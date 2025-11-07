<?php

namespace App\Services;

class EventoService
{
    public function getEventos()
    {
        // Simulación de datos de eventos
        return [
            [
                'id' => 1,
                'nombre' => 'Evento 1',
                'fecha' => '2025-11-15',
                'descripcion' => 'Descripción del evento 1'
            ],
            [
                'id' => 2,
                'nombre' => 'Evento 2',
                'fecha' => '2025-11-20',
                'descripcion' => 'Descripción del evento 2'
            ],
            [
                'id' => 3,
                'nombre' => 'Evento 3',
                'fecha' => '2025-11-25',
                'descripcion' => 'Descripción del evento 3'
            ],
            [
                'id' => 4,
                'nombre' => 'Evento 4',
                'fecha' => '2025-12-01',
                'descripcion' => 'Descripción del evento 4'
            ],
        ];
    }
    public function getSocio($dni)
    {
        // Simulación de datos de socios y sus relacionados (por DNI)
        $personas = [
            '12345678' => [
                'dni' => '12345678',
                'nombres' => 'Juan',
                'apellidos' => 'Pérez González',
                'rol' => 'Socio Titular',
                'estado' => 'Activo',
                'codigo' => '12345',
                'foto' => 'https://e7.pngegg.com/pngimages/513/311/png-clipart-silhouette-male-silhouette-animals-head-thumbnail.png'
            ],
            '87654321' => [
                'dni' => '87654321',
                'nombres' => 'María',
                'apellidos' => 'Pérez López',
                'rol' => 'Familiar',
                'estado' => 'Activo',
                'codigo' => '12345',
                'foto' => 'https://e7.pngegg.com/pngimages/513/311/png-clipart-silhouette-male-silhouette-animals-head-thumbnail.png'
            ],
            '11223344' => [
                'dni' => '11223344',
                'nombres' => 'Pedro',
                'apellidos' => 'Pérez López',
                'rol' => 'Familiar',
                'estado' => 'Activo',
                'codigo' => '12345',
                'foto' => 'https://e7.pngegg.com/pngimages/513/311/png-clipart-silhouette-male-silhouette-animals-head-thumbnail.png'
            ],
            '99887766' => [
                'dni' => '99887766',
                'nombres' => 'Ana',
                'apellidos' => 'Silva Ruiz',
                'rol' => 'Invitado',
                'estado' => 'Activo',
                'codigo' => '54321',
                'foto' => 'https://e7.pngegg.com/pngimages/513/311/png-clipart-silhouette-male-silhouette-animals-head-thumbnail.png'
            ],
            // Event 2 personas
            '22334455' => [
                'dni' => '22334455',
                'nombres' => 'Lucía',
                'apellidos' => 'Martínez',
                'rol' => 'Socio Titular',
                'estado' => 'Activo',
                'codigo' => '22222',
                'foto' => 'https://e7.pngegg.com/pngimages/513/311/png-clipart-silhouette-male-silhouette-animals-head-thumbnail.png'
            ],
            '33445566' => [
                'dni' => '33445566',
                'nombres' => 'Carlos',
                'apellidos' => 'Gómez',
                'rol' => 'Invitado',
                'estado' => 'Activo',
                'codigo' => '22222',
                'foto' => 'https://e7.pngegg.com/pngimages/513/311/png-clipart-silhouette-male-silhouette-animals-head-thumbnail.png'
            ],
            // Event 3 personas
            '44556677' => [
                'dni' => '44556677',
                'nombres' => 'Sofía',
                'apellidos' => 'Ramos',
                'rol' => 'Socio Titular',
                'estado' => 'Activo',
                'codigo' => '33333',
                'foto' => 'https://e7.pngegg.com/pngimages/513/311/png-clipart-silhouette-male-silhouette-animals-head-thumbnail.png'
            ],
            '55667788' => [
                'dni' => '55667788',
                'nombres' => 'Diego',
                'apellidos' => 'Torres',
                'rol' => 'Familiar',
                'estado' => 'Activo',
                'codigo' => '33333',
                'foto' => 'https://e7.pngegg.com/pngimages/513/311/png-clipart-silhouette-male-silhouette-animals-head-thumbnail.png'
            ],
            '66778899' => [
                'dni' => '66778899',
                'nombres' => 'Marcos',
                'apellidos' => 'Herrera',
                'rol' => 'Invitado',
                'estado' => 'Activo',
                'codigo' => '54321',
                'foto' => 'https://e7.pngegg.com/pngimages/513/311/png-clipart-silhouette-male-silhouette-animals-head-thumbnail.png'
            ],
        ];

        return $personas[$dni] ?? null;
    }

    public function getParticipantes($eventoId, $codigoSocio = null)
    {
        // Simulación de lista de participantes por evento (más extensa)
        $participantes = [
            1 => [
                [
                    'dni' => '12345678',
                    'nombre' => 'Juan Pérez González',
                    'mesa' => 'A1',
                    'asiento' => '01',
                    'checked1' => true,
                    'checked2' => false,
                    'codigo_socio' => '12345'
                ],
                [
                    'dni' => '87654321',
                    'nombre' => 'María Pérez López',
                    'mesa' => 'A1',
                    'asiento' => '02',
                    'checked1' => false,
                    'checked2' => true,
                    'codigo_socio' => '12345'
                ],
                [
                    'dni' => '11223344',
                    'nombre' => 'Pedro Pérez López',
                    'mesa' => 'A1',
                    'asiento' => '03',
                    'checked1' => true,
                    'checked2' => true,
                    'codigo_socio' => '12345'
                ],
                [
                    'dni' => '99887766',
                    'nombre' => 'Ana Silva Ruiz',
                    'mesa' => 'B1',
                    'asiento' => '01',
                    'checked1' => true,
                    'checked2' => false,
                    'codigo_socio' => '54321'
                ],
                [
                    'dni' => '66778899',
                    'nombre' => 'Marcos Herrera',
                    'mesa' => 'B1',
                    'asiento' => '02',
                    'checked1' => false,
                    'checked2' => false,
                    'codigo_socio' => '54321'
                ],
            ],
            2 => [
                [
                    'dni' => '22334455',
                    'nombre' => 'Lucía Martínez',
                    'mesa' => 'C1',
                    'asiento' => '01',
                    'checked1' => true,
                    'checked2' => false,
                    'codigo_socio' => '22222'
                ],
                [
                    'dni' => '33445566',
                    'nombre' => 'Carlos Gómez',
                    'mesa' => 'C1',
                    'asiento' => '02',
                    'checked1' => false,
                    'checked2' => false,
                    'codigo_socio' => '22222'
                ],
                [
                    'dni' => '12345678',
                    'nombre' => 'Juan Pérez González',
                    'mesa' => 'C2',
                    'asiento' => '03',
                    'checked1' => true,
                    'checked2' => true,
                    'codigo_socio' => '12345'
                ],
            ],
            3 => [
                [
                    'dni' => '44556677',
                    'nombre' => 'Sofía Ramos',
                    'mesa' => 'D1',
                    'asiento' => '01',
                    'checked1' => true,
                    'checked2' => false,
                    'codigo_socio' => '33333'
                ],
                [
                    'dni' => '55667788',
                    'nombre' => 'Diego Torres',
                    'mesa' => 'D1',
                    'asiento' => '02',
                    'checked1' => false,
                    'checked2' => true,
                    'codigo_socio' => '33333'
                ],
                [
                    'dni' => '66778899',
                    'nombre' => 'Marcos Herrera',
                    'mesa' => 'D2',
                    'asiento' => '03',
                    'checked1' => false,
                    'checked2' => false,
                    'codigo_socio' => '54321'
                ],
            ],
        ];

        if (!isset($participantes[$eventoId])) {
            return [];
        }

        if ($codigoSocio) {
            $filtered = array_values(array_filter($participantes[$eventoId], function($p) use ($codigoSocio) {
                return $p['codigo_socio'] === $codigoSocio;
            }));
            return $filtered;
        }

        return $participantes[$eventoId];
    }

    /**
     * Simulación de participantes por área (entrada).
     * El parámetro $area puede ser 'area1','area2','area3' o un identificador de evento como 'evento_1'.
     */
    public function getParticipantesByArea($area, $codigoSocio = null)
    {
        // Mapear áreas a listas de participantes (reutilizando DNIs existentes)
        $areas = [
            'area1' => [
                [ 'dni' => '12345678', 'nombre' => 'Juan Pérez González', 'edad' => 45, 'relacion' => 'Titular', 'checked' => true, 'codigo_socio' => '12345' ],
                [ 'dni' => '87654321', 'nombre' => 'María Pérez López', 'edad' => 42, 'relacion' => 'Esposa', 'checked' => false, 'codigo_socio' => '12345' ],
                [ 'dni' => '99887766', 'nombre' => 'Ana Silva Ruiz', 'edad' => 30, 'relacion' => 'Invitado', 'checked' => true, 'codigo_socio' => '54321' ],
            ],
            'area2' => [
                [ 'dni' => '22334455', 'nombre' => 'Lucía Martínez', 'edad' => 35, 'relacion' => 'Titular', 'checked' => true, 'codigo_socio' => '22222' ],
                [ 'dni' => '33445566', 'nombre' => 'Carlos Gómez', 'edad' => 28, 'relacion' => 'Invitado', 'checked' => false, 'codigo_socio' => '22222' ],
            ],
            'area3' => [
                [ 'dni' => '44556677', 'nombre' => 'Sofía Ramos', 'edad' => 50, 'relacion' => 'Titular', 'checked' => true, 'codigo_socio' => '33333' ],
                [ 'dni' => '55667788', 'nombre' => 'Diego Torres', 'edad' => 20, 'relacion' => 'Hijo', 'checked' => false, 'codigo_socio' => '33333' ],
            ],
            // permitir pasar 'evento_{id}' para usar la lista de eventos simulada
            'evento_1' => $this->getParticipantes(1),
            'evento_2' => $this->getParticipantes(2),
            'evento_3' => $this->getParticipantes(3),
        ];

        if (!isset($areas[$area])) {
            return [];
        }

        $list = $areas[$area];

        if ($codigoSocio) {
            $filtered = array_values(array_filter($list, function($p) use ($codigoSocio) {
                return isset($p['codigo_socio']) && $p['codigo_socio'] === $codigoSocio;
            }));
            return $filtered;
        }

        return $list;
    }
}
