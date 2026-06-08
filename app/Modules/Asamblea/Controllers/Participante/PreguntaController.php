<?php

namespace App\Modules\Asamblea\Controllers\Participante;

use App\Http\Controllers\Controller;
use App\Modules\Asamblea\Models\Asamblea;
use App\Modules\Asamblea\Models\Pregunta;
use App\Modules\Asamblea\Models\Opcion;
use App\Events\AsambleaEvent;
use Illuminate\Http\Request;

class PreguntaController extends \App\Http\Controllers\Controller
{
    use \App\Traits\Auditable;

    /**
     * Store a new question for the assembly.
     */
    public function store(Request $request, Asamblea $asamblea)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:simple,multiple',
            'opciones' => 'required|array|min:2',
            'opciones.*.titulo' => 'required|string|max:100',
        ]);

        $pregunta = $asamblea->preguntas()->create([
            'titulo' => $validated['titulo'],
            'descripcion' => $validated['descripcion'],
            'tipo' => $validated['tipo'],
            'status' => 'draft',
        ]);

        foreach ($validated['opciones'] as $opc) {
            $pregunta->opciones()->create(['titulo' => $opc['titulo']]);
        }

        $this->audit('ASAMBLEA', 'CREATE_QUESTION', [
            'asamblea_id' => $asamblea->id,
            'pregunta_id' => $pregunta->id,
            'titulo' => $pregunta->titulo
        ]);

        return back()->with('success', 'Pregunta creada correctamente.');
    }

    /**
     * Activate a question to start voting.
     */
    public function open(Pregunta $pregunta)
    {
        // Cerrar cualquier otra pregunta activa primero
        Pregunta::where('asamblea_id', $pregunta->asamblea_id)
            ->where('status', 'open')
            ->update(['status' => 'closed']);

        $pregunta->update(['status' => 'open']);

        // Notificar a todos vía WebSockets
        broadcast(new AsambleaEvent($pregunta->asamblea_id, 'QuestionStarted', [
            'pregunta' => $pregunta->load('opciones')
        ]))->toOthers();

        $this->audit('ASAMBLEA', 'OPEN_QUESTION', [
            'asamblea_id' => $pregunta->asamblea_id,
            'pregunta_id' => $pregunta->id,
            'titulo' => $pregunta->titulo
        ]);

        return back()->with('success', 'Votación iniciada.');
    }

    /**
     * Close a question to stop voting.
     */
    public function close(Pregunta $pregunta)
    {
        $pregunta->update(['status' => 'closed']);

        broadcast(new AsambleaEvent($pregunta->asamblea_id, 'QuestionClosed', [
            'pregunta_id' => $pregunta->id
        ]))->toOthers();

        $this->audit('ASAMBLEA', 'CLOSE_QUESTION', [
            'asamblea_id' => $pregunta->asamblea_id,
            'pregunta_id' => $pregunta->id,
            'titulo' => $pregunta->titulo
        ]);

        return back()->with('success', 'Votación cerrada.');
    }
}
