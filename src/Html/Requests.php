<?php
namespace App\Html;

use App\Repositories\CountyRepository;

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
                $resourceId = self::getResourceId();
                $code = 200;
                $entities = $db->getAll();
                if (empty($entities)) {
                    $code = 404;
                }
                if ($resourceId != 0) {
                    $entity = $db->getOneById($resourceId);
                    Response::response($entity, $code); 
                    break;
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
        $resourceName = self::getResourceName();
        switch ($resourceName) {
            case 'counties':
                $db = new CountyRepository();
                $resourceId = self::getResourceId();
                $code = 204;
                if (!$resourceId) {
                    $code = 404;
                }
                $deletedEntity = $db->deleteById($resourceId);
                $data = [];
                Response::response($data,$code); 
                break;
            default:
                Response::response([], 404, $_SERVER['REQUEST_URI'] . " not found");
                break;
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

    private static function getResourceId(): int 
    {
        $arrUri = self::getArrUri($_SERVER['REQUEST_URI']);
        $result = 0;
        if (is_numeric($arrUri[count(value: $arrUri) - 1])) {
            $result = $arrUri[count(value: $arrUri) - 1];
        }

        return $result;
    }

    private static function getArrUri(string $requestUri): ?array
    {
        return explode("/", $requestUri) ?? null;
    }


}

