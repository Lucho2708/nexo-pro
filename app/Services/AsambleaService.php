<?php

namespace App\Services;

use Agence104\LiveKit\AccessToken;
use Agence104\LiveKit\AccessTokenOptions;
use Agence104\LiveKit\VideoGrant;
use App\Models\Asamblea;
use App\Modules\IAM\Models\User;
use App\Models\Unidad;
use App\Models\Pregunta;
use App\Models\Opcion;
use App\Models\Voto;
use App\Events\VoteCast;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

use App\Services\Tenant\AssemblyTableManager;

class AsambleaService
{
    protected $tableManager;

    public function __construct(AssemblyTableManager $tableManager)
    {
        $this->tableManager = $tableManager;
    }
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
     * Get all units the user represents in the assembly.
     */
    public function getAuthenticatedUnits(User $user, Asamblea $asamblea)
    {
        if ($user->isAdmin() || $user->isSuperAdmin()) {
            return collect();
        }

        $shortId = substr($asamblea->id, 0, 8);
        $quorumTable = "asquorum_{$shortId}";

        // Si existe la tabla de quórum dinámica (aislamiento), la usamos
        if (Schema::hasTable($quorumTable)) {
            $unitIds = DB::table($quorumTable)
                ->where(function($q) use ($user, $quorumTable) {
                    $q->where("{$quorumTable}.user_id", $user->id)
                      ->orWhereExists(function ($query) use ($user, $quorumTable) {
                          $query->select(DB::raw(1))
                              ->from('unidad_user')
                              ->whereRaw("unidad_user.unidad_id = {$quorumTable}.unidad_id")
                              ->where('unidad_user.user_id', $user->id);
                      });
                })
                ->pluck('unidad_id')
                ->toArray();

            return Unidad::withoutGlobalScopes()
                ->whereIn('id', $unitIds)
                ->get();
        }

        // Fallback a la lógica original (NEXO PRO Nativo)
        return $user->unidades()
            ->where('copropiedad_id', $asamblea->copropiedad_id)
            ->get();
    }

    /**
     * Initialize the dynamic isolated ecosystem for an assembly.
     */
    public function initializeDynamicEcosystem(Asamblea $asamblea): void
    {
        $shortId = substr($asamblea->id, 0, 8);
        $this->tableManager->createAssemblyEcosystem($shortId, $asamblea->copropiedad_id);
    }

    /**
     * Helper for legacy or single-unit contexts.
     */
    public function getAuthenticatedUnit(User $user, Asamblea $asamblea)
    {
        return $this->getAuthenticatedUnits($user, $asamblea)->first();
    }

    public function castVote(User $user, $unidades, Pregunta $pregunta, Opcion $opcion, Asamblea $asamblea): void
    {
        // Ensure we work with a collection for multi-unit support
        $unidades = $unidades instanceof \Illuminate\Support\Collection ? $unidades : collect([$unidades]);

        if ($pregunta->status !== 'open') {
            throw new \Exception('La votación no está abierta.');
        }

        if ($unidades->isEmpty()) {
            throw new \Exception('No tienes unidades asociadas para votar.');
        }

        DB::transaction(function () use ($user, $unidades, $pregunta, $opcion, $asamblea) {
            $shortId = substr($asamblea->id, 0, 8);
            $votesRegistered = 0;
            
            foreach ($unidades as $unidad) {
                if ($this->tableManager->hasVoted($shortId, $pregunta->id, $unidad->id)) {
                    continue;
                }
                
                $this->tableManager->registerVote($shortId, [
                    'pregunta_id' => $pregunta->id,
                    'user_id' => $user->id,
                    'unidad_id' => $unidad->id,
                    'opcion_id' => $opcion->id,
                    'peso' => $unidad->coeficiente ?? 0,
                ], $asamblea->copropiedad_id);

                // Global table for legacy support and analytics
                Voto::create([
                    'pregunta_id' => $pregunta->id,
                    'opcion_id' => $opcion->id,
                    'unidad_id' => $unidad->id,
                    'user_id' => $user->id,
                    'peso' => $unidad->coeficiente ?? 0,
                ]);

                // Still log the event in the dynamic log table for history
                $this->logEvent($asamblea, $user, $unidad, 'vote', [
                    'pregunta_id' => $pregunta->id,
                    'opcion_id' => $opcion->id
                ]);

                $votesRegistered++;
            }

            if ($votesRegistered === 0) {
                throw new \Exception('Ya has votado con todas tus unidades disponibles.');
            }

            broadcast(new VoteCast($asamblea, $pregunta, [
                'user_id' => $user->id,
                'unidades_count' => $unidades->count(),
                'total_peso' => $unidades->sum('coeficiente')
            ]))->toOthers();
        });
    }

    /**
     * Get questions and their options, either from dynamic tables or central tables.
     */
    public function getPreguntasWithOpciones(Asamblea $asamblea)
    {
        $shortId = substr($asamblea->id, 0, 8);
        $questionsTable = "aspreguntas_{$shortId}";
        $optionsTable = "asopciones_{$shortId}";

        if (Schema::hasTable($questionsTable) && Schema::hasTable($optionsTable)) {
            $questions = DB::table($questionsTable)->latest()->get();
            $options = DB::table($optionsTable)->orderBy('order')->get();

            return $questions->map(function ($q) use ($options) {
                $q->opciones = $options->where('pregunta_id', $q->id)->values();
                return $q;
            });
        }

        return $asamblea->preguntas()->with('opciones')->latest()->get();
    }

    /**
     * Check if a user can join the assembly for a specific unit.
     */
    public function canJoin(User $user, Unidad $unidad): bool
    {
        $cacheKey = $this->getConnectionKey($unidad);
        $connectedUserId = Cache::get($cacheKey);
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
            'id' => \Illuminate\Support\Str::uuid(),
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
                $table->uuid('id')->primary();
                $table->uuid('copropiedad_id');
                $table->uuid('user_id');
                $table->uuid('unidad_id')->nullable();
                $table->string('event_type');
                $table->jsonb('payload')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();

                $table->index(['event_type']);
            });

            // GIN index for deep searching in JSONB payload (PostgreSQL specific)
            if (Schema::getConnection()->getDriverName() === 'pgsql') {
                DB::statement("CREATE INDEX idx_{$tableName}_payload_gin ON {$tableName} USING GIN (payload)");
            }
        }
    }

    private function getConnectionKey(Unidad $unidad): string
    {
        return "asamblea_conn_unidad_{$unidad->id}";
    }
}
