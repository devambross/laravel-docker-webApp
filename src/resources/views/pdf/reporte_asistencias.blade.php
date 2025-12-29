<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Asistencias - {{ $fecha_formatted }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            color: #333;
            line-height: 1.4;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #78B548;
        }

        .logo-section {
            margin-bottom: 15px;
        }

        .club-name {
            font-size: 24pt;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .report-title {
            font-size: 18pt;
            color: #78B548;
            font-weight: bold;
            margin-top: 10px;
        }

        .report-subtitle {
            font-size: 12pt;
            color: #666;
            margin-top: 5px;
        }

        .metadata {
            display: table;
            width: 100%;
            margin-bottom: 25px;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }

        .metadata-row {
            display: table-row;
        }

        .metadata-label {
            display: table-cell;
            font-weight: bold;
            color: #555;
            padding: 5px 10px;
            width: 30%;
        }

        .metadata-value {
            display: table-cell;
            padding: 5px 10px;
            color: #333;
        }

        .stats-section {
            margin-bottom: 25px;
        }

        .stats-title {
            font-size: 14pt;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e0e0e0;
        }

        .stats-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }

        .stats-row {
            display: table-row;
        }

        .stat-box {
            display: table-cell;
            background: #fff;
            border: 2px solid #e0e0e0;
            padding: 15px;
            text-align: center;
            width: 25%;
        }

        .stat-box.total {
            border-color: #78B548;
            background: #f8fdf4;
        }

        .stat-box.socios {
            border-color: #2c3e50;
            background: #f8f9fa;
        }

        .stat-box.familiares {
            border-color: #3498db;
            background: #f0f8ff;
        }

        .stat-box.invitados {
            border-color: #95a5a6;
            background: #f5f5f5;
        }

        .stat-label {
            font-size: 9pt;
            color: #666;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 28pt;
            font-weight: bold;
            color: #333;
        }

        .stat-box.total .stat-value {
            color: #78B548;
        }

        .stat-box.socios .stat-value {
            color: #2c3e50;
        }

        .stat-box.familiares .stat-value {
            color: #3498db;
        }

        .stat-box.invitados .stat-value {
            color: #95a5a6;
        }

        .table-section {
            margin-top: 25px;
        }

        .table-title {
            font-size: 14pt;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e0e0e0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        thead {
            background: #2c3e50;
            color: white;
        }

        th {
            padding: 12px 8px;
            text-align: left;
            font-size: 10pt;
            font-weight: bold;
            border: 1px solid #1a252f;
        }

        td {
            padding: 10px 8px;
            border: 1px solid #e0e0e0;
            font-size: 9pt;
        }

        tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        tbody tr:hover {
            background: #f0f0f0;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 8pt;
            font-weight: bold;
            display: inline-block;
        }

        .badge.socio {
            background: #2c3e50;
            color: white;
        }

        .badge.familiar {
            background: #3498db;
            color: white;
        }

        .badge.invitado {
            background: #ecf0f1;
            color: #555;
        }

        .evento-info {
            font-weight: bold;
            color: #333;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
            text-align: center;
            font-size: 9pt;
            color: #999;
        }

        .page-break {
            page-break-after: always;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
            font-style: italic;
        }

        .summary-text {
            margin-top: 15px;
            padding: 15px;
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            font-size: 10pt;
            color: #856404;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="logo-section">
            <div class="club-name">CLUB SOCIAL</div>
        </div>
        <div class="report-title">Reporte de Asistencias Diarias</div>
        <div class="report-subtitle">Control de Entrada del Club</div>
    </div>

    <!-- Metadata -->
    <div class="metadata">
        <div class="metadata-row">
            <div class="metadata-label">Fecha del Reporte:</div>
            <div class="metadata-value">{{ $fecha_formatted }}</div>
        </div>
        <div class="metadata-row">
            <div class="metadata-label">Generado el:</div>
            <div class="metadata-value">{{ $fecha_generacion }}</div>
        </div>
        <div class="metadata-row">
            <div class="metadata-label">Hora de Generación:</div>
            <div class="metadata-value">{{ $hora_generacion }}</div>
        </div>
        <div class="metadata-row">
            <div class="metadata-label">Total de Registros:</div>
            <div class="metadata-value">{{ $estadisticas['total'] }} asistencias</div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats-section">
        <div class="stats-title">Resumen Estadístico</div>
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stat-box total">
                    <div class="stat-label">Total</div>
                    <div class="stat-value">{{ $estadisticas['total'] }}</div>
                </div>
                <div class="stat-box socios">
                    <div class="stat-label">Socios</div>
                    <div class="stat-value">{{ $estadisticas['socios'] }}</div>
                </div>
                <div class="stat-box familiares">
                    <div class="stat-label">Familiares</div>
                    <div class="stat-value">{{ $estadisticas['familiares'] }}</div>
                </div>
                <div class="stat-box invitados">
                    <div class="stat-label">Invitados</div>
                    <div class="stat-value">{{ $estadisticas['invitados'] }}</div>
                </div>
            </div>
        </div>

        @if($es_hoy)
        <div class="summary-text">
            <strong>Nota:</strong> Este reporte corresponde al día de hoy y se actualiza en tiempo real.
            Los datos mostrados son válidos hasta las {{ $hora_generacion }}.
        </div>
        @endif
    </div>

    <!-- Detailed Table -->
    <div class="table-section">
        <div class="table-title">Detalle de Asistencias</div>

        @if(count($asistencias) > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 8%;">#</th>
                        <th style="width: 12%;">Código</th>
                        <th style="width: 10%;">Tipo</th>
                        <th style="width: 12%;">DNI</th>
                        <th style="width: 28%;">Nombre Completo</th>
                        <th style="width: 20%;">Evento</th>
                        <th style="width: 10%;">Hora</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($asistencias as $index => $asistencia)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td>{{ $asistencia->codigo_socio }}</td>
                            <td>
                                <span class="badge {{ $asistencia->tipo }}">
                                    {{ ucfirst($asistencia->tipo) }}
                                </span>
                            </td>
                            <td>{{ $asistencia->dni ?? 'N/A' }}</td>
                            <td>{{ $asistencia->nombre }}</td>
                            <td>
                                @if($asistencia->evento_nombre)
                                    <span class="evento-info">{{ $asistencia->evento_nombre }}</span>
                                @else
                                    <span style="color: #999;">Entrada general</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($asistencia->fecha_hora_entrada)->format('H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">
                No se registraron asistencias en esta fecha.
            </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Documento generado automáticamente por el Sistema de Gestión del Club</p>
        <p>{{ $fecha_generacion }} - {{ $hora_generacion }}</p>
    </div>
</body>
</html>
