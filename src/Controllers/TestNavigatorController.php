<?php

namespace App\Controllers;

use Core\Router;

class TestNavigatorController
{
    /**
     * Muestra una página con enlaces a todas las rutas registradas en la aplicación.
     *
     * @return void
     */
    public function index(): void
    {
        $router = Router::getInstance();
        $routes = [];

        if ($router) {
            $routes = $router->getRoutesList();
        }

        render_view('test-navigator.php', [
            'title' => 'Test Navigator',
            'routes' => $routes
        ]);
    }
}

