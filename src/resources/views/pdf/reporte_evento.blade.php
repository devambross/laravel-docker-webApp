<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe - {{ $evento->nombre }}</title>
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
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #78B548;
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

        .info-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .info-section h2 {
            color: #78B548;
            font-size: 14pt;
            margin: 0 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #e0e0e0;
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
            color: #555;
            padding: 5px 10px 5px 0;
            width: 35%;
        }

        .info-value {
            display: table-cell;
            padding: 5px 0;
            color: #333;
        }

        .summary {
            display: table;
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }

        .summary-row {
            display: table-row;
        }

        .summary-box {
            display: table-cell;
            text-align: center;
            padding: 15px;
            background: #e8f5e9;
            border: 2px solid #78B548;
            width: 33.33%;
        }

        .summary-number {
            font-size: 28pt;
            font-weight: bold;
            color: #78B548;
            display: block;
        }

        .summary-label {
            color: #555;
            font-size: 10pt;
            text-transform: uppercase;
            font-weight: bold;
            margin-top: 5px;
        }

        .section-title {
            font-size: 14pt;
            font-weight: bold;
            color: #2c3e50;
            margin: 25px 0 15px 0;
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
            padding: 10px 6px;
            text-align: left;
            font-size: 9pt;
            font-weight: bold;
            border: 1px solid #1a252f;
        }

        td {
            padding: 8px 6px;
            border: 1px solid #e0e0e0;
            font-size: 9pt;
        }

        tbody tr:nth-child(even) {
            background: #f8f9fa;
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

        .table-container {
            margin-bottom: 30px;
        }

        .status-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }

        .status-presente {
            background: #27ae60;
        }

        .status-ausente {
            background: #e74c3c;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="club-name">CLUB SOCIAL</div>
        <div class="report-title">Informe de Evento</div>
        <div class="report-subtitle">{{ $evento->nombre }}</div>
    </div>

    <!-- Información del Evento -->
    <div class="info-section">
        <h2>Información General</h2>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nombre del Evento:</div>
                <div class="info-value">{{ $evento->nombre }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Fecha de Inicio:</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y') }}</div>
            </div>
            @if($evento->fecha_fin)
            <div class="info-row">
                <div class="info-label">Fecha de Fin:</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($evento->fecha_fin)->format('d/m/Y') }}</div>
            </div>
            @endif
            <div class="info-row">
                <div class="info-label">Área:</div>
                <div class="info-value">{{ $evento->area }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Capacidad Total:</div>
                <div class="info-value">{{ $evento->capacidad_total }} asientos</div>
            </div>
            <div class="info-row">
                <div class="info-label">Fecha de Generación:</div>
                <div class="info-value">{{ $fecha_generacion }} - {{ $hora_generacion }}</div>
            </div>
        </div>
    </div>

    <!-- Resumen Estadístico -->
    <div class="summary">
        <div class="summary-row">
            <div class="summary-box">
                <span class="summary-number">{{ $evento->mesas->count() }}</span>
                <span class="summary-label">Mesas</span>
            </div>
            <div class="summary-box">
                <span class="summary-number">{{ $evento->participantes->count() }}</span>
                <span class="summary-label">Participantes</span>
            </div>
            <div class="summary-box">
                <span class="summary-number">{{ $evento->asientos_disponibles }}</span>
                <span class="summary-label">Disponibles</span>
            </div>
        </div>
    </div>

    <!-- Lista de Participantes -->
    <div class="table-container">
        <div class="section-title">Lista de Participantes</div>

        @if($evento->participantes->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 8%;">#</th>
                        <th style="width: 12%;">Código</th>
                        <th style="width: 25%;">Nombre</th>
                        <th style="width: 10%;">DNI</th>
                        <th style="width: 10%;">Tipo</th>
                        <th style="width: 15%;">Mesa - Silla</th>
                        <th style="width: 10%;">N° Recibo</th>
                        <th style="width: 10%;">N° Boleta</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($evento->participantes as $index => $participante)
                        @php
                            $codigo = $participante->codigo_participante;
                            $tipoReal = 'socio';

                            if (strpos($codigo, '-INV') !== false) {
                                $tipoReal = 'invitado';
                            } elseif (preg_match('/^\d+-[A-Z]$/', $codigo)) {
                                $tipoReal = 'familiar';
                            }

                            $mesaSilla = $participante->mesa ?
                                "Mesa {$participante->mesa->numero_mesa} - Silla {$participante->numero_silla}" :
                                'Sin asignar';
                        @endphp
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td>{{ $participante->codigo_participante }}</td>
                            <td>{{ $participante->nombre }}</td>
                            <td>{{ $participante->dni }}</td>
                            <td>
                                <span class="badge {{ $tipoReal }}">
                                    {{ ucfirst($tipoReal) }}
                                </span>
                            </td>
                            <td>{{ $mesaSilla }}</td>
                            <td>{{ $participante->n_recibo ?? '-' }}</td>
                            <td>{{ $participante->n_boleta ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">
                No hay participantes registrados para este evento.
            </div>
        @endif
    </div>

    <!-- Distribución de Mesas -->
    <div class="table-container">
        <div class="section-title">Distribución de Mesas</div>

        @if($evento->mesas->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 20%;">Mesa</th>
                        <th style="width: 20%;">Capacidad</th>
                        <th style="width: 20%;">Ocupadas</th>
                        <th style="width: 20%;">Disponibles</th>
                        <th style="width: 20%;">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($evento->mesas as $mesa)
                        <tr>
                            <td>Mesa {{ $mesa->numero_mesa }}</td>
                            <td style="text-align: center;">{{ $mesa->capacidad }}</td>
                            <td style="text-align: center;">{{ $mesa->ocupadas }}</td>
                            <td style="text-align: center;">{{ $mesa->disponibles }}</td>
                            <td>
                                @if($mesa->disponibles == 0)
                                    <span style="color: #e74c3c; font-weight: bold;">Completa</span>
                                @else
                                    <span style="color: #27ae60; font-weight: bold;">Disponible</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">
                No hay mesas configuradas para este evento.
            </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Sistema de Gestión de Eventos - Club Social</p>
        <p>Documento generado automáticamente el {{ $fecha_generacion }} a las {{ $hora_generacion }}</p>
    </div>
</body>
</html>
