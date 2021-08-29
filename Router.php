<?php

/**
 * @author kv.kn <aknk.v@protonmail.ch>
 * @product StudentLife
 * @package uglydavy\phpmvc
 */

namespace uglydavy\phpmvc;


use uglydavy\phpmvc\exception\NotFoundException;

/**
 * @property Request request
 * @property Response response
 * @property array routes = []
 */

class Router
{
    public function __construct (Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get ($path, $callback)
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post ($path, $callback)
    {
        $this->routes['POST'][$path] = $callback;
    }

    /**
     * @throws NotFoundException
     */
    public function resolve ()
    {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false)
        {
            $this->response->setStatusCode(404);
            throw new NotFoundException();
        }

        if ( is_string($callback) )
            return Application::$app->view->renderView($callback);

        if ( is_array($callback) )
        {
            /** @var Controller $controller */

            $controller = new $callback[0]();
            Application::$app->controller = $controller;
            $controller->action = $callback[1];
            $callback[0] = $controller;

            foreach ($controller->getMiddlewares() as $middleware)
                $middleware->execute();

            $callback[0] = Application::$app->controller;
        }

        return call_user_func($callback, $this->request, $this->response);
    }
}