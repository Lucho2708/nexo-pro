<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asamblea;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class AsambleaManagementController extends Controller
{
    public function __construct(
        protected \App\Services\Tenant\AssemblyTableManager $tableManager
    ) {}

    public function index()
    {
        $copropiedadId = Auth::user()->current_copropiedad_id;
        $copropiedad = Auth::user()->currentCopropiedad;
        
        $asambleas = Asamblea::where('copropiedad_id', $copropiedadId)
            ->withCount(['preguntas'])
            ->latest()
            ->get();

        $unidades = $copropiedad->unidades()->with('tipoUnidad')->orderBy('torre')->orderBy('piso')->orderBy('nombre')->get();
        $totalAreaUnidades = $unidades->sum(function($unidad) {
            return $unidad->tipoUnidad ? $unidad->tipoUnidad->area_m2 : 0;
        });
        $totalCoeficientes = $unidades->sum('coeficiente');

        return Inertia::render('Admin/Asambleas/Index', [
            'asambleas' => $asambleas,
            'auditoria' => [
                'area_construida_total' => $copropiedad->area_construida_total ?? 0,
                'area_unidades_total' => $totalAreaUnidades,
                'total_coeficientes' => round($totalCoeficientes, 4),
                'unidades' => $unidades,
            ]
        ]);
    }

    public function recalculateCoeficientes()
    {
        $copropiedad = Auth::user()->currentCopropiedad;
        $calculator = new \App\Services\CoeficienteCalculator();
        $calculator->calculateForCopropiedad($copropiedad);

        return back()->with('success', 'Coeficientes recalculados correctamente en base al área construida total.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'quorum_esperado' => 'required|numeric|min:0|max:100',
        ]);

        $validated['copropiedad_id'] = Auth::user()->current_copropiedad_id;
        $validated['status'] = 'scheduled';
        $validated['settings'] = [
            'quorum_esperado' => $validated['quorum_esperado'],
            'allow_chat' => true,
            'require_video' => false,
            'auto_close_questions' => true
        ];

        // Combinar fecha y hora
        $validated['fecha'] = $validated['fecha'] . ' ' . $validated['hora_inicio'];

        Asamblea::create($validated);

        return back()->with('success', 'Asamblea programada correctamente.');
    }

    public function toggle(Asamblea $asamblea)
    {
        if ($asamblea->copropiedad_id !== Auth::user()->current_copropiedad_id) {
            abort(403);
        }

        $newStatus = $asamblea->status === 'in_progress' ? 'finished' : 'in_progress';
        $asamblea->update(['status' => $newStatus]);

        if ($newStatus === 'in_progress') {
            $shortId = substr($asamblea->id, 0, 8);
            $this->tableManager->createAssemblyEcosystem($shortId, $asamblea->copropiedad_id);
        }

        return back()->with('success', $asamblea->status === 'in_progress' ? 'Asamblea activada. Los propietarios ya pueden ingresar.' : 'Asamblea marcada como finalizada.');
    }

    public function destroy(Asamblea $asamblea)
    {
        if ($asamblea->copropiedad_id !== Auth::user()->current_copropiedad_id) {
            abort(403);
        }

        $asamblea->delete();

        return back()->with('success', 'Asamblea eliminada.');
    }
}
