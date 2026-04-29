@extends('mail.layouts.premium')

@section('content')
    <div class="badge" style="background-color: #ecfdf5; color: #059669;">Pago Confirmado</div>
    <h2 style="font-size: 28px; font-weight: 900; color: #101827; margin-top: 0; letter-spacing: -0.03em;">¡Pago Recibido!</h2>
    <p style="font-size: 16px; color: #475569; font-weight: 500;">
        Hola {{ $user->name }}, te confirmamos que hemos recibido satisfactoriamente el pago realizado para la unidad <strong>{{ $unidad->nombre }}</strong>.
    </p>

    <div class="highlight-box" style="background-color: #f0f9ff; border-color: #bae6fd;">
        <h4 style="margin: 0 0 10px 0; font-size: 12px; font-weight: 900; text-transform: uppercase; color: #0369a1;">Comprobante de Transacción</h4>
        <p style="margin: 0; font-size: 14px; color: #0c4a6e; font-weight: 600;">
            Monto: <span style="font-size: 18px;">${{ number_format($transaccion->monto, 0, ',', '.') }}</span><br>
            Referencia: {{ $transaccion->referencia }}<br>
            Fecha: {{ $transaccion->created_at->format('d/m/Y H:i') }}
        </p>
    </div>

    <p style="font-size: 15px; color: #475569;">
        Tu saldo ha sido actualizado en el portal. Gracias por tu puntualidad, esto nos ayuda a mantener nuestra comunidad en las mejores condiciones.
    </p>

    <div style="text-align: center;">
        <a href="{{ route('owner.dashboard') }}" class="button">Ver mi Estado de Cuenta</a>
    </div>

    <p style="margin-top: 40px; font-size: 12px; color: #94a3b8; text-align: center;">
        Este es un mensaje automático, por favor no respondas a este correo.
    </p>
@endsection
