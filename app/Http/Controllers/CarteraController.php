<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unidad;
use App\Models\ConceptoCobro;
use App\Models\Transaccion;
use App\Actions\Cartera\ImportUnitsAction;
use App\Services\Financial\FinancialService;
use App\Services\Documents\DocumentService;
use App\Repositories\Interfaces\FinancialRepositoryInterface;
use App\DTOs\TransactionDataDTO;
use App\Http\Requests\Cartera\StoreManualPaymentRequest;
use App\Http\Requests\Cartera\TriggerBillingRequest;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use App\Traits\Auditable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CarteraController extends Controller
{
    use AuthorizesRequests, Auditable;

    public function __construct(
        private FinancialService $financialService,
        private DocumentService $documentService,
        private FinancialRepositoryInterface $repository
    ) {}

    /**
     * Display the collections dashboard.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $copropiedadId = $user->current_copropiedad_id;

        $filters = $request->only(['search', 'torre', 'status']);

        $unidades = $this->repository->getUnitsWithPaginationAndFilters($copropiedadId, $filters);
        $conceptos = $this->repository->getConceptosByCopropiedad($copropiedadId);

        return Inertia::render('Cartera/Index', [
            'unidades' => $unidades,
            'conceptos' => $conceptos,
            'stats' => $this->repository->getCarteraStats($copropiedadId),
            'filters' => $filters,
            'torres' => $this->repository->getTorresByCopropiedad($copropiedadId)
        ]);
    }

    /**
     * Import units from CSV.
     */
    public function import(Request $request, ImportUnitsAction $importer)
    {
        $request->validate([
            'file' => 'required|file|mimetypes:text/csv,text/plain|extensions:csv,txt|max:5120',
        ]);

        $path = $request->file('file')->getRealPath();
        $results = $importer->execute($path, auth()->user()->current_copropiedad_id);

        $this->audit('CARTERA', 'IMPORTACION_UNIDADES', [
            'success_count' => $results['success'],
            'file_name' => $request->file('file')->getClientOriginalName(),
        ]);

        return back()->with('success', "Se importaron {$results['success']} unidades exitosamente.");
    }

    /**
     * Store a manual payment.
     */
    public function storePayment(StoreManualPaymentRequest $request)
    {
        $validated = $request->validated();

        $soportePath = $request->hasFile('soporte') 
            ? $request->file('soporte')->store('soportes_pagos', 'public')
            : null;

        $dto = TransactionDataDTO::fromRequest($validated, $soportePath);

        $this->financialService->registerTransaction($dto);

        $this->audit('CARTERA', 'REGISTRO_PAGO_MANUAL', [
            'unidad_id' => $validated['unidad_id'],
            'monto' => $validated['monto'],
            'referencia' => $validated['referencia'] ?? null,
        ]);

        return back()->with('success', 'Pago registrado correctamente.');
    }

    /**
     * Trigger mass billing process.
     */
    public function triggerMonthlyBilling(TriggerBillingRequest $request)
    {
        $validated = $request->validated();

        $user = auth()->user();
        
        \App\Jobs\Financial\GenerateMonthlyBillingJob::dispatch(
            $user->current_copropiedad_id,
            $validated['monto']
        );

        $this->audit('CARTERA', 'DISPARO_FACTURACION_MASIVA', [
            'monto_administracion' => $validated['monto'],
        ]);

        return back()->with('success', 'El proceso de facturación masiva ha iniciado en segundo plano.');
    }

    /**
     * Download account statement for a unit.
     */
    public function downloadAccountStatement(Unidad $unidad)
    {
        $this->authorize('view', $unidad);

        $pdf = $this->documentService->generateAccountStatement($unidad);
        
        return $pdf->download("Estado_Cuenta_{$unidad->torre}_{$unidad->nombre}.pdf");
    }

    private function authorizeAccess(Unidad $unidad)
    {
        $this->authorize('view', $unidad);
    }
}
