<?php
/**
 * @author kv.kn <aknk.v@protonmail.ch>
 * @product StudentLife
 * @package app\core\exception
 */

namespace app\core\exception;

use Exception;

class NotFoundException extends Exception
{
    protected $message = "Page not found";
    protected $code = 404;
}