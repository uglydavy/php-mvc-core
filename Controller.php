<?php
/**
 * @author kv.kn <aknk.v@protonmail.ch>
 * @product StudentLife
 * @package app\core
 */

namespace app\core;

use app\core\middlewares\BaseMiddleware;

class Controller
{
    public $layout = 'main';
    public $action = '';
    public $middlewares = [];

    /**
     * @var BaseMiddleware[]
     */

    public function setLayout ($layout)
    {
        $this->layout = $layout;
    }

    public function render ($view, $params = [])
    {
        return Application::$app->view->renderView($view, $params);
    }

    public function registerMiddleware (BaseMiddleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    /**
     * @return array
     */
    public function getMiddlewares (): array
    {
        return $this->middlewares;
    }
}