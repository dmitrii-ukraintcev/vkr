<?php

namespace src;

use mysqli;

abstract class Entity
{
    public int $id;
    protected $dbConn;
    protected static string $tableName;
    protected array $fields = [];
    protected array $primaryKeys = ['id'];

    abstract protected function initFields(): void;

    protected function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
        $this->initFields();
    }

    public static function add(mysqli $dbConn, $data): static
    {
        $className = static::class;

        $object = new $className($dbConn);
        $object->setFieldValues((array) $data);
        $object->insert();

        return $object;
    }

    public function update(mixed $data): void
    {
        $this->setFieldValues((array) $data);
        $this->save();
    }

    public static function getAll(mysqli $dbConn, array $conditions = []): array
    {
        return static::getObjects($dbConn, $conditions);
    }

    public static function getByField(mysqli $dbConn, string $fieldName, $fieldValue): ?static
    {
        $objects = static::getObjects($dbConn, [$fieldName => $fieldValue]);
        return $objects[0] ?? null;
    }

    protected function setFieldValues(array $data): void
    {
        foreach ($this->primaryKeys as $keyName) {
            if (array_key_exists($keyName, $data)) {
                $this->$keyName = $data[$keyName];
            }
        }

        foreach ($this->fields as $fieldName) {
            if (array_key_exists($fieldName, $data)) {
                $this->$fieldName = $data[$fieldName];
            }
        }
    }

    protected function insert(): void
    {
        $parameterValues = [];
        $parameterTypes = '';

        foreach ($this->fields as $field) {
            $parameterValues[] = $this->$field;
            $parameterTypes .= self::getType($this->$field);
        }

        $fieldNames = implode(', ', $this->fields);
        $parameters = implode(', ', array_fill(0, count($this->fields), '?'));

        $sql = "INSERT INTO " . static::$tableName . " ($fieldNames) VALUES ($parameters)";

        $stmt = $this->dbConn->prepare($sql);
        $stmt->bind_param($parameterTypes, ...$parameterValues);
        $stmt->execute();

        $this->id = $this->dbConn->insert_id;

        $stmt->close();
    }

    protected function save(): void
    {
        $fieldBindings = [];
        $keyBindings = [];
        $parameterValues = [];
        $parameterTypes = '';

        foreach ($this->fields as $field) {
            $fieldBindings[] = $field . ' = ?';
            $parameterValues[] = $this->$field;
            $parameterTypes .= self::getType($this->$field);
        }

        foreach ($this->primaryKeys as $key) {
            $keyBindings[] = $key . ' = ?';
            $parameterValues[] = $this->$key;
            $parameterTypes .= self::getType($this->$key);
        }

        $fieldBindingsString = implode(', ', $fieldBindings);
        $keyBindingsString = implode(' AND ', $keyBindings);

        $sql = "UPDATE " . static::$tableName . " SET $fieldBindingsString WHERE $keyBindingsString";

        $stmt = $this->dbConn->prepare($sql);
        $stmt->bind_param($parameterTypes, ...$parameterValues);
        $stmt->execute();

        $stmt->close();
    }

    public function delete(): void
    {
        $keyBindings = [];
        $parameterValues = [];
        $parameterTypes = '';

        foreach ($this->primaryKeys as $key) {
            $keyBindings[] = $key . ' = ?';
            $parameterValues[] = $this->$key;
            $parameterTypes .= self::getType($this->$key);
        }

        $keyBindingsString = join(' AND ', $keyBindings);

        $sql = "DELETE FROM " . static::$tableName . " WHERE $keyBindingsString";

        $stmt = $this->dbConn->prepare($sql);
        $stmt->bind_param($parameterTypes, ...$parameterValues);
        $stmt->execute();

        $stmt->close();
    }

    private static function getData(mysqli $dbConn, array $conditions = []): array
    {
        $sql = "SELECT * FROM " . static::$tableName;
        $fieldBindings = [];
        $types = '';
        $values = [];

        if ($conditions) {
            foreach ($conditions as $fieldName => $fieldValue) {
                $fieldBindings[] = "$fieldName = ?";
                $types .= self::getType($fieldValue);
                $values[] = $fieldValue;
            }
            $sql .= ' WHERE ' . implode(' AND ', $fieldBindings);
        }

        $stmt = $dbConn->prepare($sql);

        if ($conditions) {
            $stmt->bind_param($types, ...$values);
        }

        $stmt->execute();
        $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        return $data;
    }

    private static function getObjects(mysqli $dbConn, array $conditions = []): array
    {
        $data = static::getData($dbConn, $conditions);
        $objects = [];

        if ($data) {
            $className = static::class;
            foreach ($data as $objectData) {
                $object = new $className($dbConn);
                $object->setFieldValues($objectData, true);
                $objects[] = $object;
            }
        }

        return $objects;
    }

    private static function getType($value): string
    {
        if (is_null($value)) {
            return 's';
        } elseif (is_int($value)) {
            return 'i';
        } elseif (is_float($value)) {
            return 'd';
        } elseif (is_string($value)) {
            return 's';
        } else {
            return 'b';
        }
    }
}
