<?php

namespace App\Services;

use Agence104\LiveKit\AccessToken;
use Agence104\LiveKit\AccessTokenOptions;
use Agence104\LiveKit\VideoGrant;
use App\Models\Asamblea;
use App\Models\User;
use App\Models\Unidad;
use App\Models\Pregunta;
use App\Models\Opcion;
use App\Models\Voto;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class AsambleaService
{
    /**
     * Generate an access token for LiveKit.
     */
    public function generateToken(User $user, Unidad $unidad): string
    {
        $apiKey = config('services.livekit.key');
        $apiSecret = config('services.livekit.secret');
        $roomName = "asamblea_" . $user->current_copropiedad_id;

        $identity = "T-{$unidad->torre} U-{$unidad->nombre} | {$user->name}";

        $tokenOptions = (new AccessTokenOptions())
            ->setIdentity($identity);

        $videoGrant = (new VideoGrant())
            ->setRoomJoin()
            ->setRoomName($roomName);

        $token = (new AccessToken($apiKey, $apiSecret, $tokenOptions))
            ->setGrant($videoGrant);

        return $token->toJwt();
    }

    /**
     * Get the authenticated unit for the user in the given asamblea context.
     * For admins, it might return null but we handle it in the controller.
     */
    public function getAuthenticatedUnit(User $user, Asamblea $asamblea): ?Unidad
    {
        if ($user->isAdmin() || $user->isSuperAdmin()) {
            return null; // Admins don't have a unit
        }

        return $user->unidades()
            ->where('copropiedad_id', $asamblea->copropiedad_id)
            ->first();
    }

    /**
     * Cast a vote for a question.
     */
    public function castVote(User $user, Unidad $unidad, Pregunta $pregunta, Opcion $opcion, Asamblea $asamblea): Voto
    {
        if ($pregunta->status !== 'open') {
            throw new \Exception('La votación no está abierta.');
        }

        return DB::transaction(function () use ($user, $unidad, $pregunta, $opcion, $asamblea) {
            $exists = Voto::where('pregunta_id', $pregunta->id)
                ->where('unidad_id', $unidad->id)
                ->lockForUpdate() // Prevenir race conditions
                ->exists();

            if ($exists) {
                throw new \Exception('Esta unidad ya ha registrado su voto.');
            }

            $voto = Voto::create([
                'pregunta_id' => $pregunta->id,
                'user_id' => $user->id,
                'unidad_id' => $unidad->id,
                'opcion_id' => $opcion->id,
                'peso' => $unidad->coeficiente ?? 0,
            ]);

            $this->logEvent($asamblea, $user, $unidad, 'vote', [
                'pregunta_id' => $pregunta->id,
                'opcion_id' => $opcion->id
            ]);

            return $voto;
        });
    }

    /**
     * Check if a user can join the assembly for a specific unit.
     */
    public function canJoin(User $user, Unidad $unidad): bool
    {
        $cacheKey = $this->getConnectionKey($unidad);
        $connectedUserId = Cache::get($cacheKey);

        // Si no hay nadie, puede entrar.
        // Si el que está conectado es el MISMO usuario, puede entrar (traspaso de sesión).
        return !$connectedUserId || $connectedUserId === $user->id;
    }

    /**
     * Register an active connection for a unit.
     */
    public function registerConnection(User $user, Unidad $unidad, Asamblea $asamblea): void
    {
        $cacheKey = $this->getConnectionKey($unidad);
        Cache::put($cacheKey, $user->id, now()->addHours(4));
        $this->logEvent($asamblea, $user, $unidad, 'login', ['ip' => request()->ip()]);
    }

    /**
     * Clear the connection for a unit.
     */
    public function clearConnection(Unidad $unidad): void
    {
        $cacheKey = $this->getConnectionKey($unidad);
        Cache::forget($cacheKey);
    }

    /**
     * Log an event in a dynamic asamblea audit table.
     */
    public function logEvent(Asamblea $asamblea, User $user, Unidad $unidad, string $type, array $payload = []): void
    {
        $tableName = $asamblea->getLogTableName();
        $this->ensureLogTableExists($tableName);

        DB::table($tableName)->insert([
            'copropiedad_id' => $asamblea->copropiedad_id,
            'user_id' => $user->id,
            'unidad_id' => $unidad->id,
            'event_type' => $type,
            'payload' => json_encode($payload),
            'ip_address' => request()->ip() ?? '127.0.0.1',
            'user_agent' => request()->userAgent() ?? 'System',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Ensure the dynamic log table exists.
     */
    public function ensureLogTableExists(string $tableName): void
    {
        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->uuid('copropiedad_id');
                $table->uuid('user_id');
                $table->uuid('unidad_id')->nullable();
                $table->string('event_type');
                $table->json('payload')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();
                $table->index(['event_type']);
            });
        }
    }

    private function getConnectionKey(Unidad $unidad): string
    {
        return "asamblea_conn_unidad_{$unidad->id}";
    }
}
