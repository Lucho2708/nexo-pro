<?php

namespace App\Modules\Asamblea\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Asamblea\Models\Asamblea;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class AsambleaManagementController extends \App\Http\Controllers\Controller
{
    use \App\Traits\Auditable;

    public function __construct(
        protected \App\Services\Tenant\AssemblyTableManager $tableManager,
        protected \App\Modules\Asamblea\Actions\ImportVotersAction $importVotersAction
    ) {}

    public function importVoters(Request $request)
    {
        $request->validate([
            'voters' => 'required|array',
            'voters.*.unidad' => 'required|string',
            'voters.*.nombre' => 'required|string',
            'voters.*.documento' => 'required|string',
            'voters.*.coeficiente' => 'required|numeric',
        ]);

        $copropiedad = Auth::user()->currentCopropiedad;
        $result = $this->importVotersAction->execute($copropiedad, $request->voters);

        $this->audit('ASAMBLEA', 'IMPORT_VOTERS', [
            'count' => $result['success'],
            'copropiedad_id' => $copropiedad->id
        ]);

        return back()->with('success', "Importados {$result['success']} votantes correctamente.");
    }

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
        $calculator = new \App\Modules\Property\Services\CoeficienteCalculator();
        $calculator->calculateForCopropiedad($copropiedad);

        $this->audit('ASAMBLEA', 'RECALCULATE_COEFICIENTES', [
            'copropiedad_id' => $copropiedad->id
        ]);

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

        $asamblea = Asamblea::create($validated);

        $this->audit('ASAMBLEA', 'CREATE_ASSEMBLY', [
            'asamblea_id' => $asamblea->id,
            'titulo' => $asamblea->titulo
        ]);

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

        $this->audit('ASAMBLEA', 'TOGGLE_STATUS', [
            'asamblea_id' => $asamblea->id,
            'status' => $newStatus
        ]);

        return back()->with('success', $asamblea->status === 'in_progress' ? 'Asamblea activada. Los propietarios ya pueden ingresar.' : 'Asamblea marcada como finalizada.');
    }

    public function destroy(Asamblea $asamblea)
    {
        if ($asamblea->copropiedad_id !== Auth::user()->current_copropiedad_id) {
            abort(403);
        }

        $id = $asamblea->id;
        $titulo = $asamblea->titulo;
        
        $asamblea->delete();

        $this->audit('ASAMBLEA', 'DELETE_ASSEMBLY', [
            'asamblea_id' => $id,
            'titulo' => $titulo
        ]);

        return back()->with('success', 'Asamblea eliminada.');
    }
}
