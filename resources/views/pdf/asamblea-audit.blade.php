<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Auditoría - {{ $asamblea->titulo }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.5; font-size: 12px; }
        .header { text-align: center; border-bottom: 2px solid #00173c; padding-bottom: 20px; margin-bottom: 30px; }
        .property-name { font-size: 20px; font-weight: bold; color: #00173c; margin-bottom: 5px; }
        .report-title { font-size: 16px; color: #666; text-transform: uppercase; letter-spacing: 2px; }
        
        .section-title { background: #f4f4f4; padding: 8px 15px; font-weight: bold; border-left: 4px solid #00173c; margin: 25px 0 15px 0; text-transform: uppercase; font-size: 11px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background: #00173c; color: white; text-align: left; padding: 10px; font-size: 10px; text-transform: uppercase; }
        td { padding: 8px 10px; border-bottom: 1px solid #eee; }
        
        .result-card { border: 1px solid #ddd; border-radius: 8px; padding: 15px; margin-bottom: 20px; }
        .question-title { font-weight: bold; margin-bottom: 10px; font-size: 13px; }
        
        .bar-container { background: #eee; height: 10px; border-radius: 5px; margin-top: 5px; overflow: hidden; }
        .bar { background: #00173c; height: 100%; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: bold; }
        .badge-success { background: #e6fcf5; color: #0ca678; }
    </style>
</head>
<body>
    <div class="header">
        <div class="property-name">{{ $copropiedad->nombre }}</div>
        <div class="report-title">Acta de Auditoría Digital - Asamblea Virtual</div>
        <div style="margin-top: 10px;">ID de Sesión: #AS-{{ $asamblea->id }} | Fecha: {{ $asamblea->fecha->format('d/m/Y') }}</div>
    </div>

    <div class="section-title">Resumen de la Sesión</div>
    <table>
        <tr>
            <td width="30%"><strong>Título de Asamblea:</strong></td>
            <td>{{ $asamblea->titulo }}</td>
        </tr>
        <tr>
            <td><strong>Estado Final:</strong></td>
            <td><span class="badge badge-success">FINALIZADA Y AUDITADA</span></td>
        </tr>
        <tr>
            <td><strong>Generado por:</strong></td>
            <td>NEXO-PRO - Motor de Asamblea Virtual</td>
        </tr>
        <tr>
            <td><strong>Fecha de Reporte:</strong></td>
            <td>{{ $generated_at->format('d/m/Y H:i:s') }}</td>
        </tr>
    </table>

    <div class="section-title">Registro de Asistencia (Quórum)</div>
    <table>
        <thead>
            <tr>
                <th>Unidad</th>
                <th>Usuario (ID)</th>
                <th>IP de Conexión</th>
                <th>Fecha/Hora Ingreso</th>
            </tr>
        </thead>
        <tbody>
            @foreach($participants as $participant)
                <tr>
                    <td>Unidad ID: {{ $participant->unidad_id }}</td>
                    <td>{{ $participant->user_id }}</td>
                    <td>{{ $participant->ip_address }}</td>
                    <td>{{ \Carbon\Carbon::parse($participant->created_at)->format('H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Resultados de Votaciones</div>
    @foreach($results as $result)
        <div class="result-card">
            <div class="question-title">{{ $result['titulo'] }}</div>
            <div style="font-size: 10px; color: #666; margin-bottom: 10px;">
                Total Unidades: {{ $result['total_votos'] }} | Peso Total: {{ number_format($result['total_peso'], 4) }}
            </div>
            
            @foreach($result['opciones'] as $opcion)
                <div style="margin-bottom: 12px;">
                    <div style="display: flex; justify-content: space-between;">
                        <span>{{ $opcion['titulo'] }} ({{ $opcion['cantidad'] }} votos)</span>
                        <span style="float: right; font-weight: bold;">{{ number_format($opcion['porcentaje'], 2) }}%</span>
                    </div>
                    <div class="bar-container">
                        <div class="bar" style="width: {{ $opcion['porcentaje'] }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach

    <div class="footer">
        Este documento es una evidencia digital generada automáticamente por NEXO-PRO. 
        Cumple con los requisitos legales de auditoría para asambleas virtuales según la normativa vigente.
        Página 1 de 1
    </div>
</body>
</html>
