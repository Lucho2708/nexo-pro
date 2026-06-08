<?php

use App\Modules\IAM\Models\User;
use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\Unidad;
use App\Modules\Operations\Models\Pqrs;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Mail\PqrsCreatedNotification;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->copropiedad = Copropiedad::factory()->create();
    
    // Admin setup
    $this->admin = User::factory()->create([
        'role' => 'admin',
        'current_copropiedad_id' => $this->copropiedad->id,
    ]);
    $this->admin->managedCopropiedades()->attach($this->copropiedad->id);

    // Owner setup
    $this->owner = User::factory()->create([
        'role' => 'owner',
        'current_copropiedad_id' => $this->copropiedad->id,
    ]);
    
    $this->unidad = Unidad::factory()->create([
        'copropiedad_id' => $this->copropiedad->id,
    ]);
    $this->unidad->users()->attach($this->owner->id, ['role' => 'owner']);
});

describe('PqrsController', function () {
    describe('index', function () {
        it('allows owner to see their pqrs', function () {
            Pqrs::factory()->create([
                'user_id' => $this->owner->id,
                'unidad_id' => $this->unidad->id,
                'copropiedad_id' => $this->copropiedad->id,
            ]);

            $response = $this->actingAs($this->owner)->get(route('pqrs.index'));

            $response->assertOk()
                ->assertInertia(fn ($page) => $page->component('Owner/Pqrs/Index'));
        });

        it('allows admin to see copropiedad pqrs', function () {
            Pqrs::factory()->create([
                'user_id' => $this->owner->id,
                'unidad_id' => $this->unidad->id,
                'copropiedad_id' => $this->copropiedad->id,
            ]);

            $response = $this->actingAs($this->admin)->get(route('pqrs.index'));

            $response->assertOk()
                ->assertInertia(fn ($page) => $page->component('Admin/Pqrs/Index'));
        });
    });

    describe('store', function () {
        it('validates required fields', function () {
            $response = $this->actingAs($this->owner)->post(route('pqrs.store'), []);

            $response->assertSessionHasErrors(['unidad_id', 'tipo', 'asunto', 'mensaje', 'prioridad']);
        });

        it('allows owner to create a pqrs with attachments and notifies admin', function () {
            Storage::fake('public');
            Mail::fake();

            $file = UploadedFile::fake()->image('photo.jpg');

            $response = $this->actingAs($this->owner)->post(route('pqrs.store'), [
                'unidad_id' => $this->unidad->id,
                'tipo' => 'queja',
                'asunto' => 'Ruido',
                'mensaje' => 'Mucho ruido en la noche',
                'prioridad' => 'media',
                'adjuntos' => [$file],
            ]);

            $response->assertRedirect()->assertSessionHas('success', 'PQRS radicada exitosamente.');

            $this->assertDatabaseHas('operations.pqrs', [
                'user_id' => $this->owner->id,
                'unidad_id' => $this->unidad->id,
                'tipo' => 'queja',
                'asunto' => 'Ruido',
                'prioridad' => 'media',
            ]);

            $pqrs = Pqrs::first();
            
            // Check file upload
            expect($pqrs->adjuntos)->toBeArray()->toHaveCount(1);
            Storage::disk('public')->assertExists($pqrs->adjuntos[0]);

            // Check email sent
            Mail::assertSent(PqrsCreatedNotification::class, function ($mail) use ($pqrs) {
                return $mail->hasTo($this->admin->email) &&
                       $mail->pqrs->id === $pqrs->id;
            });
        });
    });

    describe('update', function () {
        it('allows admin to respond and close pqrs', function () {
            $pqrs = Pqrs::factory()->create([
                'user_id' => $this->owner->id,
                'unidad_id' => $this->unidad->id,
                'copropiedad_id' => $this->copropiedad->id,
                'estado' => 'abierto',
            ]);

            $response = $this->actingAs($this->admin)->patch(route('pqrs.update', $pqrs), [
                'respuesta' => 'Estamos trabajando en ello',
                'cerrar' => true,
            ]);

            $response->assertRedirect()->assertSessionHas('success', 'Respuesta enviada exitosamente.');

            $this->assertDatabaseHas('operations.pqrs', [
                'id' => $pqrs->id,
                'respuesta' => 'Estamos trabajando en ello',
                'estado' => 'cerrado',
            ]);
        });

        it('allows owner to reopen their pqrs', function () {
            $pqrs = Pqrs::factory()->create([
                'user_id' => $this->owner->id,
                'unidad_id' => $this->unidad->id,
                'copropiedad_id' => $this->copropiedad->id,
                'estado' => 'cerrado',
            ]);

            $response = $this->actingAs($this->owner)->patch(route('pqrs.update', $pqrs), []);

            $response->assertRedirect()->assertSessionHas('success', 'PQRS reabierta.');

            $this->assertDatabaseHas('operations.pqrs', [
                'id' => $pqrs->id,
                'estado' => 'reabierto',
            ]);
        });

        it('prevents owner from responding to their own pqrs', function () {
            $pqrs = Pqrs::factory()->create([
                'user_id' => $this->owner->id,
                'unidad_id' => $this->unidad->id,
                'copropiedad_id' => $this->copropiedad->id,
                'estado' => 'abierto',
            ]);

            $response = $this->actingAs($this->owner)->patch(route('pqrs.update', $pqrs), [
                'respuesta' => 'Me respondo a mi mismo',
                'cerrar' => true,
            ]);

            // In Controller: if owner -> repoen. It ignores the 'respuesta' and 'cerrar' inputs and only reopens.
            // Wait, actually PqrsPolicy controls 'update'. Let's see what PqrsPolicy says.
            // For now, let's just see if it doesn't process the response.
            $response->assertRedirect();
            
            $this->assertDatabaseMissing('operations.pqrs', [
                'id' => $pqrs->id,
                'respuesta' => 'Me respondo a mi mismo',
            ]);
        });
    });

    describe('downloadPdf', function () {
        it('allows authorized users to download pdf', function () {
            $this->withoutExceptionHandling();
            
            $pqrs = Pqrs::factory()->create([
                'user_id' => $this->owner->id,
                'unidad_id' => $this->unidad->id,
                'copropiedad_id' => $this->copropiedad->id,
            ]);

            $response = $this->actingAs($this->owner)->get(route('pqrs.download', $pqrs));

            $response->assertOk();
            $response->assertHeader('content-type', 'application/pdf');
        });
    });
});
