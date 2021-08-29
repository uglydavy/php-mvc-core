<?php
/**
 * @author kv.kn <aknk.v@protonmail.ch>
 * @product StudentLife
 * @package uglydavy\phpmvc\middlewares
 */

namespace uglydavy\phpmvc\middlewares;

abstract class BaseMiddleware
{
    abstract public function execute();
}