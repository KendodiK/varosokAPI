<?php
namespace App\Repositories;

use App\Database\DB;

class CountyRepository extends BaseRepository
{
    function __construct($host = DB::HOST, $user = DB::USER, $password = DB::PASSWORD, $database = DB::DATABASE) {
        parent::__construct(host: $host, user: $user, password: $password, database: $database);
        $this->tableName = 'counties';
    }

}