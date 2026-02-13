<?php

namespace Core;

use Core\Middleware\Authenticated;
use Core\Middleware\Guest;
use Core\Middleware\Middleware;

class Router
{
    protected $routesList = [];

    public function add($method, $uri, $controller)
    {
        $this->routesList[] = [
            'uri' => $uri,
            'controller' => $controller,
            'method' => $method,
            'middleware' => null
        ];

        return $this;
    }

    public function get($uri, $controller)
    {
        return $this->add('GET', $uri, $controller);
    }

    public function post($uri, $controller)
    {
        return $this->add('POST', $uri, $controller);
    }

    public function delete($uri, $controller)
    {
        return $this->add('DELETE', $uri, $controller);
    }

    public function patch($uri, $controller)
    {
        return $this->add('PATCH', $uri, $controller);
    }

    public function put($uri, $controller)
    {
        return $this->add('PUT', $uri, $controller);
    }

    public function only($key)
    {
        $this->routesList[array_key_last($this->routesList)]['middleware'] = $key;

        return $this;
    }

    public function route($uri, $method): void
    {

        $requestedMethod = strtoupper($method);

        foreach ($this->routesList as $routeEntry) {
            // Exact match
            if ($routeEntry['uri'] === $uri && $routeEntry['method'] === $requestedMethod) {
                Middleware::resolve($routeEntry['middleware']);

                $controller = $routeEntry['controller'];

                // If controller is in the form Controller@method, resolve as class
                if (is_string($controller) && str_contains($controller, '@')) {
                    [$controllerName, $action] = explode('@', $controller);

                    $class = 'App\\Controllers\\' . $controllerName;

                    if (class_exists($class)) {
                        $controllerInstance = new $class();
                        $controllerInstance->{$action}();
                        return;
                    }

                    abort_with_status(404);
                }
                return;
            }

            // Pattern match for dynamic routes (e.g., /api/autores/:id)
            $pattern = $this->convertPatternToRegex($routeEntry['uri']);
            $matches = [];
            if (preg_match($pattern, $uri, $matches) && $routeEntry['method'] === $requestedMethod) {
                Middleware::resolve($routeEntry['middleware']);

                $controller = $routeEntry['controller'];

                if (is_string($controller) && str_contains($controller, '@')) {
                    [$controllerName, $action] = explode('@', $controller);

                    $class = 'App\\Controllers\\' . $controllerName;

                    if (class_exists($class)) {
                        $controllerInstance = new $class();
                        // Pass captured parameters to the action
                        $params = array_slice($matches, 1); // Skip the full match
                        if (!empty($params)) {
                            call_user_func_array([$controllerInstance, $action], array_map(function($p) {
                                return (int)$p;
                            }, $params));
                        } else {
                            $controllerInstance->{$action}();
                        }
                        return;
                    }

                    abort_with_status(404);
                }
                return;
            }
        }

        // If URI exists with different method, return 405
        $foundUri = false;
        $allowedMethods = [];
        foreach ($this->routesList as $routeEntry) {
            if ($routeEntry['uri'] === $uri || $this->patternMatches($routeEntry['uri'], $uri)) {
                $foundUri = true;
                $allowedMethods[] = $routeEntry['method'];
            }
        }

        if ($foundUri) {
            header('HTTP/1.1 405 Method Not Allowed');
            header('Allow: '.implode(', ', $allowedMethods));
            echo "<h1>405 Method Not Allowed</h1><p>Allowed methods: ".implode(', ', $allowedMethods)."</p>";
            exit();
        }

        abort_with_status(404);
    }

    /**
     * Convierte un patrón de ruta a una expresión regular
     * Ejemplo: /api/autores/:id -> /^\/api\/autores\/(\d+)$/
     *
     * @param string $pattern Patrón de ruta con placeholders como :id
     * @return string Expresión regular
     */
    private function convertPatternToRegex(string $pattern): string
    {
        // Primero reemplazar placeholders :param con un marcador temporal
        $regex = preg_replace('/:(\w+)/', '___PARAM___', $pattern);
        // Escapar barras diagonales y otros caracteres especiales
        $regex = preg_quote($regex, '/');
        // Reemplazar el marcador temporal con el patrón regex para capturar dígitos
        $regex = str_replace('___PARAM___', '(\d+)', $regex);
        return '/^' . $regex . '$/';
    }

    /**
     * Verifica si un URI coincide con un patrón de ruta
     *
     * @param string $pattern Patrón de ruta
     * @param string $uri URI a verificar
     * @return bool True si coincide, false en caso contrario
     */
    private function patternMatches(string $pattern, string $uri): bool
    {
        $regex = $this->convertPatternToRegex($pattern);
        return (bool)preg_match($regex, $uri);
    }

    public function previousUrl()
    {
        return $_SERVER['HTTP_REFERER'] ?? '/';
    }

}
