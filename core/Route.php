<?php

namespace App\Core;

use Core\Middlewares\Middleware;
use Exception;
use ReflectionClass;
use ReflectionMethod;

class Route
{
    /**
     * All registered routes.
     *
     * @var array
     */
    protected static $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'PATCH' => [],
        'DELETE' => [],
    ];

    /**
     * The last method called in the chain.
     *
     * @var string|null
     */
    protected static $lastMethod;

    /**
     * The last URL called in the chain.
     *
     * @var string|null
     */
    protected static $lastUrl;

    /**
     * Register a GET route.
     *
     * @param string $uri
     * @param string $action
     * @return Route
     */
    public static function get($uri, $action): Route
    {
        return self::addRoute('GET', $uri, $action);
    }

    /**
     * Register a POST route.
     *
     * @param string $uri
     * @param string $action
     */
    public static function post($uri, $action)
    {
        return self::addRoute('POST', $uri, $action);
    }

    /**
     * Register a PUT route.
     *
     * @param string $uri
     * @param string $action
     */
    public static function put($uri, $action)
    {
        return self::addRoute('PUT', $uri, $action);
    }

    /**
     * Register a PATCH route.
     *
     * @param string $uri
     * @param string $action
     */
    public static function patch($uri, $action)
    {
        return self::addRoute('PATCH', $uri, $action);
    }

    /**
     * Register a DELETE route.
     *
     * @param string $uri
     * @param string $action
     */
    public static function delete($uri, $action)
    {
        return self::addRoute('DELETE', $uri, $action);
    }

    /**
     * Add a route to the routes array.
     *
     * @param string $method
     * @param string $uri
     * @param string $action
     */
    protected static function addRoute($method, $uri, $action)
    {
        $uri = baseUrl() . $uri;

        self::$lastMethod = $method;
        self::$lastUrl = $uri;

        if (is_array($action) && ! is_callable($action)) {
            $action = implode('@', $action);
        }

        self::$routes[$method][$uri] = [
            'controller' => $action,
        ];

        return new Route($method, $uri, $action);
    }

    /**
     * Add middleware to the registered route.
     *
     * @param string $middleware
     * @return Route
     */
    public function middleware(string $middleware): Route
    {
        self::$routes[self::$lastMethod][self::$lastUrl]['middleware'] = $middleware;

        return $this;
    }

    /**
     * Direct the request to the appropriate controller method.
     *
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function route(Request $request)
    {
        $routeInfo = $this->getRouteInfo($request->uri(), $request->method());

        if ($routeInfo) {
            return $this->callAction(
                $routeInfo['controller'],
                $routeInfo['action'],
                $routeInfo['param'],
                $routeInfo['routeInfo'],
                $request
            );
        }

        return Response::send(false, HTTP_NOT_FOUND, 'Invalid request url');
    }

    /**
     * Get route information for the given URI and request type.
     *
     * @param string $uri
     * @param string $requestType
     * @return array|null
     */
    private function getRouteInfo(string $uri, string $requestType): ?array
    {
        foreach (self::$routes[$requestType] as $pattern => $routeInfo) {

            $regex = $this->generateRouteRegex($pattern);

            if (preg_match($regex, $uri, $matches)) {
                array_shift($matches);
                $param = isset($matches[0]) ? (int)$matches[0] : null;
                list($controller, $action) = explode('@', $routeInfo['controller']);

                return [
                    'controller' => $controller,
                    'action' => $action,
                    'param' => $param,
                    'routeInfo' => $routeInfo,
                ];
            }
        }

        return null;
    }

    /**
     * Generate a regex pattern for the given route pattern.
     *
     * @param string $pattern
     * @return string
     */
    private function generateRouteRegex(string $pattern): string
    {
        $regex = preg_replace('/{[^\/]+}/', '([^/]+)', $pattern);
        return '/^' . str_replace('/', '\/', $regex) . '$/';
    }

    /**
     * Call the controller action with the given parameters and route information.
     *
     * @param string $controller
     * @param string $action
     * @param array $routeParams
     * @param array $routeInfo
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    protected function callAction(string $controller, string $action, ?int $routeParam, array $routeInfo, Request $request): mixed
    {
        $controller = "App\\Controllers\\{$controller}";
        $controllerInstance = $this->resolveDependencies(new ReflectionClass($controller));
        $reflectionMethod = new ReflectionMethod($controllerInstance, $action);
        $parameters = $reflectionMethod->getParameters();

        if (!method_exists($controllerInstance, $action)) {
            return Response::send(false, HTTP_NOT_FOUND, "The requested action '{$action}' is not available on the '{$controller}' controller.");
        }
    
        // Apply middleware if exists
        if (isset($routeInfo['middleware'])) {
            $this->applyMiddleware($routeInfo['middleware'], $request);
        }
    
        // Validate the request
        $request = $this->validateRequest($controllerInstance, $action, $request);

        if ($this->actionRequiresRequestObject($parameters)) {
            return $controllerInstance->$action($request, $routeParam);
        }

        return $controllerInstance->$action($routeParam);
    }

    /**
     * Validates a Request object against its corresponding Request class, if any.
     *
     * @param object $controllerInstance
     * @param string $action
     * @param Request $request
     *
     * @return Request
     */
    private function validateRequest(object $controllerInstance, string $action, Request $request): Request
    {
        $reflectionMethod = new ReflectionMethod($controllerInstance, $action);
        $parameters = $reflectionMethod->getParameters();

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if ($type && is_subclass_of($type->getName(), Request::class)) {
                $requestClass = $type->getName();
                $request = new $requestClass($request->all());
                $request->validate();
                break;
            }
        }

        return $request;
    }

    /**
     * Resolves dependencies of a class using reflection.
     *
     * @param ReflectionClass $reflector
     * @return mixed
     * @throws Exception
     */
    protected function resolveDependencies(ReflectionClass $reflector)
    {
        if (!$constructor = $reflector->getConstructor()) {
            return new $reflector->name;
        }
    
        $dependencies = [];
    
        foreach ($constructor->getParameters() as $parameter) {
            $type = $parameter->getType();
            if (!$type) {
                throw new Exception("Unable to resolve class dependency {$parameter->name}");
            }
    
            $class = new ReflectionClass($type->getName());
            $dependencies[] = $this->resolveDependencies($class);
        }
    
        return $reflector->newInstanceArgs($dependencies);
    }

    /**
     * Apply middleware to the request.
     *
     * @param string $middleware
     * @param Request $request
     * @return void
     */
    protected function applyMiddleware(string $middleware, Request $request): void
    {
        Middleware::handle($middleware, $request);
    }

    /**
     * Determines if a controller action method requires a Request object as its parameter.
     *
     * @param ReflectionParameter[] $parameters
     *
     * @return bool
     */
    private function actionRequiresRequestObject(array $parameters): bool
    {
        foreach ($parameters as $parameter) {
            $type = $parameter->getType();
            if ($type && (is_a($type->getName(), Request::class, true) || $type->getName() === Request::class)) {
                return true;
            }
        }
        return false;
    }
}
