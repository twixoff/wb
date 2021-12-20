<?php

namespace app;

/**
 * The example DB class
 */
class DB
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Find row(s). Return rows array;
     * @param string $tableName
     * @param array $condition
     * @return array
     */
    public function find(string $tableName, array $condition = []): array
    {
        // Only for test
        if (!empty($condition) && $condition['id'] === 77) {
            return [];
        }

        return [
            'id' => 5,
            'name' => 'Alena',
            'email' => 'alenka@super.com',
            'created' => '2021-05-01 12:45:45',
            'deleted' => null,
            'notes' => null,
        ];
    }

    /**
     * Insert new row. Return true|false or database exception.
     * @param string $tableName
     * @param array $params
     * @return bool|Exception
     */
    public function insert(string $tableName, array $params): bool
    {
        return true;
    }

    /**
     * Update row by condition. Return true|false or database exception.
     * @param string $tableName
     * @param array $condition
     * @param array $params
     * @return bool|Exception
     */
    public function update(string $tableName, array $condition, array $params): bool
    {
        return true;
    }

    /**
     * Delete row. Return true|false or database exception.
     * @param string $tableName
     * @param array $condition
     * @return bool|Exception
     */
    public function delete(string $tableName, array $condition): bool
    {
        return true;
    }

}