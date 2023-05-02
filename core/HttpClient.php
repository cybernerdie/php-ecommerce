<?php

namespace App\Core;

/**
 * Class HttpClient
 * @package App\Core
 */
class HttpClient 
{
    /**
     * @var string The base URL for the API
     */
    private $baseUrl;

    public function __construct() {
        $this->baseUrl = baseUrl();
    }

    /**
     * Makes a GET request to the API
     *
     * @param string $path The path to the endpoint
     * @param array $query An optional array of query string parameters
     * @return mixed The response data
     */
    public function get(string $path, array $query = [], $token = null)
    {
        $url = $this->baseUrl . $path;

        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }

        $headers = [
            'Content-Type: application/json',
        ];

        if ($token !== null) {
            $headers[] = 'Authorization: Bearer ' . $token;
        }

        $options = [
            CURLOPT_HTTPHEADER => $headers,
        ];

        return $this->request('GET', $url, $options);
    }

    /**
     * Makes a POST request to the API
     *
     * @param string $path The path to the endpoint
     * @param array $data The request data
     * @param string|null $token An optional authentication token
     * @return mixed The response data
     */
    public function post(string $path, array $data, $token = null) {
        $url = $this->baseUrl . $path;
    
        $options = [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($data)),
                'Authorization: Bearer ' . $token,
            ]
        ];
    
        return $this->request('POST', $url, $options);
    }

    /**
     * Makes a PUT request to the API
     *
     * @param string $path The path to the endpoint
     * @param array $data The request data
     * @param string|null $token An optional authentication token
     * @return mixed The response data
     */
    public function put(string $path, array $data, $token = null) {
        $url = $this->baseUrl . $path;
    
        $options = [
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($data)),
                'Authorization: Bearer ' . $token,
            ]
        ];
    
        return $this->request('PUT', $url, $options);
    }

    /**
     * Makes a DELETE request to the API
     *
     * @param string $path The path to the endpoint
     * @param array $data The request data
     * @param string|null $token An optional authentication token
     * @return mixed The response data
     */
    public function delete(string $path, array $data = [], $token = null) {
        $url = $this->baseUrl . $path;

        $options = [
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($data)),
                'Authorization: Bearer ' . $token,
            ]
        ];

        return $this->request('DELETE', $url, $options);
    }

    /**
     * Sends a request to the API
     *
     * @param string $method The HTTP method to use for the request
     * @param string $url The URL to send the request to
     * @param array $options An array of options to pass to cURL
     *
     * @return array The response data
     */
    private function request(string $method, string $url, array $options = []): array
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
        ]);

        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }

        $response = curl_exec($ch);

        curl_close($ch);

        return json_decode($response, true);
    }
}