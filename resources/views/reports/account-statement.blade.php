<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estado de Cuenta - {{ $unidad->torre }} {{ $unidad->nombre }}</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #101827;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }
        .header {
            background-color: #101827;
            color: #ffffff;
            padding: 40px;
            height: 120px;
        }
        .header-table { width: 100%; }
        .brand { font-size: 24px; font-weight: bold; letter-spacing: -1px; }
        .brand span { color: #00D4FF; }
        .report-title { text-align: right; text-transform: uppercase; font-size: 14px; letter-spacing: 2px; opacity: 0.8; }
        
        .content { padding: 40px; }
        
        .summary-grid { width: 100%; margin-bottom: 40px; border-collapse: collapse; }
        .summary-box { 
            background-color: #F9FAFB; 
            padding: 20px; 
            border-radius: 15px;
            border: 1px solid #E5E7EB;
        }
        .summary-label { font-size: 10px; font-weight: bold; color: #6B7280; text-transform: uppercase; margin-bottom: 5px; }
        .summary-value { font-size: 18px; font-weight: bold; color: #101827; }
        .summary-value.danger { color: #EF4444; }
        
        .section-title { 
            font-size: 12px; 
            font-weight: bold; 
            text-transform: uppercase; 
            color: #101827; 
            margin-bottom: 15px;
            border-bottom: 2px solid #00D4FF;
            display: inline-block;
            padding-bottom: 4px;
        }
        
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .data-table th { 
            background-color: #F3F4F6; 
            color: #4B5563; 
            font-size: 10px; 
            text-transform: uppercase; 
            padding: 12px 15px; 
            text-align: left;
            border-bottom: 1px solid #E5E7EB;
        }
        .data-table td { 
            padding: 12px 15px; 
            font-size: 11px; 
            border-bottom: 1px solid #F3F4F6;
            color: #374151;
        }
        
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        .footer { 
            position: absolute; 
            bottom: 40px; 
            left: 40px; 
            right: 40px; 
            border-top: 1px solid #E5E7EB; 
            padding-top: 20px;
            font-size: 9px;
            color: #9CA3AF;
            text-align: center;
        }
        
        .badge {
            padding: 4px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-abono { background-color: #ECFDF5; color: #059669; }
        .badge-cargo { background-color: #FEF2F2; color: #DC2626; }
    </style>
</head>
<body>
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="brand">NEXO<span>-PRO</span></td>
                <td class="report-title">Estado de Cuenta</td>
            </tr>
            <tr>
                <td style="font-size: 12px; opacity: 0.8; padding-top: 5px;">{{ $copropiedad->nombre }}</td>
                <td style="font-size: 10px; opacity: 0.6; text-align: right;">Fecha de corte: {{ $generated_at->format('d/m/Y H:i') }}</td>
            </tr>
        </table>
    </div>

    <div class="content">
        <table class="summary-grid">
            <tr>
                <td style="width: 48%; padding-right: 20px;">
                    <div class="summary-box">
                        <div class="summary-label">Información de la Unidad</div>
                        <div class="summary-value" style="margin-bottom: 5px;">{{ $unidad->torre }} - {{ $unidad->nombre }}</div>
                        <div style="font-size: 11px; color: #6B7280;">Propietario: {{ $unidad->propietario_nombre ?? 'N/A' }}</div>
                        <div style="font-size: 11px; color: #6B7280;">ID: {{ $unidad->propietario_identificacion ?? 'N/A' }}</div>
                    </div>
                </td>
                <td style="width: 48%; padding-left: 20px;">
                    <div class="summary-box" style="border-left: 4px solid {{ $unidad->saldo_actual > 0 ? '#EF4444' : '#10B981' }};">
                        <div class="summary-label">Saldo Actual a Pagar</div>
                        <div class="summary-value {{ $unidad->saldo_actual > 0 ? 'danger' : '' }}">
                            $ {{ number_format($unidad->saldo_actual, 0, ',', '.') }}
                        </div>
                        <div style="font-size: 10px; margin-top: 5px; color: {{ $unidad->saldo_actual > 0 ? '#B91C1C' : '#059669' }}; font-weight: bold; text-transform: uppercase;">
                            {{ $unidad->saldo_actual > 0 ? 'En Mora' : 'Al Día' }}
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="section-title">Detalle de Movimientos (Últimos 20)</div>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Concepto</th>
                    <th>Referencia</th>
                    <th>Tipo</th>
                    <th class="text-right">Valor</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transacciones as $tx)
                <tr>
                    <td>{{ $tx->fecha->format('d/m/Y') }}</td>
                    <td class="font-bold">{{ $tx->concepto->nombre }}</td>
                    <td style="color: #6B7280;">{{ $tx->referencia ?? '---' }}</td>
                    <td>
                        <span class="badge badge-{{ $tx->tipo }}">
                            {{ $tx->tipo }}
                        </span>
                    </td>
                    <td class="text-right font-bold {{ $tx->tipo === 'cargo' ? 'danger' : '' }}">
                        {{ $tx->tipo === 'abono' ? '-' : '' }} $ {{ number_format($tx->monto, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px; color: #9CA3AF;">No se registran movimientos recientes.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 40px; padding: 20px; background-color: #F3F4F6; border-radius: 10px; font-size: 10px; color: #4B5563; line-height: 1.5;">
            <strong>Nota importante:</strong> Este documento es una representación digital de su estado de cuenta en la plataforma NEXO-PRO. Si identifica alguna inconsistencia en sus pagos o cargos, por favor contacte a la administración de <strong>{{ $copropiedad->nombre }}</strong> adjuntando sus soportes de pago.
        </div>
    </div>

    <div class="footer">
        Documento generado automáticamente por NEXO-PRO para la administración de Propiedad Horizontal. <br>
        &copy; {{ date('Y') }} NEXO-PRO PropTech Premium. Todos los derechos reservados.
    </div>
</body>
</html>
