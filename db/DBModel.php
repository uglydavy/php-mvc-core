<?php
/**
 * @author kv.kn <aknk.v@protonmail.ch>
 * @product StudentLife
 * @package app\core
 */

namespace app\core\db;

use app\core\Application;
use app\core\Model;

abstract class DBModel extends Model
{
    abstract public function tableName(): string;
    abstract public function attributes(): array;
    abstract public function primaryKey(): string;

    public function save ()
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map( function ($attr) { return ":$attr"; }, $attributes );

        $stmt = self::prepare("INSERT INTO $tableName ( ".implode(',', $attributes)." ) 
        VALUES ( ".implode(',', $params)." )");

        foreach ($attributes as $attribute)
            $stmt->bindValue( ":$attribute", $this->{$attribute} );

        $stmt->execute();

        return true;
    }

    public static function findOne ($where) // ==> [ email => email@example.com, Firstname => {PLACE_HOLDER} ]
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = implode("AND ", array_map( function ($attr) { return "$attr = :$attr"; }, $attributes ));
        $stmt = self::prepare("SELECT * FROM $tableName WHERE $sql");

        foreach ($where as $key => $item)
            $stmt->bindValue(":$key", $item);

        $stmt->execute();
        return $stmt->fetchObject(static::class);
    }

    public static function prepare ($sql)
    {
        return Application::$app->db->pdo->prepare($sql);
    }
}