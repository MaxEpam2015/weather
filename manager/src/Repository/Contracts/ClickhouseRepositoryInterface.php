<?php

namespace App\Repository\Contracts;


interface ClickhouseRepositoryInterface
{
    /**
     * @param string $table
     */
    public function createTable(string $table);


    /**
     * @param $table
     * @return mixed
     */
    public function select($table);

    /**
     * @return mixed
     */
    public function executeAsync();

    /**
     * @param $table
     * @param array $values
     * @param array $fields
     * @return mixed
     */
    public function insert($table, array $values, array $fields);
}