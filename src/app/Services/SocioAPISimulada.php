<?php

namespace App\Services;

class SocioAPISimulada
{
    /**
     * Datos simulados de socios
     */
    private static $socios = [
        '0001' => [
            'codigo' => '0001',
            'dni' => '12345678',
            'nombre' => 'Juan Pérez García',
            'telefono' => '987654321',
            'email' => 'juan.perez@email.com',
            'estado' => 'activo',
            'familiares' => [
                [
                    'codigo' => '0001-A',
                    'dni' => '87654321',
                    'nombre' => 'María Pérez López',
                    'parentesco' => 'Esposa',
                    'edad' => 35
                ],
                [
                    'codigo' => '0001-B',
                    'dni' => '11223344',
                    'nombre' => 'Carlos Pérez López',
                    'parentesco' => 'Hijo',
                    'edad' => 12
                ],
                [
                    'codigo' => '0001-C',
                    'dni' => '44332211',
                    'nombre' => 'Ana Pérez López',
                    'parentesco' => 'Hija',
                    'edad' => 8
                ]
            ]
        ],
        '0002' => [
            'codigo' => '0002',
            'dni' => '23456789',
            'nombre' => 'Carlos Rodríguez Silva',
            'telefono' => '976543210',
            'email' => 'carlos.rodriguez@email.com',
            'estado' => 'activo',
            'familiares' => [
                [
                    'codigo' => '0002-A',
                    'dni' => '98765432',
                    'nombre' => 'Laura Silva Mendoza',
                    'parentesco' => 'Esposa',
                    'edad' => 32
                ]
            ]
        ],
        '0003' => [
            'codigo' => '0003',
            'dni' => '34567890',
            'nombre' => 'Ana Martínez Torres',
            'telefono' => '965432109',
            'email' => 'ana.martinez@email.com',
            'estado' => 'activo',
            'familiares' => [
                [
                    'codigo' => '0003-A',
                    'dni' => '56789012',
                    'nombre' => 'Pedro Martínez Ruiz',
                    'parentesco' => 'Esposo',
                    'edad' => 42
                ],
                [
                    'codigo' => '0003-B',
                    'dni' => '67890123',
                    'nombre' => 'Sofia Martínez Ruiz',
                    'parentesco' => 'Hija',
                    'edad' => 15
                ]
            ]
        ],
        '0004' => [
            'codigo' => '0004',
            'dni' => '45678901',
            'nombre' => 'Roberto Sánchez Díaz',
            'telefono' => '954321098',
            'email' => 'roberto.sanchez@email.com',
            'estado' => 'activo',
            'familiares' => []
        ],
        '0005' => [
            'codigo' => '0005',
            'dni' => '78901234',
            'nombre' => 'Luis García Morales',
            'telefono' => '943210987',
            'email' => 'luis.garcia@email.com',
            'estado' => 'activo',
            'familiares' => [
                [
                    'codigo' => '0005-A',
                    'dni' => '89012345',
                    'nombre' => 'Carmen Morales Vega',
                    'parentesco' => 'Esposa',
                    'edad' => 38
                ],
                [
                    'codigo' => '0005-B',
                    'dni' => '90123456',
                    'nombre' => 'Diego García Morales',
                    'parentesco' => 'Hijo',
                    'edad' => 10
                ]
            ]
        ],
        '0006' => [
            'codigo' => '0006',
            'dni' => '56789012',
            'nombre' => 'Patricia Fernández Castro',
            'telefono' => '932109876',
            'email' => 'patricia.fernandez@email.com',
            'estado' => 'activo',
            'familiares' => [
                [
                    'codigo' => '0006-A',
                    'dni' => '67890123',
                    'nombre' => 'Miguel Castro Ruiz',
                    'parentesco' => 'Esposo',
                    'edad' => 45
                ],
                [
                    'codigo' => '0006-B',
                    'dni' => '78901234',
                    'nombre' => 'Valentina Castro Fernández',
                    'parentesco' => 'Hija',
                    'edad' => 16
                ],
                [
                    'codigo' => '0006-C',
                    'dni' => '89012345',
                    'nombre' => 'Mateo Castro Fernández',
                    'parentesco' => 'Hijo',
                    'edad' => 13
                ]
            ]
        ],
        '0007' => [
            'codigo' => '0007',
            'dni' => '90123456',
            'nombre' => 'Fernando López Vargas',
            'telefono' => '921098765',
            'email' => 'fernando.lopez@email.com',
            'estado' => 'activo',
            'familiares' => [
                [
                    'codigo' => '0007-A',
                    'dni' => '01234567',
                    'nombre' => 'Isabel Vargas Ramos',
                    'parentesco' => 'Esposa',
                    'edad' => 40
                ]
            ]
        ],
        '0008' => [
            'codigo' => '0008',
            'dni' => '12340987',
            'nombre' => 'Gabriela Torres Medina',
            'telefono' => '910987654',
            'email' => 'gabriela.torres@email.com',
            'estado' => 'activo',
            'familiares' => [
                [
                    'codigo' => '0008-A',
                    'dni' => '23451098',
                    'nombre' => 'Ricardo Medina Soto',
                    'parentesco' => 'Esposo',
                    'edad' => 43
                ],
                [
                    'codigo' => '0008-B',
                    'dni' => '34562109',
                    'nombre' => 'Lucía Medina Torres',
                    'parentesco' => 'Hija',
                    'edad' => 18
                ],
                [
                    'codigo' => '0008-C',
                    'dni' => '45673210',
                    'nombre' => 'Andrés Medina Torres',
                    'parentesco' => 'Hijo',
                    'edad' => 14
                ]
            ]
        ],
        '0009' => [
            'codigo' => '0009',
            'dni' => '56784321',
            'nombre' => 'Diego Ramírez Ortiz',
            'telefono' => '909876543',
            'email' => 'diego.ramirez@email.com',
            'estado' => 'activo',
            'familiares' => []
        ],
        '0010' => [
            'codigo' => '0010',
            'dni' => '67895432',
            'nombre' => 'Sofía Mendoza Flores',
            'telefono' => '998765432',
            'email' => 'sofia.mendoza@email.com',
            'estado' => 'activo',
            'familiares' => [
                [
                    'codigo' => '0010-A',
                    'dni' => '78906543',
                    'nombre' => 'Javier Flores Campos',
                    'parentesco' => 'Esposo',
                    'edad' => 39
                ],
                [
                    'codigo' => '0010-B',
                    'dni' => '89017654',
                    'nombre' => 'Emma Flores Mendoza',
                    'parentesco' => 'Hija',
                    'edad' => 11
                ]
            ]
        ],
        '0011' => [
            'codigo' => '0011',
            'dni' => '90128765',
            'nombre' => 'Mario Herrera Gutiérrez',
            'telefono' => '987654320',
            'email' => 'mario.herrera@email.com',
            'estado' => 'activo',
            'familiares' => [
                [
                    'codigo' => '0011-A',
                    'dni' => '01239876',
                    'nombre' => 'Rosa Gutiérrez Ponce',
                    'parentesco' => 'Esposa',
                    'edad' => 36
                ],
                [
                    'codigo' => '0011-B',
                    'dni' => '12340987',
                    'nombre' => 'Martín Herrera Gutiérrez',
                    'parentesco' => 'Hijo',
                    'edad' => 9
                ],
                [
                    'codigo' => '0011-C',
                    'dni' => '23451098',
                    'nombre' => 'Paula Herrera Gutiérrez',
                    'parentesco' => 'Hija',
                    'edad' => 6
                ]
            ]
        ],
        '0012' => [
            'codigo' => '0012',
            'dni' => '34562109',
            'nombre' => 'Elena Vega Paredes',
            'telefono' => '976543219',
            'email' => 'elena.vega@email.com',
            'estado' => 'activo',
            'familiares' => [
                [
                    'codigo' => '0012-A',
                    'dni' => '45673210',
                    'nombre' => 'Alberto Paredes Luna',
                    'parentesco' => 'Esposo',
                    'edad' => 44
                ]
            ]
        ],
        '0234' => [
            'codigo' => '0234',
            'dni' => '11223344',
            'nombre' => 'Jorge Castillo Montes',
            'telefono' => '965432108',
            'email' => 'jorge.castillo@email.com',
            'estado' => 'activo',
            'familiares' => [
                [
                    'codigo' => '0234-A',
                    'dni' => '22334455',
                    'nombre' => 'Claudia Montes Rivera',
                    'parentesco' => 'Esposa',
                    'edad' => 37
                ],
                [
                    'codigo' => '0234-B',
                    'dni' => '33445566',
                    'nombre' => 'Santiago Castillo Montes',
                    'parentesco' => 'Hijo',
                    'edad' => 15
                ]
            ]
        ],
        '0500' => [
            'codigo' => '0500',
            'dni' => '55667788',
            'nombre' => 'Andrea Campos Navarro',
            'telefono' => '954321097',
            'email' => 'andrea.campos@email.com',
            'estado' => 'activo',
            'familiares' => []
        ]
    ];

    /**
     * Buscar socio por código
     */
    public static function buscarSocio($codigo)
    {
        // Extraer el código base (sin sufijos de familiar)
        $codigoBase = self::extraerCodigoBase($codigo);

        if (isset(self::$socios[$codigoBase])) {
            return self::$socios[$codigoBase];
        }

        return null;
    }

    /**
     * Buscar socio y familiares por código
     */
    public static function buscarSocioConFamiliares($codigo)
    {
        $socio = self::buscarSocio($codigo);

        if (!$socio) {
            return null;
        }

        // Preparar respuesta con socio principal y familiares
        $resultado = [
            'socio_principal' => [
                'codigo' => $socio['codigo'],
                'dni' => $socio['dni'],
                'nombre' => $socio['nombre'],
                'tipo' => 'principal'
            ],
            'familiares' => []
        ];

        foreach ($socio['familiares'] as $familiar) {
            $resultado['familiares'][] = [
                'codigo' => $familiar['codigo'],
                'dni' => $familiar['dni'],
                'nombre' => $familiar['nombre'],
                'parentesco' => $familiar['parentesco'],
                'edad' => $familiar['edad'],
                'tipo' => 'familiar'
            ];
        }

        return $resultado;
    }

    /**
     * Buscar familiar específico
     */
    public static function buscarFamiliar($codigo)
    {
        // Separar código base y sufijo
        $partes = explode('-', $codigo);
        if (count($partes) !== 2) {
            return null;
        }

        $codigoBase = $partes[0];
        $sufijo = $partes[1];

        $socio = self::buscarSocio($codigoBase);
        if (!$socio) {
            return null;
        }

        // Buscar el familiar específico
        foreach ($socio['familiares'] as $familiar) {
            if ($familiar['codigo'] === $codigo) {
                return $familiar;
            }
        }

        return null;
    }

    /**
     * Obtener información básica del socio (para invitados)
     */
    public static function getNombreSocio($codigo)
    {
        $codigoBase = self::extraerCodigoBase($codigo);
        $socio = self::buscarSocio($codigoBase);

        return $socio ? $socio['nombre'] : null;
    }

    /**
     * Extraer código base sin sufijos
     */
    private static function extraerCodigoBase($codigo)
    {
        $partes = explode('-', $codigo);
        return $partes[0];
    }

    /**
     * Verificar si un código existe
     */
    public static function existeSocio($codigo)
    {
        $codigoBase = self::extraerCodigoBase($codigo);
        return isset(self::$socios[$codigoBase]);
    }

    /**
     * Obtener todos los socios (para testing)
     */
    public static function getAllSocios()
    {
        return self::$socios;
    }

    /**
     * Obtener TODOS los socios y familiares en un array plano
     * Para entrada del club
     */
    public static function obtenerTodosSocios()
    {
        $resultado = [];

        foreach (self::$socios as $socio) {
            // Agregar socio principal
            $resultado[] = [
                'codigo' => $socio['codigo'],
                'dni' => $socio['dni'],
                'nombre' => $socio['nombre'],
                'telefono' => $socio['telefono'] ?? null,
                'email' => $socio['email'] ?? null,
                'tipo' => 'socio'
            ];

            // Agregar familiares
            foreach ($socio['familiares'] as $familiar) {
                $resultado[] = [
                    'codigo' => $familiar['codigo'],
                    'dni' => $familiar['dni'],
                    'nombre' => $familiar['nombre'],
                    'parentesco' => $familiar['parentesco'] ?? null,
                    'edad' => $familiar['edad'] ?? null,
                    'tipo' => 'familiar'
                ];
            }
        }

        return $resultado;
    }
}
