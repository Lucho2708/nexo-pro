<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Modules\Operations\Models\FeatureUsageLog;
use App\Modules\Operations\Models\SystemModule;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $query = FeatureUsageLog::with(['user', 'copropiedad']);

        // Filtro por Módulo
        if ($request->filled('feature')) {
            $query->where('feature', $request->feature);
        }

        // Filtro por Conjunto (Copropiedad)
        if ($request->filled('copropiedad_id')) {
            $query->where('copropiedad_id', $request->copropiedad_id);
        }

        // Búsqueda por Email o Nombre
        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('email', 'like', "%{$request->search}%")
                  ->orWhere('name', 'like', "%{$request->search}%");
            });
        }

        // Rango de Fechas
        if ($request->filled('date_from')) {
            $query->whereDate('used_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('used_at', '<=', $request->date_to);
        }

        $logs = $query->latest('used_at')
            ->paginate(40)
            ->withQueryString();

        return Inertia::render('SuperAdmin/Audit/Index', [
            'logs' => $logs->through(fn($log) => [
                'id' => $log->id,
                'feature' => $log->feature,
                'action' => $log->action,
                'metadata' => $log->metadata, // DNA técnico
                'user' => $log->user ? [
                    'id' => $log->user->id,
                    'name' => $log->user->name,
                    'email' => $log->user->email,
                    'avatar' => "https://api.dicebear.com/7.x/avataaars/svg?seed=" . urlencode($log->user->email),
                ] : [
                    'name' => 'Sistema',
                    'avatar' => "https://api.dicebear.com/7.x/bottts/svg?seed=system",
                ],
                'copropiedad' => $log->copropiedad ? [
                    'id' => $log->copropiedad->id,
                    'nombre' => $log->copropiedad->nombre,
                ] : null,
                'used_at' => $log->used_at->format('Y-m-d H:i:s'),
                'used_at_human' => $log->used_at->diffForHumans(),
            ]),
            'features' => SystemModule::where('is_active', true)
                ->orderBy('name')
                ->get()
                ->map(fn($m) => [
                    'value' => $m->feature,
                    'label' => $m->name
                ]),
            'copropiedades' => \App\Modules\Property\Models\Copropiedad::select('id', 'nombre')->get(),
            'filters' => $request->only(['feature', 'search', 'copropiedad_id', 'date_from', 'date_to']),
            'chartData' => $this->getUsageChartData($request->feature),
        ]);
    }

    public function export(Request $request)
    {
        $query = FeatureUsageLog::with(['user', 'copropiedad']);

        if ($request->filled('feature')) $query->where('feature', $request->feature);
        if ($request->filled('copropiedad_id')) $query->where('copropiedad_id', $request->copropiedad_id);
        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('email', 'like', "%{$request->search}%")
                  ->orWhere('name', 'like', "%{$request->search}%");
            });
        }
        if ($request->filled('date_from')) $query->whereDate('used_at', '>=', $request->date_from);
        if ($request->filled('date_to')) $query->whereDate('used_at', '<=', $request->date_to);

        $logs = $query->latest('used_at')->limit(2000)->get(); // Limite preventivo

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="auditoria_forense_' . now()->format('Ymd_His') . '.csv"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'FECHA', 'MODULO', 'ACCION', 'USUARIO', 'EMAIL', 'COPROPIEDAD', 'METADATA_JSON']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->used_at->format('Y-m-d H:i:s'),
                    $log->feature,
                    $log->action,
                    $log->user->name ?? 'Sistema Nucleo',
                    $log->user->email ?? 'ROOT',
                    $log->copropiedad->nombre ?? 'GLOBAL',
                    json_encode($log->metadata)
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getUsageChartData($selectedFeature = null)
    {
        $days = 15;
        $data = [];
        $categories = [];

        for ($i = $days; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $categories[] = now()->subDays($i)->format('d M');

            $query = FeatureUsageLog::whereDate('used_at', $date);
            if ($selectedFeature) {
                $query->where('feature', $selectedFeature);
            }

            $data[] = $query->count();
        }

        return [
            'categories' => $categories,
            'series' => [
                [
                    'name' => $selectedFeature ? "Uso de {$selectedFeature}" : 'Uso Global',
                    'data' => $data
                ]
            ]
        ];
    }
}
