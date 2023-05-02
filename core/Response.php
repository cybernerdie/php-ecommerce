<?php

namespace App\Core;

class Response
{
    /**
     * Send a JSON response.
     *
     * @param bool $status
     * @param int $code
     * @param string|null $message
     * @param mixed $data
     * @return void
     */
    public static function send(bool $status, int $code, string $message = null, $data = [])
    {
        $data = [
            'data' => $data
        ];

        self::jsonResponse($status, $code, $message, $data);
    }

    /**
     * Send a JSON response.
     *
     * @param bool $status
     * @param int $code
     * @param string|null $message
     * @param mixed $data
     * @return void
     */
    public static function sendWithCollection(bool $status, int $code, string $message = null, $data)
    {
        $data = [
            'data' => $data['data'],
            'meta' => $data['meta'],
            'links' => $data['links']
        ];

        self::jsonResponse($status, $code, $message, $data);
    }

    /**
     * Send a JSON response.
     *
     * @param int $code
     * @param string|null $message
     * @param mixed $data
     * @return void
     */
    public static function error(string $message = null, int $code, $errors = [])
    {
        $data = [
            'errors' => $errors
        ];

        self::jsonResponse(false, $code, $message, $data);
    }

    /**
     * Send a JSON response with status and data.
     *
     * @param bool $status
     * @param int $code
     * @param mixed $data
     * @return void
     */
    private static function jsonResponse(bool $status, int $code, string $message, $data)
    {
        $response = [
            'status' => $status,
            'message' => $message,
            ...$data
        ];

        header('Content-Type: application/json');
        http_response_code($code);
        echo json_encode($response);
        exit();
    }
}
    
