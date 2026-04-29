<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asamblea;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class AsambleaManagementController extends Controller
{
    public function index()
    {
        $copropiedadId = Auth::user()->current_copropiedad_id;
        
        $asambleas = Asamblea::where('copropiedad_id', $copropiedadId)
            ->withCount(['preguntas'])
            ->latest()
            ->get();

        return Inertia::render('Admin/Asambleas/Index', [
            'asambleas' => $asambleas
        ]);
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
