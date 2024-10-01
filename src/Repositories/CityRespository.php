<?php
namespace App\Repositories;

use App\Database\DB;

class CityRespository extends BaseRepository
{
    function __construct($host = DB::HOST, $user = DB::USER, $password = DB::PASSWORD, $database = DB::DATABASE) {
        parent::__construct(host: $host, user: $user, password: $password, database: $database);
        $this->tableName = 'cities';
    }

    public function getCityByCountyId($countyId): array 
    {
        $query = "SELECT * FROM  `{$this->tableName}` WHERE id_county = {$countyId}";

        return $this->mysqli->query( $query)->fetch_all( MYSQLI_ASSOC);    
    }
}