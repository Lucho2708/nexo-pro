<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Radicado PQRS #{{ $pqrs->id }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.6; margin: 40px; }
        .header { border-bottom: 2px solid #00173c; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { font-size: 24px; font-weight: bold; color: #00173c; letter-spacing: -1px; }
        .property-name { font-size: 14px; font-weight: bold; color: #666; margin-top: 5px; }
        .title { font-size: 20px; font-weight: bold; text-align: center; margin-bottom: 30px; text-transform: uppercase; }
        .info-section { margin-bottom: 20px; background: #f9fafb; padding: 15px; border-radius: 8px; }
        .label { font-size: 10px; font-weight: bold; color: #6b7280; text-transform: uppercase; margin-bottom: 4px; }
        .value { font-size: 13px; font-weight: bold; color: #111827; }
        .message-section { margin-top: 30px; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 10px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 10px; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 4px; font-size: 10px; font-weight: bold; text-transform: uppercase; }
        .priority-alta { background: #fee2e2; color: #dc2626; }
        .status-abierto { background: #dbeafe; color: #2563eb; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">NEXO-PRO <span style="font-size: 12px; color: #3b82f6;">PLATFORM</span></div>
        <div class="property-name">{{ $pqrs->unidad->copropiedad->nombre }}</div>
        <div style="font-size: 10px; color: #999;">NIT: {{ $pqrs->unidad->copropiedad->nit }}</div>
    </div>

    <div class="title">Certificado de Radicación PQRS #{{ $pqrs->id }}</div>

    <table width="100%">
        <tr>
            <td width="50%">
                <div class="info-section">
                    <div class="label">Fecha de Radicación</div>
                    <div class="value">{{ $pqrs->created_at->format('d/m/Y H:i') }}</div>
                </div>
            </td>
            <td width="50%">
                <div class="info-section">
                    <div class="label">Tipo de Solicitud</div>
                    <div class="value">{{ ucfirst(str_replace('_', ' ', $pqrs->tipo)) }}</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="info-section">
        <table width="100%">
            <tr>
                <td>
                    <div class="label">Radicado por</div>
                    <div class="value">{{ $pqrs->user->name }}</div>
                </td>
                <td>
                    <div class="label">Unidad</div>
                    <div class="value">Torre {{ $pqrs->unidad->torre }} - {{ $pqrs->unidad->nombre }}</div>
                </td>
                <td>
                    <div class="label">Prioridad</div>
                    <div class="value"><span class="badge priority-{{ $pqrs->prioridad }}">{{ $pqrs->prioridad }}</span></div>
                </td>
            </tr>
        </table>
    </div>

    <div class="message-section">
        <div class="label">Asunto</div>
        <div class="value" style="font-size: 16px; margin-bottom: 15px;">{{ $pqrs->asunto }}</div>
        
        <div class="label">Contenido de la Solicitud</div>
        <div style="font-size: 12px; color: #4b5563; text-align: justify;">{{ $pqrs->mensaje }}</div>
    </div>

    @if($pqrs->respuesta)
    <div class="message-section" style="border-top: 1px dashed #ccc; padding-top: 20px; margin-top: 40px;">
        <div class="label">Respuesta de la Administración ({{ $pqrs->fecha_respuesta->format('d/m/Y') }})</div>
        <div style="font-size: 12px; color: #1e3a8a; font-style: italic;">"{{ $pqrs->respuesta }}"</div>
    </div>
    @endif

    <div class="footer">
        Documento generado automáticamente por Nexo-Pro para {{ $pqrs->unidad->copropiedad->nombre }}. <br>
        La veracidad de este radicado puede ser validada en el portal administrativo.
    </div>
</body>
</html>
