<?php

namespace App\Services;

use App\Models\Evento;

class EventoService
{
    public function getEventos()
    {
        // Consultar eventos reales de la base de datos
        $eventos = Evento::orderBy('fecha', 'desc')->get();

        return $eventos->map(function($evento) {
            return [
                'id' => $evento->id,
                'nombre' => $evento->nombre,
                'fecha' => $evento->fecha->format('Y-m-d'),
                'descripcion' => $evento->area ?? ''
            ];
        })->toArray();
    }

    /**
     * Obtener todos los participantes asociados a un código de socio (en todas las áreas y eventos).
     */
    public function getParticipantesByCodigo($codigoSocio)
    {
        if (!$codigoSocio) {
            return [];
        }

        $areaKeys = [
            'area1','area2','area3','piscina','gimnasio','cancha','spa',
            'evento_1','evento_2','evento_3','evento_4'
        ];

        $results = [];
        $seenDnis = [];

        foreach ($areaKeys as $a) {
            $list = $this->getParticipantesByArea($a, $codigoSocio);
            if ($list && count($list) > 0) {
                // Normalize entries: ensure keys like 'checked' etc exist consistently
                foreach ($list as $item) {
                    if (isset($seenDnis[$item['dni']])) {
                        continue; // ya agregado
                    }
                    $normalized = [
                        'dni' => $item['dni'],
                        'nombre' => $item['nombre'],
                        'edad' => $item['edad'] ?? null,
                        'relacion' => $item['relacion'] ?? ($item['mesa'] ? 'Participante' : 'visiante'),
                        'checked' => $item['checked'] ?? ($item['checked1'] ?? false),
                        'codigo_socio' => $item['codigo_socio']
                    ];
                    $results[] = $normalized;
                    $seenDnis[$item['dni']] = true;
                }
            }
        }

        return array_values($results);
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
                'foto' => 'https://i1.rgstatic.net/ii/profile.image/11431281172222146-1688472944064_Q512/Juan-Perez-Gonzalez-7.jpg'
            ],
            '87654321' => [
                'dni' => '87654321',
                'nombres' => 'María',
                'apellidos' => 'Pérez López',
                'rol' => 'Familiar',
                'estado' => 'Activo',
                'codigo' => '12345',
                'foto' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRut9ihaZHt3s7pZDnM02epDxgGklSdA0aU9uPxWi52Vcsn1yaYQVpKVPdQ4cjoIRaGw-k&usqp=CAU'
            ],
            '11223344' => [
                'dni' => '11223344',
                'nombres' => 'Pedro',
                'apellidos' => 'Pérez López',
                'rol' => 'Familiar',
                'estado' => 'Activo',
                'codigo' => '12345',
                'foto' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR9iz0AVZLtG5wWM3Xra4W8XoF3MZERmdFg_A&s'
            ],
            '99887766' => [
                'dni' => '99887766',
                'nombres' => 'Ana',
                'apellidos' => 'Silva Ruiz',
                'rol' => 'Invitado',
                'estado' => 'Activo',
                'codigo' => '54321',
                'foto' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSK_3QNSD8IjnwkvkQJgKcXblYvjvokyGl4uA&s'
            ],
            // Event 2 personas
            '22334455' => [
                'dni' => '22334455',
                'nombres' => 'Lucía',
                'apellidos' => 'Martínez',
                'rol' => 'Socio Titular',
                'estado' => 'Activo',
                'codigo' => '22222',
                'foto' => 'https://imagessl.casadellibro.com/img/autores/w/Luc%C3%83%C2%ADa%20Mart%C3%83%C2%ADnez.webp'
            ],
            '33445566' => [
                'dni' => '33445566',
                'nombres' => 'Carlos',
                'apellidos' => 'Gómez',
                'rol' => 'Invitado',
                'estado' => 'Activo',
                'codigo' => '22222',
                'foto' => 'https://img.a.transfermarkt.technology/portrait/big/1029758-1736796920.jpg?lm=1'
            ],
            // Event 3 personas
            '44556677' => [
                'dni' => '44556677',
                'nombres' => 'Sofía',
                'apellidos' => 'Ramos',
                'rol' => 'Socio Titular',
                'estado' => 'Activo',
                'codigo' => '33333',
                'foto' => 'https://cloud.ctbuh.org/people/color/sofia-ramos-1673024187.jpg'
            ],
            '55667788' => [
                'dni' => '55667788',
                'nombres' => 'Diego',
                'apellidos' => 'Torres',
                'rol' => 'Familiar',
                'estado' => 'Activo',
                'codigo' => '33333',
                'foto' => 'https://www.famousbirthdays.com/faces/torres-diego-image.jpg'
            ],
            '66778899' => [
                'dni' => '66778899',
                'nombres' => 'Marcos',
                'apellidos' => 'Herrera',
                'rol' => 'Invitado',
                'estado' => 'Activo',
                'codigo' => '54321',
                'foto' => 'https://s3.amazonaws.com/files.pucp.edu.pe/profesor/img-docentes/herrera-burstein-marcos-pompeyo-00003942.jpg'
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

            'evento_1' => array_map(function($p) {
                return [
                    'dni' => $p['dni'],
                    'nombre' => $p['nombre'],
                    'edad' => null,
                    'relacion' => isset($p['mesa']) ? 'Participante' : 'Visitante',
                    'checked' => $p['checked1'] ?? false,
                    'codigo_socio' => $p['codigo_socio']
                ];
            }, $this->getParticipantes(1)),
            'evento_2' => array_map(function($p) {
                return [
                    'dni' => $p['dni'],
                    'nombre' => $p['nombre'],
                    'edad' => null,
                    'relacion' => isset($p['mesa']) ? 'Participante' : 'Visitante',
                    'checked' => $p['checked1'] ?? false,
                    'codigo_socio' => $p['codigo_socio']
                ];
            }, $this->getParticipantes(2)),
            'evento_3' => array_map(function($p) {
                return [
                    'dni' => $p['dni'],
                    'nombre' => $p['nombre'],
                    'edad' => null,
                    'relacion' => isset($p['mesa']) ? 'Participante' : 'Visitante',
                    'checked' => $p['checked1'] ?? false,
                    'codigo_socio' => $p['codigo_socio']
                ];
            }, $this->getParticipantes(3)),
            'evento_4' => array_map(function($p) {
                return [
                    'dni' => $p['dni'],
                    'nombre' => $p['nombre'],
                    'edad' => null,
                    'relacion' => isset($p['mesa']) ? 'Participante' : 'Visitante',
                    'checked' => $p['checked1'] ?? false,
                    'codigo_socio' => $p['codigo_socio']
                ];
            }, $this->getParticipantes(4)),
            // otras áreas comunes
            'piscina' => [
                [ 'dni' => '12345678', 'nombre' => 'Juan Pérez González', 'edad' => 45, 'relacion' => 'Titular', 'checked' => true, 'codigo_socio' => '12345' ],
                [ 'dni' => '66778899', 'nombre' => 'Marcos Herrera', 'edad' => 38, 'relacion' => 'Invitado', 'checked' => false, 'codigo_socio' => '54321' ],
            ],
            'gimnasio' => [
                [ 'dni' => '22334455', 'nombre' => 'Lucía Martínez', 'edad' => 35, 'relacion' => 'Titular', 'checked' => true, 'codigo_socio' => '22222' ],
                [ 'dni' => '33445566', 'nombre' => 'Carlos Gómez', 'edad' => 28, 'relacion' => 'Invitado', 'checked' => false, 'codigo_socio' => '22222' ],
            ],
            'cancha' => [
                [ 'dni' => '44556677', 'nombre' => 'Sofía Ramos', 'edad' => 50, 'relacion' => 'Titular', 'checked' => true, 'codigo_socio' => '33333' ],
                [ 'dni' => '55667788', 'nombre' => 'Diego Torres', 'edad' => 20, 'relacion' => 'Hijo', 'checked' => false, 'codigo_socio' => '33333' ],
            ],
            'spa' => [
                [ 'dni' => '99887766', 'nombre' => 'Ana Silva Ruiz', 'edad' => 30, 'relacion' => 'Invitado', 'checked' => true, 'codigo_socio' => '54321' ],
            ],
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
