<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\IAM\Models\LegalDocument;
use App\Modules\IAM\Models\LegalConsent;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class LegalController extends Controller
{
    public function showConsent(Request $request)
    {
        $type = $request->get('type', 'terms');
        $document = LegalDocument::getActive($type);

        if (!$document) {
            return redirect()->route('dashboard');
        }

        // Si ya lo aceptó, pasar al siguiente o al dashboard
        $user = Auth::user();
        $alreadyAccepted = $user->consents()
            ->where('legal_document_id', $document->id)
            ->wherePivot('version', $document->version)
            ->exists();

        if ($alreadyAccepted) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('Legal/Consent', [
            'document' => $document,
            'type' => $type
        ]);
    }

    public function accept(Request $request, LegalDocument $document)
    {
        $user = Auth::user();

        // Evitar duplicados si hace doble clic
        $exists = $user->consents()
            ->where('legal_document_id', $document->id)
            ->wherePivot('version', $document->version)
            ->exists();

        if (!$exists) {
            LegalConsent::create([
                'user_id' => $user->id,
                'legal_document_id' => $document->id,
                'version' => $document->version,
                'accepted_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Gracias por aceptar nuestros términos y condiciones.');
    }
}
