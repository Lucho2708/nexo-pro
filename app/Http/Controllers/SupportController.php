<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SupportController extends Controller
{
    /**
     * Display a listing of the user's tickets.
     */
    public function index()
    {
        $user = Auth::user();
        
        return Inertia::render('Support/Index', [
            'tickets' => Ticket::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get(),
            'categories' => [
                ['value' => 'payments', 'label' => 'Fallos en Pagos / Pasarela'],
                ['value' => 'reservations', 'label' => 'Errores de Reservas'],
                ['value' => 'billing', 'label' => 'Problemas de Facturación'],
                ['value' => 'ui_ux', 'label' => 'Error de Interfaz / Visual'],
                ['value' => 'access', 'label' => 'Acceso y Seguridad'],
                ['value' => 'performance', 'label' => 'Rendimiento / Velocidad'],
                ['value' => 'other', 'label' => 'Otro Problema Técnico'],
            ]
        ]);
    }

    /**
     * Store a newly created ticket in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:payments,reservations,billing,ui_ux,access,performance,other',
            'priority' => 'required|in:low,medium,high,critical',
        ]);

        $user = Auth::user();

        Ticket::create([
            'user_id' => $user->id,
            'copropiedad_id' => $user->copropiedad_id, // Asumimos que el usuario tiene un contexto de copropiedad
            'subject' => $request->subject,
            'description' => $request->description,
            'category' => $request->category,
            'priority' => $request->priority,
            'status' => 'open',
        ]);

        return redirect()->back()->with('message', 'Ticket de soporte generado correctamente. El equipo técnico lo revisará pronto.');
    }
}
