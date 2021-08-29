<?php
/**
 * @author kv.kn <aknk.v@protonmail.ch>
 * @product StudentLife
 * @package app\core\middlewares
 */

namespace app\core\middlewares;

abstract class BaseMiddleware
{
    abstract public function execute();
}