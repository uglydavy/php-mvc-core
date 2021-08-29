<?php
/**
 * @author kv.kn <aknk.v@protonmail.ch>
 * @product StudentLife
 * @package uglydavy\phpmvc\exception
 */

namespace uglydavy\phpmvc\exception;

use Exception;

class ForbiddenException extends Exception
{
    protected $message = "You don't have permission to access this page";
    protected $code = 403;
}