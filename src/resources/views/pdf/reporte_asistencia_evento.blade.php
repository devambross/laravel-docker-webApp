<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Asistencia - {{ $evento->nombre }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 10pt;
            color: #333;
            line-height: 1.4;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #2563eb;
        }

        .header h1 {
            font-size: 20pt;
            color: #1e40af;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 14pt;
            color: #475569;
            font-weight: normal;
            margin-bottom: 3px;
        }

        .header .subtitle {
            font-size: 9pt;
            color: #64748b;
        }

        .evento-info {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px 15px;
            margin-bottom: 15px;
        }

        .evento-info h3 {
            font-size: 11pt;
            color: #1e40af;
            margin-bottom: 8px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 4px;
        }

        .info-grid {
            display: table;
            width: 100%;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            font-weight: bold;
            color: #475569;
            padding: 3px 10px 3px 0;
            width: 120px;
        }

        .info-value {
            display: table-cell;
            color: #1e293b;
            padding: 3px 0;
        }

        .stats-container {
            display: table;
            width: 100%;
            margin-bottom: 15px;
            border-collapse: separate;
            border-spacing: 8px 0;
        }

        .stat-box {
            display: table-cell;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 6px;
            padding: 10px;
            text-align: center;
            border: 1px solid #cbd5e1;
            width: 16.66%;
        }

        .stat-box.total {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
        }

        .stat-box.club {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border: none;
        }

        .stat-box.evento {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
            border: none;
        }

        .stat-label {
            font-size: 8pt;
            opacity: 0.9;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 18pt;
            font-weight: bold;
        }

        .section-title {
            font-size: 12pt;
            color: #1e40af;
            margin: 15px 0 10px 0;
            padding-bottom: 4px;
            border-bottom: 2px solid #2563eb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        thead {
            background-color: #1e40af;
            color: white;
        }

        thead th {
            padding: 8px 6px;
            text-align: left;
            font-size: 9pt;
            font-weight: 600;
            border: 1px solid #1e3a8a;
        }

        tbody td {
            padding: 6px 6px;
            font-size: 9pt;
            border: 1px solid #e2e8f0;
        }

        tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        tbody tr:hover {
            background-color: #f1f5f9;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 8pt;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-socio {
            background-color: #1e293b;
            color: white;
        }

        .badge-familiar {
            background-color: #3b82f6;
            color: white;
        }

        .badge-invitado {
            background-color: #6b7280;
            color: white;
        }

        .check-icon {
            color: #10b981;
            font-weight: bold;
            font-size: 11pt;
        }

        .x-icon {
            color: #ef4444;
            font-weight: bold;
            font-size: 11pt;
        }

        .fecha-hora {
            font-size: 8pt;
            color: #64748b;
            display: block;
            margin-top: 2px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px;
            background-color: #f8fafc;
            border-top: 1px solid #cbd5e1;
            padding: 8px 15px;
            font-size: 8pt;
            color: #64748b;
            text-align: center;
        }

        .note-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 10px 12px;
            margin: 15px 0;
            font-size: 9pt;
            color: #92400e;
        }

        .note-box strong {
            color: #78350f;
        }

        @page {
            margin: 15mm;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>🏛️ Club Social</h1>
        <h2>Reporte de Asistencia del Evento</h2>
        <div class="subtitle">{{ $evento->nombre }}</div>
    </div>

    <!-- Información del Evento -->
    <div class="evento-info">
        <h3>📋 Información del Evento</h3>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Evento:</div>
                <div class="info-value">{{ $evento->nombre }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Fecha Inicio:</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y') }}</div>
            </div>
            @if($evento->fecha_fin)
            <div class="info-row">
                <div class="info-label">Fecha Fin:</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($evento->fecha_fin)->format('d/m/Y') }}</div>
            </div>
            @endif
            <div class="info-row">
                <div class="info-label">Área:</div>
                <div class="info-value">{{ $evento->area }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Capacidad:</div>
                <div class="info-value">{{ $evento->capacidad }} personas</div>
            </div>
            <div class="info-row">
                <div class="info-label">Generado:</div>
                <div class="info-value">{{ $fechaGeneracion }}</div>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="stats-container">
        <div class="stat-box total">
            <div class="stat-label">Total</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
        </div>
        <div class="stat-box club">
            <div class="stat-label">Entrada Club</div>
            <div class="stat-value">{{ $stats['entrada_club'] }}</div>
        </div>
        <div class="stat-box evento">
            <div class="stat-label">Entrada Evento</div>
            <div class="stat-value">{{ $stats['entrada_evento'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Socios</div>
            <div class="stat-value">{{ $stats['socios'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Familiares</div>
            <div class="stat-value">{{ $stats['familiares'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Invitados</div>
            <div class="stat-value">{{ $stats['invitados'] }}</div>
        </div>
    </div>

    <!-- Nota informativa -->
    <div class="note-box">
        <strong>📌 Nota:</strong> Este reporte muestra el estado de asistencia actualizado al momento de su generación ({{ $fechaGeneracion }}). Las marcas de entrada club (✓) pueden provenir tanto del registro directo en "Entrada Club" como del registro desde "Entrada Evento".
    </div>

    <!-- Tabla de Asistencias -->
    <h3 class="section-title">Detalle de Asistencias</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 10%;">Código</th>
                <th style="width: 8%;">Tipo</th>
                <th style="width: 25%;">Nombre Completo</th>
                <th style="width: 12%;">DNI</th>
                <th style="width: 10%;">Mesa</th>
                <th style="width: 8%;">Silla</th>
                <th style="width: 13%;">Entrada Club</th>
                <th style="width: 14%;">Entrada Evento</th>
            </tr>
        </thead>
        <tbody>
            @forelse($participantes as $participante)
            <tr>
                <td>{{ $participante['codigo'] }}</td>
                <td>
                    <span class="badge badge-{{ $participante['tipo'] }}">
                        {{ $participante['tipo'] }}
                    </span>
                </td>
                <td>{{ $participante['nombre'] }}</td>
                <td>{{ $participante['dni'] ?? 'N/A' }}</td>
                <td style="text-align: center;">{{ $participante['mesa'] }}</td>
                <td style="text-align: center;">{{ $participante['silla'] }}</td>
                <td style="text-align: center;">
                    @if($participante['entrada_club'])
                        <span class="check-icon">✓</span>
                        @if($participante['fecha_hora_club'])
                            <span class="fecha-hora">
                                {{ \Carbon\Carbon::parse($participante['fecha_hora_club'])->format('d/m/Y H:i') }}
                            </span>
                        @endif
                    @else
                        <span class="x-icon">✗</span>
                    @endif
                </td>
                <td style="text-align: center;">
                    @if($participante['entrada_evento'])
                        <span class="check-icon">✓</span>
                        @if($participante['fecha_hora_evento'])
                            <span class="fecha-hora">
                                {{ \Carbon\Carbon::parse($participante['fecha_hora_evento'])->format('d/m/Y H:i') }}
                            </span>
                        @endif
                    @else
                        <span class="x-icon">✗</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 20px; color: #64748b;">
                    No hay participantes registrados en este evento.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        Sistema de Gestión de Eventos - Generado el {{ $fechaGeneracion }}
    </div>
</body>
</html>
