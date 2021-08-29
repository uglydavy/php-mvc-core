<?php
/**
 * @author kv.kn <aknk.v@protonmail.ch>
 * @product StudentLife
 * @package app\core
 */

namespace app\core;

use app\core\db\DBModel;

abstract class UserModel extends DBModel
{
    abstract public function getDisplayName (): string;
}