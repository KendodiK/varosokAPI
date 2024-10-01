<?php
namespace App\Html;

use App\Repositories\BaseRepository;
use App\Repositories\CountyRepository;
use App\Repositories\CityRespository;

class Requests 
{
    static function handle(): void 
    {
        switch ($_SERVER["REQUEST_METHOD"]) {
            case "GET":
                self::getRequest();
                break;
            case "DELETE":
                self::deleteRequest();
                break;
            case "POST":
                self::postRequest();
                break;
            case "PUT":
                self::putRequest();
                break;
            default:
                echo 'Unknown requset type';
                break;
        }
    }

    private static function getRequest(): void 
    {
        $resourceName = self::getResourceName();
        switch ($resourceName) {
            case 'counties':
                $db = new CountyRepository();
                $resourceId = self::getResourceId((int)1);
                $code = 200;
                $entities = $db->getAll();
                if (empty($entities)) {
                    $code = 404;
                }
                if ($resourceId != 0) {
                    $entity = $db->getOneById($resourceId);
                    if(empty($entity)){
                        $code = 404;
                    }
                    Response::response($entity, $code); 
                    break;
                }
                Response::response($entities, $code);
                break;
            case 'cities':
                $dbCity = new CityRespository();
                $resourceId = self::getResourceId( (int)1);
                $code = 200;
                $countyId = self::getResourceId((int)2);
                $entities = $dbCity->getCityByCountyId($countyId);
                if ($resourceId != 0) {
                    $entity = $dbCity->getOneById($resourceId);
                    if(empty($entity)){
                        $code = 404;
                    }
                    Response::response($entity, $code); 
                    break;
                }
                if (empty($entities)) {
                    $code = 404;
                }
                Response::response($entities, $code);
                break;
            default:
                Response::response( [], 404, $_SERVER['REQUEST_URI'] . " not found");
                break;
        }
    }

    private static function deleteRequest(): void  
    {
        $id = self::getResourceId((int)1);
        if(!$id){
            Response::response([], "404", "not found");
        }
        $resourceName = self::getResourceName();
        switch ($resourceName) {
            case 'counties':
                $db = new CountyRepository();
                $resourceId = self::getResourceId((int)1);
                $code = 204;
                if (!$resourceId) {
                    $code = 404;
                }
                $deletedEntity = $db->deleteById($resourceId);
                $data = [];
                Response::response($data,$code,$deletedEntity); 
                break;
            case 'cities':
                $db = new CityRespository();
                $resourceId = self::getResourceId((int)1);
                $code = 204;
                if (!$resourceId) {
                    $code = 404;
                }
                $deletedEntity = $db->deleteById($resourceId);
                $data = [];
                Response::response($data,$code,$deletedEntity); 
                break;
            default:
                Response::response([], 404, $_SERVER['REQUEST_URI'] . " not found");
                break;
        }
    }

    private static function postRequest()
    {
        $resourceName = self::getResourceName();
        switch ($resourceName) {
            case 'counties':
                $data = self::getRequestData();
                if (isset($data['name'])) {
                    $db = new CountyRepository();
                    $newId = $db->create($data);
                    $code = 201;
                    if (!$newId) {
                        $code = 404; //bad request
                    }
                }
                Response::response(['id' => $newId], $code);
                break;
            case 'cities':
                $data = self::getRequestData();
                if (isset($data['city'])) {
                    $db = new CityRespository();
                    $newId = $db->create($data);
                    $code = 201;
                    if (!$newId) {
                        $code = 404; //bad request
                    }
                }
                Response::response(['id' => $newId], $code);
                break;
            default:
                Response::response([], 404, $_SERVER['REQUEST_URI'] . " not found");
                break;
        }
    }

    private static function putRequest()
    {
        $id = self::getResourceId((int)1);
        if (!$id) {
            Response::response([], 400, Response::STATUSES[400]);
            return;
        }
        $resourceName = self::getResourceName();
        switch ($resourceName) {
            case 'counties':
                $data = self::getRequestData();
                $db = new CountyRepository();
                $entity = $db->getOneById($id);
                $code = 404;
                if($entity) {
                    $result = $db->update($id, ['name' => $data['name']]);
                    if($result) {
                        $code = 201;
                    }
                }
                Response::response([], $code);
                break;
            default;
                Response::response([], 404, $_SERVER['REQUEST_URI']);
        }
    }

    private static function getResourceName(): string
    {
        $arrUri = self::getArrUri($_SERVER['REQUEST_URI']);
        $result = $arrUri[count($arrUri) - 1];
        if (is_numeric($result)) {
            $result = $arrUri[count($arrUri) - 2];
        }

        return $result;
    }

    private static function getResourceId($back): int 
    {
        $arrUri = self::getArrUri($_SERVER['REQUEST_URI']);
        $result = 0;
        if (is_numeric($arrUri[count(value: $arrUri) - $back])) {
            $result = $arrUri[count(value: $arrUri) - $back];
        }

        return $result;
    }

    private static function getArrUri(string $requestUri): ?array
    {
        return explode("/", $requestUri) ?? null;
    }

    private static function getRequestData(): ?array
    {
        return json_decode(file_get_contents("php://input"), true);
    }
}

