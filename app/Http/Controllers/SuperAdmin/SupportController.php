<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Modules\Operations\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SupportController extends Controller
{
    /**
     * Display a listing of all system tickets for SuperAdmin.
     */
    public function index()
    {
        return Inertia::render('SuperAdmin/Support/Index', [
            'tickets' => Ticket::with(['user', 'copropiedad'])
                ->orderBy('created_at', 'desc')
                ->get(),
            'stats' => [
                'open' => Ticket::where('status', 'open')->count(),
                'in_progress' => Ticket::where('status', 'in_progress')->count(),
                'resolved' => Ticket::where('status', 'resolved')->count(),
            ]
        ]);
    }

    /**
     * Update the ticket status and resolution notes.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'priority' => 'required|in:low,medium,high,critical',
            'resolution_notes' => 'nullable|string',
        ]);

        $ticket->update($request->only(['status', 'priority', 'resolution_notes']));

        return redirect()->back()->with('message', 'Incidencia actualizada correctamente.');
    }

    /**
     * Delete a ticket (SoftDelete).
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->back()->with('message', 'Ticket archivado.');
    }
}
