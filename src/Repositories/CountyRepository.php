<?php
namespace App\Repositories;

class CountyRepository extends BaseRepository
{
    function __construct($host = App\Database\DB::HOST, $user = App\Database\DB::USER, $password = App\Database\DB::PASSWORD, $database = App\Database\DB::DATABASE) {
        parent::__construct(host: $host, user: $user, password: $password, database: $database);
        $this->tableName = 'counties';
    }

}