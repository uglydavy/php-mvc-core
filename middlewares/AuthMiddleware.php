<?php
/**
 * @author kv.kn <aknk.v@protonmail.ch>
 * @product StudentLife
 * @package uglydavy\phpmvc\middlewares
 */

namespace uglydavy\phpmvc\middlewares;

use uglydavy\phpmvc\Application;
use uglydavy\phpmvc\exception\ForbiddenException;
use uglydavy\phpmvc\exception\NotFoundException;

/**
 * @property array actions = []
 */

class AuthMiddleware extends BaseMiddleware
{
    public function __construct ( array $actions = [] )
    {
        $this->actions = $actions;
    }

    /**
     * @throws ForbiddenException
     */
    public function execute ()
    {
        if ( Application::isGuest() )
        {
            if ( empty($this->actions) || in_array(Application::$app->controller->action, $this->actions) )
                throw new ForbiddenException();
        }
    }
}