<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\OnlinePayment;
use App\Models\Unidad;
use App\Models\ConceptoCobro;
use App\Services\Payments\WompiService;
use App\Services\Financial\FinancialService;
use App\DTOs\TransactionDataDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct(
        private WompiService $wompiService,
        private FinancialService $financialService
    ) {}

    /**
     * Prepara la sesión de pago y genera la firma de integridad.
     */
    public function initiate(Request $request)
    {
        $request->validate([
            'unidad_id' => 'required|exists:unidades,id',
            'amount' => 'required|numeric|min:1000',
        ]);

        $unidad = Unidad::with('copropiedad')->findOrFail($request->unidad_id);
        
        // Configurar Wompi con las llaves del conjunto
        $this->wompiService->forCopropiedad($unidad->copropiedad);

        $reference = 'NEXO-' . strtoupper(Str::random(10));

        $payment = OnlinePayment::create([
            'user_id' => auth()->id(),
            'unidad_id' => $unidad->id,
            'amount' => $request->amount,
            'currency' => 'COP',
            'reference' => $reference,
            'status' => 'PENDING',
        ]);

        $signature = $this->wompiService->generateIntegritySignature(
            $reference,
            $request->amount
        );

        return response()->json([
            'public_key' => $this->wompiService->getPublicKey(),
            'reference' => $reference,
            'amount_in_cents' => (int) ($request->amount * 100),
            'signature' => $signature,
            'payment_id' => $payment->id,
        ]);
    }

    /**
     * Maneja la notificación segura de Wompi.
     */
    public function handleWebhook(Request $request)
    {
        $checksum = $request->header('X-Event-Checksum');
        $timestamp = $request->header('X-Event-Timestamp');
        $data = $request->input('data');

        $transaction = $data['transaction'];
        $payment = OnlinePayment::with('unidad.copropiedad')->where('reference', $transaction['reference'])->first();

        if (!$payment) {
            Log::error("Wompi Webhook: Payment reference not found: " . ($transaction['reference'] ?? 'NULL'));
            return response()->json(['message' => 'Reference not found'], 404);
        }

        // Configurar Wompi con las llaves del conjunto antes de validar
        $this->wompiService->forCopropiedad($payment->unidad->copropiedad);

        if (!$this->wompiService->validateWebhookChecksum($data, $timestamp, $checksum)) {
            Log::warning("Wompi Webhook: Invalid Checksum for reference {$payment->reference}.");
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Evitar procesamiento duplicado
        if ($payment->status === 'APPROVED') {
            return response()->json(['message' => 'Already processed']);
        }

        $payment->update([
            'wompi_id' => $transaction['id'],
            'status' => $transaction['status'],
            'payment_method' => $transaction['payment_method_type'] ?? null,
            'gateway_response' => $data,
        ]);

        if ($transaction['status'] === 'APPROVED') {
            $this->finalizePayment($payment);
        }

        return response()->json(['message' => 'OK']);
    }

    /**
     * Registra el pago en la contabilidad interna de la copropiedad.
     */
    protected function finalizePayment(OnlinePayment $payment)
    {
        // Buscar el concepto de administración por defecto para la copropiedad
        $concepto = ConceptoCobro::where('copropiedad_id', $payment->unidad->copropiedad_id)
            ->where('codigo', 'ADM')
            ->first();

        $dto = new TransactionDataDTO(
            unidadId: $payment->unidad_id,
            conceptoId: $concepto?->id ?? ConceptoCobro::where('copropiedad_id', $payment->unidad->copropiedad_id)->first()?->id,
            tipo: 'abono',
            monto: $payment->amount,
            fecha: now()->toDateString(),
            referencia: "Wompi: " . $payment->reference,
            soportePath: null,
            userId: $payment->user_id
        );

        $this->financialService->registerTransaction($dto);
        
        Log::info("Wompi Payment Finalized: Unit {$payment->unidad_id}, Amount {$payment->amount}");
    }
}
