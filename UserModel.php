<?php
/**
 * @author kv.kn <aknk.v@protonmail.ch>
 * @product StudentLife
 * @package uglydavy\phpmvc
 */

namespace uglydavy\phpmvc;

use uglydavy\phpmvc\db\DBModel;

abstract class UserModel extends DBModel
{
    abstract public function getDisplayName (): string;
}