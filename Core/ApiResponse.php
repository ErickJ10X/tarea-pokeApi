<?php

namespace Core;

/**
 * ApiResponse
 *
 * Clase helper para estandarizar las respuestas de la API REST.
 * Se encarga de enviar las respuestas JSON con los códigos HTTP adecuados.
 *
 * @package Core
 */
class ApiResponse
{
    /**
     * Envía una respuesta JSON exitosa
     *
     * @param mixed $data Los datos a devolver en la respuesta
     * @param int $statusCode Código HTTP de la respuesta (default: 200)
     * @return void
     */
    public static function success($data, int $statusCode = 200): void
    {
        self::sendResponse($data, $statusCode);
    }

    /**
     * Envía una respuesta JSON de error
     *
     * @param string $message Mensaje de error
     * @param int $statusCode Código HTTP de error (default: 400)
     * @param mixed $data Datos adicionales del error (opcional)
     * @return void
     */
    public static function error(string $message, int $statusCode = 400, $data = null): void
    {
        $response = [
            'status' => 'error',
            'message' => $message
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        self::sendResponse($response, $statusCode);
    }

    /**
     * Envía una respuesta JSON genérica
     *
     * @param mixed $data Los datos a devolver
     * @param int $statusCode Código HTTP
     * @return void
     */
    private static function sendResponse($data, int $statusCode = 200): void
    {
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Content-Type');

        http_response_code($statusCode);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
}
