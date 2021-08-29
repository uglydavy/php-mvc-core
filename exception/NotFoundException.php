<?php
/**
 * @author kv.kn <aknk.v@protonmail.ch>
 * @product StudentLife
 * @package uglydavy\phpmvc\exception
 */

namespace uglydavy\phpmvc\exception;

use Exception;

class NotFoundException extends Exception
{
    protected $message = "Page not found";
    protected $code = 404;
}