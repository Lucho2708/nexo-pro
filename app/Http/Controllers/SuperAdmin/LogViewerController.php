<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class LogViewerController extends Controller
{
    /**
     * Muestra el panel de monitoreo de logs del sistema
     */
    public function index(Request $request)
    {
        $query = SystemLog::with(['copropiedad', 'user']);

        // Filtrado por contexto de texto
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('message', 'like', "%{$search}%")
                  ->orWhere('env', 'like', "%{$search}%");
            });
        }

        // Filtrado por nivel de severidad ("INFO", "ERROR", etc.)
        if ($request->filled('level_name')) {
            $query->where('level_name', $request->level_name);
        }

        // Filtrado por id de conjunto (Multi-tenant)
        if ($request->filled('copropiedad_id')) {
            $query->where('copropiedad_id', $request->copropiedad_id);
        }

        // Ordenamiento (por defecto priorizamos errores críticos y fecha reciente)
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');

        if ($sortBy === 'severity') {
            // Priority ordering based on monolog standard levels (higher number = more critical, eg ERROR = 400)
            $query->orderBy('level', $sortDir);
            $query->orderBy('created_at', 'desc'); // desempate
        } else {
            $query->orderBy($sortBy, $sortDir);
        }

        $logs = $query->paginate(50)->withQueryString();

        // Extraer métricas rápidas de los últimos 7 días
        $recentMetrics = SystemLog::select('level_name', DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('level_name')
            ->pluck('total', 'level_name');

        return Inertia::render('Admin/Logs/Index', [
            'logs' => $logs,
            'filters' => $request->only(['search', 'level_name', 'copropiedad_id', 'sort_by', 'sort_dir']),
            'metrics' => $recentMetrics
        ]);
    }

    /**
     * Flush / Clear old logs (opcional, para mantenimiento)
     */
    public function purge()
    {
        SystemLog::where('created_at', '<', now()->subDays(30))->delete();
        return back()->with('success', 'Logs anteriores a 30 días eliminados exitosamente.');
    }
}
