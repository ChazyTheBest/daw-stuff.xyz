<?php

namespace framework;

use PDO;
use ReflectionClass;
use ReflectionProperty;

abstract class ActiveRecord
{
    private PDO $pdo;
    private bool $new = true;
    private string $tableName;

    public function __construct()
    {
        $this->pdo = MyPDO::getPDO();
        $this->tableName = static::tableName();
    }

    // TODO: check for changes before updating
    public function save(): bool
    {
        $query = $this->new ? "INSERT INTO `$this->tableName` (" : "UPDATE `$this->tableName` SET ";
        $values = ') VALUES (';
        $params = [];

        $reflect = new ReflectionClass($this);
        $properties = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach ($properties as $property)
        {
            // never insert/update id neither empty properties
            if ($property->getName() === 'id' || $property->getName() === 'user_id' || !$property->getValue($this))
                continue;

            if ($this->new)
            {
                $query .= '`' . $property->getName() . '`, ';
                $values .= '?, ';
            }

            else
            {
                $query .= '`' . $property->getName() . '` = ?, ';
            }

            $params[] = $property->getValue($this);
        }

        $query = substr($query, 0, -2);

        if ($this->new)
        {
            $values = substr($values, 0, -2);
            $query .= "$values)";
        }

        else
        {
            // todo check relations
            if ($this->tableName === 'user_info')
            {
                $query .= ' WHERE user_id = ?';
                $params[] = App::$user->id;
            }
        }

        $stmt = $this->pdo->prepare($query);
        if (!$stmt->execute($params))
            return false;

        if (property_exists($this, 'id'))
            $this->id = $this->pdo->lastInsertId();

        return true;
    }

    public function delete(): void
    {
        if (!$this->id)
            return;

        $this->pdo->query("DELETE FROM `$this->tableName` WHERE `id` = $this->id");
    }

    public function close(): void
    {
        if (!$this->id)
            return;

        $this->pdo->query("UPDATE `$this->tableName` SET `status` = 0 WHERE `id` = $this->id");
    }

    public function hasOne(string $className, array $relations)
    {
        // has one
    }

    public function hasMany()
    {
        // has many
    }

    protected static function findOne(array $cond)
    {
        return self::find($cond);
    }

    protected static function findAll(array $cond, int $page = 0)
    {
        return self::find($cond, true, $page);
    }

    private static function find(array $cond, bool $all = false, int $page = 0)
    {
        $pdo = MyPDO::getPDO();
        $className = static::class;
        $query = 'SELECT * FROM `' . static::tableName() . '`';
        $cQuery = 'SELECT COUNT(*) FROM `' . static::tableName() . '`';
        $params = [];
        $data = null;

        if ($cond !== [])
        {
            $build = ' WHERE ';

            foreach ($cond as $key => $value)
            {
                $build .= self::where($key, $value) . ' AND ';

                if (is_array($value))
                    $params = array_merge($params, $value);

                else
                    $params[] = $value;
            }

            $build = substr($build, 0, -5);
            // build final query
            $query = $query . $build;
        }

        if ($page > 0)
        {
            $cStmt = $pdo->prepare($cQuery . $build ?? '');
            $cStmt->execute($params);
            $total = $cStmt->fetchColumn();
            // TODO: hardcoded value
            $limit = 8;
            $offset = ($page - 1) * $limit;
            // build final query
            $query = $query . " LIMIT $limit OFFSET $offset";
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        if ($all)
        {
            if ($stmt->rowCount() < 1)
                return null;

            // fetch mode?

            $data = $page > 0 ? [
                'total' => $total,
                'limit' => $limit,
                'pages' => ceil($total / $limit),
                'page' => $page,
                'offset' => $offset,
                'products' => $stmt->fetchAll()
            ] : $stmt->fetchAll();
        }

        else
        {
            if ($stmt->rowCount() !== 1)
                return null;

            $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, $className);

            $data = $stmt->fetch();
            $data->new = false;
        }

        return $data;
    }

    private static function where(string $key, $value): string
    {
        $query = '';

        if (is_array($value))
        {
            $query .= '`' . $key . '` IN(';

            for ($i = 0; $i < count($value); $i++)
            {
                $query .= '?,';
            }

            $query = substr($query, 0, -1) . ')';
        }

        else
        {
            $query .= '`' . $key . '` = ?';
        }

        return $query;
    }
}
