<?php

namespace framework;

use PDO;

abstract class ActiveQuery
{
    private PDO $pdo;
    private string $tableName;

    public function __construct()
    {
        $this->pdo = MyPDO::getPDO();
        $className = str_replace('Query', '', get_parent_class(self::class));
        $this->tableName = call_user_func("$className::tableName");
    }

    protected function where(array $cond): array
    {
        $query = "SELECT * FROM $this->tableName WHERE ";

        if (is_array($cond[0]))
        {
            $query .= '`' . key($cond) . '` IN(';

            for ($i = 0; $i < count($cond[0]); $i++)
            {
                $query .= '?,';
            }

            $query = substr($query, 0, -1) . ')';
        }

        else
        {
            $query .= '`' . key($cond) . '` = ?';
            $cond[0] = [ $cond[0] ];
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($cond[0]);

        return $stmt->fetchAll();
    }
}
