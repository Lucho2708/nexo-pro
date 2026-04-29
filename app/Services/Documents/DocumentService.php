<?php

namespace App\Services\Documents;

use App\Models\Unidad;
use App\Models\Copropiedad;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    /**
     * Generar el PDF del Estado de Cuenta para una unidad.
     */
    public function generateAccountStatement(Unidad $unidad)
    {
        $copropiedad = $unidad->copropiedad;
        $transacciones = $unidad->transacciones()
            ->with('concepto')
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->take(20) // Últimos 20 movimientos
            ->get();

        $pdf = Pdf::loadView('reports.account-statement', [
            'unidad' => $unidad,
            'copropiedad' => $copropiedad,
            'transacciones' => $transacciones,
            'generated_at' => now(),
        ]);

        // Opciones para asegurar calidad
        $pdf->setPaper('letter', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'sans-serif'
        ]);

        return $pdf;
    }
}
