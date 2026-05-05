<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\IAM\Models\LegalDocument;
use Inertia\Inertia;

class LegalManagementController extends Controller
{
    public function index()
    {
        return Inertia::render('SuperAdmin/Legal/Index', [
            'documents' => LegalDocument::orderBy('type')->orderByDesc('created_at')->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('SuperAdmin/Legal/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:terms,privacy,cookies',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'version' => 'required|string|max:20',
            'activate' => 'boolean'
        ]);

        $activate = $request->get('activate', true);

        if ($activate) {
            // Desactivar previos del mismo tipo
            LegalDocument::where('type', $validated['type'])->update(['is_active' => false]);
        }

        LegalDocument::create([
            'type' => $validated['type'],
            'title' => $validated['title'],
            'body' => $validated['body'],
            'version' => $validated['version'],
            'is_active' => $activate
        ]);

        return redirect()->route('superadmin.legal.index')->with('success', 'Documento legal registrado correctamente.');
    }

    public function toggle(LegalDocument $document)
    {
        if (!$document->is_active) {
            // Si vamos a activar, desactivamos los otros
            LegalDocument::where('type', $document->type)->update(['is_active' => false]);
            $document->update(['is_active' => true]);
        } else {
            $document->update(['is_active' => false]);
        }

        return back()->with('success', 'Estado del documento actualizado.');
    }
}
