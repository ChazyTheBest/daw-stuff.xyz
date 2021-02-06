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
    private array $updateCond;

    public function __construct()
    {
        $this->pdo = MyPDO::getPDO();
        $this->tableName = static::tableName();
        $this->updateCond = static::updateCond();
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
            $name = $property->getName();
            // never insert/update id or update conditions nor null properties
            if ($name === 'id' || (!$this->new && in_array($name, $this->updateCond[1])) || !$property->getValue($this))
                continue;

            if ($this->new)
            {
                $query .= '`' . $name . '`, ';
                $values .= '?, ';
            }

            else
            {
                $query .= '`' . $name . '` = ?, ';
            }

            $params[] = $property->getValue($this);
        }

        $query = substr($query, 0, -2);

        if ($this->new)
        {
            $values = substr($values, 0, -2);
            $query .= "$values)";
        }

        else if ($this->tableName === $this->updateCond[0])
        {
            $query .= ' WHERE ';

            foreach ($this->updateCond[1] as $col)
            {
                $query .= "`$col` = ? AND ";
                $params[] = $this->$col;
            }

            $query = substr($query, 0, -5);
        }

        $stmt = $this->pdo->prepare($query);
        if (!$stmt->execute($params))
            return false;

        if (property_exists($this, 'id'))
            $this->id = $this->pdo->lastInsertId();

        return true;
    }

    public function delete(array $cond): void
    {
        $query = "DELETE FROM `$this->tableName`";

        if ($cond === [])
            return;

        $stuff = self::where($cond);

        $this->pdo->prepare($query . $stuff[0])->execute($stuff[1]);
    }

    public function close(): void
    {
        if (!$this->id)
            return;

        $this->pdo->query("UPDATE `$this->tableName` SET `status` = 0 WHERE `id` = $this->id");
    }

    protected static function count(array $cond = [], string $cols = '*'): int
    {
        $pdo = MyPDO::getPDO();
        $query = "SELECT COUNT($cols) FROM `" . static::tableName() . '`';
        $params = [];

        if ($cond !== [])
        {
            if (isset($cond['build']) && is_array($cond['build']))
            {
                $query .= $cond['build'][0];
                $params = $cond['build'][1];
            }

            else
            {
                $stuff = self::where($cond);
                $query .= $stuff[0];
                $params = $stuff[1];
            }
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchColumn();
    }

    protected static function sum(string $cols, array $cond = []): int
    {
        $pdo = MyPDO::getPDO();
        $query = "SELECT SUM($cols) FROM `" . static::tableName() . '`';
        $params = [];

        if ($cond !== [])
        {
            $stuff = self::where($cond);
            $query .= $stuff[0];
            $params = $stuff[1];
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchColumn() ?? 0;
    }

    protected static function findOne(array $cond): ?ActiveRecord
    {
        $pdo = MyPDO::getPDO();
        $query = 'SELECT * FROM `' . static::tableName() . '`';

        if ($cond === [])
            return null;

        $stuff = self::where($cond);
        $query .= $stuff[0];
        $params = $stuff[1];

        $stmt = $pdo->prepare($query);
        $stmt->execute($params ?? []);

        if ($stmt->rowCount() !== 1)
            return null;

        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, static::class);

        $data = $stmt->fetch();
        $data->new = false;

        return $data;
    }

    protected static function findAll(array $cond = [], int $page = 0): array
    {
        $pdo = MyPDO::getPDO();
        $query = 'SELECT * FROM `' . static::tableName() . '`';

        if ($cond !== [])
        {
            $stuff = self::where($cond);
            $query .= $stuff[0];
            $params = $stuff[1];
        }

        if ($page > 0)
        {
            $total = self::count(isset($stuff[0]) ? ([ 'build' => [ $stuff[0], $stuff[1] ] ]) : []);
            $limit = App::$config['pagination']['limit'];
            $offset = ($page - 1) * $limit;
            // build final query
            $query .= " LIMIT $limit OFFSET $offset";
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params ?? []);

        if ($stmt->rowCount() < 1)
            return [];

        return $page > 0 ? [
            'total' => $total,
            'limit' => $limit,
            'pages' => ceil($total / $limit),
            'page' => $page,
            'offset' => $offset,
            'products' => $stmt->fetchAll() // fetch mode?
        ] : $stmt->fetchAll();
    }

    protected function custom(array $data, int $fetch_mode = PDO::ATTR_DEFAULT_FETCH_MODE): array
    {
        $query = '';

        if (isset($data['select']))
        {
            $query .= 'SELECT ';
            foreach ($data['select'] as $column)
            {
                $query .= "`$column`, ";
            }

            $query = substr($query, 0, -2) . " FROM `$this->tableName`";
        }

        if (isset($data['cond']) && $data['cond'] !== [])
        {
            $stuff = self::where($data['cond']);
            $query .= $stuff[0];
            $params = $stuff[1];
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params ?? []);

        if ($stmt->rowCount() < 1)
            return [];

        return $stmt->fetchAll($fetch_mode);
    }

    private static function where(array $cond): array
    {
        $where = ' WHERE ';
        $params = [];

        foreach ($cond as $key => $value)
        {
            if (is_array($value))
            {
                $where .= '`' . $key . '` IN(';

                for ($i = 0; $i < count($value); $i++)
                {
                    $where .= '?,';
                    $params[] = $value[$i];
                }

                $where = substr($where, 0, -1) . ')';
            }

            else
            {
                $where .= '`' . $key . '` = ?';
                $params[] = $value;
            }

            $where .= ' AND ';
        }

        return [ substr($where, 0, -5), $params ];
    }

    public static abstract function tableName(): string;
    public static function updateCond(): array
    {
        return [ '', [] ];
    }
}
