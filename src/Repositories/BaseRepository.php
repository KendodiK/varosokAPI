<?php
namespace App\Repositories;

use App\Database\DB;

class BaseRepository extends DB
{
    protected string $tableName;

    public function getAll(): array
    {
        $query = $this->select() . "ORDER BY name";

        return $this->mysqli->query(query: $query)->fetch_all(mode: MYSQLI_ASSOC);
    }

    public function select(): string
    {
        return "SELECT * FROM '{$this->tableName}' ";
    }
}