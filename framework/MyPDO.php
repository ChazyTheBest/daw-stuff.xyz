<?php

namespace framework;

use PDO;

class MyPDO
{
    private static ?PDO $pdo = null;

    // use the same instance across runtime
    public static function getPDO(): PDO
    {
        if (!self::$pdo)
        {
            $pdo = App::$config['pdo'];
            self::$pdo = new PDO($pdo['dsn'], $pdo['user'], '', $pdo['options']);
        }

        return self::$pdo;
    }
}
