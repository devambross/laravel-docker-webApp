<?php

namespace App\Exports;

use App\Models\Evento;
use App\Models\ParticipanteEvento;

class EventoExport
{
    protected $eventoId;

    public function __construct($eventoId)
    {
        $this->eventoId = $eventoId;
    }

    public function export()
    {
        $evento = Evento::with(['participantes.mesa', 'participantes.entradaEvento'])->findOrFail($this->eventoId);

        $filename = 'evento_' . $evento->id . '_' . date('Y-m-d_His') . '.xls';

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($evento) {
            echo '<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr style="background-color: #78B548; color: white; font-weight: bold;">
            <td colspan="10" style="text-align: center; font-size: 14pt; padding: 10px;">REPORTE DEL EVENTO</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Nombre:</td>
            <td colspan="9">' . htmlspecialchars($evento->nombre) . '</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Fecha:</td>
            <td colspan="9">' . htmlspecialchars($evento->fecha) . '</td>
        </tr>';

        if ($evento->fecha_fin) {
            echo '<tr>
                <td style="font-weight: bold;">Fecha Fin:</td>
                <td colspan="9">' . htmlspecialchars($evento->fecha_fin) . '</td>
            </tr>';
        }

        echo '<tr>
            <td style="font-weight: bold;">Capacidad Total:</td>
            <td colspan="9">' . $evento->capacidad_total . '</td>
        </tr>
        <tr><td colspan="10">&nbsp;</td></tr>
        <tr style="background-color: #4CAF50; color: white; font-weight: bold; text-align: center;">
            <td>Código Participante</td>
            <td>Tipo</td>
            <td>Nombre</td>
            <td>DNI</td>
            <td>Mesa</td>
            <td>Silla</td>
            <td>N° Recibo</td>
            <td>N° Boleta</td>
            <td>Entrada Club</td>
            <td>Entrada Evento</td>
        </tr>';

        foreach ($evento->participantes as $index => $participante) {
            $bgColor = $index % 2 === 0 ? '#f9f9f9' : '#ffffff';
            echo '<tr style="background-color: ' . $bgColor . ';">
                <td>' . htmlspecialchars($participante->codigo_participante) . '</td>
                <td>' . htmlspecialchars(ucfirst($participante->tipo)) . '</td>
                <td>' . htmlspecialchars($participante->nombre) . '</td>
                <td>' . htmlspecialchars($participante->dni) . '</td>
                <td>' . ($participante->mesa ? htmlspecialchars($participante->mesa->numero_mesa) : 'Sin mesa') . '</td>
                <td>' . ($participante->numero_silla ?? '-') . '</td>
                <td>' . htmlspecialchars($participante->n_recibo ?? '-') . '</td>
                <td>' . htmlspecialchars($participante->n_boleta ?? '-') . '</td>
                <td style="text-align: center;">' . ($participante->entradaEvento->entrada_club ? 'Sí' : 'No') . '</td>
                <td style="text-align: center;">' . ($participante->entradaEvento->entrada_evento ? 'Sí' : 'No') . '</td>
            </tr>';
        }

        echo '</table>
</body>
</html>';
        };

        return response()->stream($callback, 200, $headers);
    }
}
