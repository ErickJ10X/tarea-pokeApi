<?php

namespace Core;

/**
 * HttpClient
 *
 * Clase utilizada para realizar peticiones HTTP a servicios web externos.
 * Utiliza cURL para manejar peticiones GET, POST, PUT y DELETE.
 *
 * @package Core
 */
class HttpClient
{
    /**
     * Realiza una petición HTTP y devuelve la respuesta
     *
     * @param string $url URL del servicio web
     * @param string $method Método HTTP (GET, POST, PUT, DELETE)
     * @param array|null $data Datos a enviar en la petición (solo para POST/PUT)
     * @param array $headers Headers adicionales para la petición
     *
     * @return array Contiene 'status' (código HTTP), 'data' (respuesta), 'error' (si existe)
     */
    public static function request(string $url, string $method = 'GET', ?array $data = null, array $headers = []): array
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        // Headers por defecto
        $defaultHeaders = [
            'Content-Type: application/json',
            'Accept: application/json',
            'User-Agent: Docker-MVC-Template/1.0'
        ];

        // Mezclar headers
        $allHeaders = array_merge($defaultHeaders, $headers);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $allHeaders);

        // Enviar datos si es POST o PUT
        if ($method !== 'GET' && $data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);

        if ($curlError) {
            return [
                'status' => 0,
                'data' => null,
                'error' => $curlError
            ];
        }

        $decodedData = json_decode($response, true);

        return [
            'status' => $httpCode,
            'data' => $decodedData ?? $response,
            'error' => null
        ];
    }

    /**
     * Realiza una petición GET a un servicio web
     *
     * @param string $url URL del servicio web
     * @param array $headers Headers adicionales para la petición
     *
     * @return array Contiene 'status', 'data' y 'error'
     */
    public static function get(string $url, array $headers = []): array
    {
        return self::request($url, 'GET', null, $headers);
    }

    /**
     * Realiza una petición POST a un servicio web
     *
     * @param string $url URL del servicio web
     * @param array $data Datos a enviar en la petición
     * @param array $headers Headers adicionales para la petición
     *
     * @return array Contiene 'status', 'data' y 'error'
     */
    public static function post(string $url, array $data, array $headers = []): array
    {
        return self::request($url, 'POST', $data, $headers);
    }

    /**
     * Realiza una petición PUT a un servicio web
     *
     * @param string $url URL del servicio web
     * @param array $data Datos a enviar en la petición
     * @param array $headers Headers adicionales para la petición
     *
     * @return array Contiene 'status', 'data' y 'error'
     */
    public static function put(string $url, array $data, array $headers = []): array
    {
        return self::request($url, 'PUT', $data, $headers);
    }

    /**
     * Realiza una petición DELETE a un servicio web
     *
     * @param string $url URL del servicio web
     * @param array $headers Headers adicionales para la petición
     *
     * @return array Contiene 'status', 'data' y 'error'
     */
    public static function delete(string $url, array $headers = []): array
    {
        return self::request($url, 'DELETE', null, $headers);
    }
}

