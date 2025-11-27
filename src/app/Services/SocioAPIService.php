<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class SocioAPIService
{
    protected $apiUrl;

    public function __construct()
    {
        // URL de la API externa - configurar en .env
        $this->apiUrl = env('SOCIO_API_URL', 'http://api-externa.com');
    }

    /**
     * Buscar socio por código
     *
     * @param string $codigo
     * @return array|null
     */
    public function buscarSocioPorCodigo($codigo)
    {
        try {
            $response = Http::timeout(10)
                ->get("{$this->apiUrl}/api/socios/{$codigo}");

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning("Socio no encontrado: {$codigo}");
            return null;

        } catch (Exception $e) {
            Log::error("Error al buscar socio: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Buscar socio por DNI
     *
     * @param string $dni
     * @return array|null
     */
    public function buscarSocioPorDNI($dni)
    {
        try {
            $response = Http::timeout(10)
                ->get("{$this->apiUrl}/api/socios", [
                    'dni' => $dni
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['data'][0] ?? null;
            }

            return null;

        } catch (Exception $e) {
            Log::error("Error al buscar socio por DNI: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Buscar múltiples socios (para búsqueda general)
     *
     * @param string $termino Código, nombre o DNI
     * @return array
     */
    public function buscarSocios($termino)
    {
        try {
            $response = Http::timeout(10)
                ->get("{$this->apiUrl}/api/socios", [
                    'q' => $termino
                ]);

            if ($response->successful()) {
                return $response->json()['data'] ?? [];
            }

            return [];

        } catch (Exception $e) {
            Log::error("Error al buscar socios: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener familiares de un socio titular
     * Los familiares tienen formato: ####-XXX (ej: 0001-A, 0001-FAM)
     *
     * @param string $codigoSocio Código del socio titular (4 dígitos)
     * @return array
     */
    public function obtenerFamiliaresSocio($codigoSocio)
    {
        try {
            $response = Http::timeout(10)
                ->get("{$this->apiUrl}/api/socios/{$codigoSocio}/familiares");

            if ($response->successful()) {
                return $response->json()['data'] ?? [];
            }

            return [];

        } catch (Exception $e) {
            Log::error("Error al obtener familiares: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener TODOS los socios y familiares
     * Para entrada del club - lista completa
     *
     * @return array
     */
    public function obtenerTodosSocios()
    {
        try {
            // Usar API simulada si no hay conexión real
            return \App\Services\SocioAPISimulada::obtenerTodosSocios();

        } catch (Exception $e) {
            Log::error("Error al obtener todos los socios: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Alias para compatibilidad con código existente
     * @deprecated Usar obtenerFamiliaresSocio() en su lugar
     */
    public function obtenerInvitadosSocio($codigoSocio)
    {
        return $this->obtenerFamiliaresSocio($codigoSocio);
    }    /**
     * Formatear datos del socio para uso interno
     *
     * @param array $socioData
     * @return array
     */
    public function formatearSocio($socioData)
    {
        return [
            'codigo' => $socioData['codigo'] ?? '',
            'nombre' => $socioData['nombre'] ?? '',
            'dni' => $socioData['dni'] ?? '',
            'email' => $socioData['email'] ?? '',
            'telefono' => $socioData['telefono'] ?? '',
            'tipo' => 'socio'
        ];
    }

    /**
     * Formatear datos del familiar para uso interno
     * Familiares: ####-XXX formato (ej: 0001-A, 0234-FAM)
     *
     * @param array $familiarData
     * @param string $codigoSocio Código del socio titular
     * @return array
     */
    public function formatearFamiliar($familiarData, $codigoSocio)
    {
        return [
            'codigo' => $familiarData['codigo'] ?? '',
            'codigo_socio' => $codigoSocio,
            'nombre' => $familiarData['nombre'] ?? '',
            'dni' => $familiarData['dni'] ?? '',
            'parentesco' => $familiarData['parentesco'] ?? '',
            'tipo' => 'familiar'
        ];
    }

    /**
     * Alias para compatibilidad
     * @deprecated Usar formatearFamiliar() en su lugar
     */
    public function formatearInvitado($invitadoData, $codigoSocio)
    {
        return $this->formatearFamiliar($invitadoData, $codigoSocio);
    }

    /**
     * Verificar si un código corresponde a un socio titular
     * Formato: #### (4 dígitos sin guion)
     */
    public function esSocioTitular($codigo)
    {
        return preg_match('/^\d{4}$/', $codigo) === 1;
    }

    /**
     * Verificar si un código corresponde a un familiar
     * Formato: ####-XXX (4 dígitos + guion + letras)
     */
    public function esFamiliar($codigo)
    {
        return preg_match('/^\d{4}-[A-Z]+$/', $codigo) === 1;
    }

    /**
     * Extraer código del socio titular de un código de familiar
     * Ej: "0001-A" -> "0001"
     */
    public function extraerCodigoSocio($codigo)
    {
        if ($this->esFamiliar($codigo)) {
            return substr($codigo, 0, 4);
        }
        return $codigo;
    }
}
