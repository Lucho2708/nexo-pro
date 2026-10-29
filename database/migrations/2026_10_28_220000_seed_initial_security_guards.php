<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use App\Modules\IAM\Models\User;
use App\Modules\IAM\Models\Role;
use App\Modules\IAM\Models\LegalDocument;
use App\Modules\IAM\Models\LegalConsent;
use App\Modules\Property\Models\Copropiedad;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Garantiza la existencia de usuarios de prueba para el módulo de portería en QA/Staging.
     */
    public function up(): void
    {
        try {
            $copropiedad = Copropiedad::first();
            $copropiedadId = $copropiedad?->id;

            $securityRole = Role::where('name', 'security')->first();
            $password = Hash::make('password');

            // 1. Guarda Titular
            $guarda = User::updateOrCreate(
                ['email' => 'guarda@nexo.pro'],
                [
                    'name' => 'Carlos Rodríguez (Guarda Titular)',
                    'password' => $password,
                    'role' => 'security',
                    'email_verified_at' => now(),
                    'current_copropiedad_id' => $copropiedadId,
                    'pin_porteria' => '4812',
                    'is_active' => true,
                ]
            );

            if ($securityRole) {
                $guarda->roles()->syncWithoutDetaching([$securityRole->id]);
            }

            // 2. Guarda Relevo
            $guardaRelevo = User::updateOrCreate(
                ['email' => 'guarda_relevo@nexo.pro'],
                [
                    'name' => 'Nelson Gómez (Guarda Relevo)',
                    'password' => $password,
                    'role' => 'security',
                    'email_verified_at' => now(),
                    'current_copropiedad_id' => $copropiedadId,
                    'pin_porteria' => '9945',
                    'is_active' => true,
                ]
            );

            if ($securityRole) {
                $guardaRelevo->roles()->syncWithoutDetaching([$securityRole->id]);
            }

            // Pre-aprobar documentos legales para evitar bloqueos por middleware ensure-legal
            $activeDocs = LegalDocument::where('is_active', true)->get();
            foreach ([$guarda, $guardaRelevo] as $u) {
                foreach ($activeDocs as $doc) {
                    LegalConsent::firstOrCreate([
                        'user_id' => $u->id,
                        'legal_document_id' => $doc->id,
                        'version' => $doc->version,
                    ], [
                        'accepted_at' => now(),
                        'ip_address' => '127.0.0.1',
                    ]);
                }
            }
        } catch (\Throwable $e) {
            // No bloquear migración si las tablas aún se están construyendo
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            User::whereIn('email', ['guarda@nexo.pro', 'guarda_relevo@nexo.pro'])->delete();
        } catch (\Throwable $e) {
            // Ignorar en rollback si la tabla no existe
        }
    }
};
