<?php

    namespace App\Core;

    use App\Core\Error_404;

    class Router 
    {
        public static function resolver($routes)
        {
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

            if (isset($routes[$uri])) {
                $route = $routes[$uri];
                
                if (!empty($route['middleware']) && is_array($route['middleware'])) {
                    array_map(function ($middlewareKey) {
                        $middlewareClass = \App\Config\getMiddlewares($middlewareKey);
                        $middleware = new $middlewareClass();
                        if (method_exists($middleware, '__invoke')) {
                            $middleware();
                        } else {
                            throw new \Exception("Le middleware '$middlewareKey' doit avoir une méthode __invoke()");
                        }
                    }, $route['middleware']);
                }

                $controllerClass = $route['controller'];
                $controllerAction = $route['action'];

                $controller = new $controllerClass();
                $controller->$controllerAction();
            } else {
                Error_404::render();
            }
        }
    }
