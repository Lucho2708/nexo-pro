<?php

namespace App\Modules\Finance\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Finance\Models\OnlinePayment;
use App\Modules\Property\Models\Unidad;
use App\Modules\Finance\Models\ConceptoCobro;
use App\Modules\Finance\Services\PaymentManager;
use App\Services\Financial\FinancialService;
use App\DTOs\TransactionDataDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentManager $paymentManager,
        private FinancialService $financialService
    ) {}

    /**
     * Prepara la sesión de pago dinámica según la pasarela activa.
     */
    public function initiate(Request $request)
    {
        $request->validate([
            'unidad_id' => ['required', Rule::exists(Unidad::class, 'id')],
            'amount' => 'required|numeric|min:1000',
        ]);

        $unidad = Unidad::with('copropiedad')->findOrFail($request->unidad_id);
        $copropiedad = $unidad->copropiedad;

        // 1. Obtener la pasarela activa para esta copropiedad
        $gateway = $this->paymentManager->getActiveGateway($copropiedad);
        
        // 2. Obtener configuración de la pasarela desde los settings del conjunto
        $config = $copropiedad->settings['payments']['gateways'][$gateway->getIdentifier()] ?? [];

        if (empty($config) && $gateway->getIdentifier() !== 'wompi') {
            return response()->json(['message' => 'Configuración de pasarela incompleta'], 400);
        }

        $reference = 'NEXO-' . strtoupper(Str::random(10));

        // 3. Crear registro de intento de pago
        $payment = OnlinePayment::create([
            'user_id' => auth()->id(),
            'unidad_id' => $unidad->id,
            'amount' => $request->amount,
            'currency' => 'COP',
            'reference' => $reference,
            'status' => 'PENDING',
            'payment_method' => $gateway->getIdentifier()
        ]);

        // 4. Delegar inicio a la pasarela (Strategy)
        // Nota: Pasamos una transacción simulada o el objeto de pago
        $checkoutData = $gateway->initiatePayment($this->mockTransactionForGateway($payment), $config);

        return response()->json(array_merge($checkoutData, [
            'payment_id' => $payment->id,
            'gateway' => $gateway->getIdentifier()
        ]));
    }

    /**
     * Maneja el webhook de cualquier pasarela.
     */
    public function handleWebhook(Request $request)
    {
        // En una implementación real, podríamos usar un parámetro en la URL 
        // o detectar el origen para saber qué gateway llamar.
        // Por ahora, asumimos Wompi como default o detectamos por headers.
        
        $gatewayIdentifier = $request->header('X-Gateway-ID', 'wompi');
        
        // Buscar el pago por referencia en el body
        $data = $request->input('data');
        $reference = $data['transaction']['reference'] ?? null;
        
        if (!$reference) return response()->json(['message' => 'No reference'], 400);

        $payment = OnlinePayment::with('unidad.copropiedad')->where('reference', $reference)->first();
        if (!$payment) return response()->json(['message' => 'Not found'], 404);

        $gateway = $this->paymentManager->getActiveGateway($payment->unidad->copropiedad);

        // 1. Verify Signature
        $config = $payment->unidad->copropiedad->settings['payments']['gateways'][$gateway->getIdentifier()] ?? [];
        $secret = $config['webhook_secret'] ?? env('WOMPI_WEBHOOK_SECRET', '');

        if (!$gateway->verifySignature($request->all(), $request->headers->all(), $secret)) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // 2. Procesar webhook (Strategy)
        $result = $gateway->handleWebhook($request->all(), $request->headers->all());

        if ($payment->status === 'APPROVED') return response()->json(['message' => 'Done']);

        $payment->update([
            'status' => $result['status'],
            'wompi_id' => $result['gateway_reference'] ?? null,
            'gateway_response' => $request->all(),
        ]);

        if ($result['status'] === 'APPROVED') {
            $this->finalizePayment($payment);
        }

        return response()->json(['message' => 'OK']);
    }

    private function mockTransactionForGateway(OnlinePayment $payment)
    {
        // El gateway espera un objeto Transaccion, creamos uno efímero
        return new \App\Modules\Finance\Models\Transaccion([
            'monto' => $payment->amount,
            'referencia' => $payment->reference,
            'id' => $payment->id
        ]);
    }

    protected function finalizePayment(OnlinePayment $payment)
    {
        $concepto = ConceptoCobro::where('copropiedad_id', $payment->unidad->copropiedad_id)
            ->where('codigo', 'ADM')
            ->first();

        $dto = new TransactionDataDTO(
            unidadId: $payment->unidad_id,
            conceptoId: $concepto?->id ?? ConceptoCobro::where('copropiedad_id', $payment->unidad->copropiedad_id)->first()?->id,
            tipo: 'abono',
            monto: $payment->amount,
            fecha: now()->toDateString(),
            referencia: "Pago Online: " . $payment->reference,
            soportePath: null,
            userId: $payment->user_id
        );

        $this->financialService->registerTransaction($dto);
    }
}
