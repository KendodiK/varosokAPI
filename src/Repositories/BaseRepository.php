<?php
namespace App\Repositories;

use App\Database\DB;

class BaseRepository extends DB
{
    protected string $tableName;

    public function getAll(): array
    {
        $query = "SELECT * FROM  `{$this->tableName}` ORDER BY name";

        return $this->mysqli->query(query: $query)->fetch_all(mode: MYSQLI_ASSOC);
    }

}