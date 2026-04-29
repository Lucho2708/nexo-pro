@extends('mail.layouts.premium')

@section('content')
    <div class="badge">Bienvenida Premium</div>
    <h2 style="font-size: 28px; font-weight: 900; color: #101827; margin-top: 0; letter-spacing: -0.03em;">¡Hola, {{ $user->name }}!</h2>
    <p style="font-size: 16px; color: #475569; font-weight: 500;">
        Es un placer darte la bienvenida a <strong>NEXO-PRO</strong>. Has dado el primer paso para transformar la gestión de <strong>{{ $copropiedad->nombre }}</strong> con la tecnología más avanzada del mercado.
    </p>

    <div class="highlight-box">
        <h4 style="margin: 0 0 10px 0; font-size: 12px; font-weight: 900; text-transform: uppercase; color: #101827;">Detalles de tu cuenta</h4>
        <p style="margin: 0; font-size: 14px; color: #64748b; font-weight: 600;">
            Plan Activo: <span style="color: #00d4ff;">{{ strtoupper($copropiedad->plan) }}</span><br>
            Email de acceso: {{ $user->email }}
        </p>
    </div>

    <p style="font-size: 15px; color: #475569;">
        Desde tu nuevo panel de control podrás automatizar recaudos, gestionar PQRS, programar asambleas virtuales y mucho más. Todo diseñado para ofrecerte una experiencia fluida y profesional.
    </p>

    <div style="text-align: center;">
        <a href="{{ route('login') }}" class="button">Ingresar al Panel</a>
    </div>

    <p style="margin-top: 40px; font-size: 13px; color: #94a3b8; font-style: italic; border-top: 1px solid #f1f5f9; pt-20">
        Si tienes alguna duda técnica, nuestro equipo de soporte premium está listo para ayudarte.
    </p>
@endsection
