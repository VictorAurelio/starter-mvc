<?php

namespace App\Core\Database\QueryBuilder;

interface QueryBuilderInterface {

    public function insertQuery(): string;
    public function selectQuery(): string;
    public function updateQuery(): string;
    public function deleteQuery(): string;
    public function rawQuery(): string;

}