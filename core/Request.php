<?php

namespace App\Core;

use App\Core\Validation\Validator;

class Request
{
    /**
     * The authenticated user for the request.
     *
     * @var object
     */
    public $user;

    /**
     * The validator instance used to validate the request data.
     *
     * @var Validator
     */
    protected $validator;

    /**
     * Create a new Request instance.
     */
    public function __construct()
    {
        $this->validator = new Validator();
    }

    /**
     * Fetch the request URI.
     *
     * @return string
     */
    public static function uri()
    {
        $baseUrl = baseUrl();
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $url = $baseUrl . $path;
    
        return $url;
    }

    /**
     * Fetch the request method.
     *
     * @return string
     */
    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Get the value of a specific HTTP header.
     *
     * @param string $header
     * @return string|null
     */
    public function getHeader($header): ?string
    {
        $headers = apache_request_headers();

        if (isset($headers[$header])) {
            return $headers[$header];
        }

        return null;
    }

    /**
     * Set the authenticated user for the request.
     *
     * @param object $user
     * @return void
     */
    public function setUser(object $user): void
    {
        $this->user = $user;
    }

    /**
     * Get the authenticated user for the request.
     *
     * @return object|null
     */
    public function user(): ?object
    {
        return $this->user;
    }

    /**
     * Validate the request data using the defined validation rules.
     *
     * @return void
     * @throws InvalidRequestException
     */
    public function validate()
    {
        $errors = $this->validator->validate($this->all(), $this->rules());

        if (!empty($errors)) {
            Response::error('The given data was invalid.', HTTP_BAD_REQUEST, $errors);
        }
    }

    /**
     * Get the validated request data.
     *
     * @return array
     */
    public function validated()
    {
        return $this->validator->validatedData();
    }

    /**
     * Get all the request parameters (GET and POST).
     *
     * @return array
     */
    public function all()
    {
        $query = $_GET;
        $body = json_decode(file_get_contents('php://input'), true);

        if (is_array($body)) {
            return array_merge($query, $body);
        }

        return $query;
    }

    /**
     * Get the validation rules for the request data.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Dynamically retrieve a validated request parameter.
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->validated()[$name] ?? null;
    }
}

