<?php

namespace Blog\Config;

class Database
{
    private static ?\PDO $pdo = null;

    public static function getConnection(string $env = 'test')
    {
        require_once __DIR__ . "/../../config/Database.php";
        $config = getDatabaseConfig();

        if (self::$pdo == null) {
            self::$pdo = new \PDO(
                $config["database"][$env]["url"],
                $config["database"][$env]["username"],
                $config["database"][$env]["password"]
            );
        }

        return self::$pdo;
    }

    public static function beginTransaction()
    {
        self::$pdo->beginTransaction();
    }

    public static function commit()
    {
        self::$pdo->commit();
    }

    public static function rollBack()
    {
        self::$pdo->rollBack();
    } 
}