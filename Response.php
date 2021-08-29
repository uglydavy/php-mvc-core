<?php
/**
 * @author kv.kn <aknk.v@protonmail.ch>
 * @product StudentLife
 * @package uglydavy\phpmvc
 */

namespace uglydavy\phpmvc;

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