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
        '0234' => [
            'codigo' => '0234',
            'dni' => '45678901',
            'nombre' => 'Roberto Sánchez Díaz',
            'telefono' => '954321098',
            'email' => 'roberto.sanchez@email.com',
            'estado' => 'activo',
            'familiares' => []
        ],
        '0500' => [
            'codigo' => '0500',
            'dni' => '78901234',
            'nombre' => 'Luis García Morales',
            'telefono' => '943210987',
            'email' => 'luis.garcia@email.com',
            'estado' => 'activo',
            'familiares' => [
                [
                    'codigo' => '0500-A',
                    'dni' => '89012345',
                    'nombre' => 'Carmen Morales Vega',
                    'parentesco' => 'Esposa',
                    'edad' => 38
                ]
            ]
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
}
