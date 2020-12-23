<?php

namespace App\Repository;

use App\Repository\Contracts\ClickhouseRepositoryInterface;
use ClickHouseDB\Client;

class ClickhouseRepository implements ClickhouseRepositoryInterface
{
    const QUERY_LIMIT = 3;

    /**
     * @var Client
     */
    private $database;

    /**
     * ClickhouseRepository constructor.
     */
    public function __construct()
    {
        $config = [
            'host' => $_ENV['DB_HOST'],
            'port' => $_ENV['DB_PORT'],
            'username' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASSWORD'],
        ];
        $this->database = new Client($config);
        $this->database->database($_ENV['DB_NAME']);
    }

    /**
     * @param string $table
     */
    public function createTable(string $table)
    {
        $this->database->write("
            CREATE TABLE IF NOT EXISTS {$table} (
                event_date Date,
                city String,
                temperature Float32,
                created_at DateTime
            )
            ENGINE = MergeTree(event_date, (city), 8192)
        ");
    }

    /**
     * @param $table
     * @param array $values
     * @param array $fields
     * @return \ClickHouseDB\Statement|mixed
     */
    public function insert($table, array $values, array $fields)
    {
        return $this->database->insert($table, $values, $fields);
    }

    /**
     * @return bool
     */
    public function executeAsync()
    {
        return $this->database->executeAsync();
    }

    public function select($table)
    {
        $bindings = [
            'limit' => self::QUERY_LIMIT,
            'table' => $table
        ];

        return $this->database->selectAsync("SELECT * FROM {table} ORDER BY created_at DESC LIMIT {limit}", $bindings);
    }
}