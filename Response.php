<?php
/**
 * @author kv.kn <aknk.v@protonmail.ch>
 * @product StudentLife
 * @package app\core
 */

namespace app\core;

class Response
{
    public function setStatusCode (int $code)
    {
        http_response_code($code);
    }

    public function redirect (string $url)
    {
        header('Location: '.$url);
    }
}