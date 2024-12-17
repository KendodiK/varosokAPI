<?php
namespace App\Html;

/**
 * @author Endrődi Kálmán
 */

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

    /**
     * Summary of getCounties
     * @api {get} /counties Get list of counties
     * @apiGroup Counties
     * @apiName getCounties
     * @apiVersion 1.0.0
     * 
     * @apiSuccessExample {json} Success-Response:
     *      HTTP/1.1 200
     *      {
     *        "data": [
     *           {
     *            "id": "3",
     *            "name": "Baranya"
     *           }, ],
     *         "message": "OK",
     *         "code": 200
     *      }
     */
    private static function getCounties() {}

     /**
     * Summary of getCities
     * @api {get} /counties/:idCounty/cities Get list of cities
     * @apiParam {Number} idCounty Unique county id
     * @apiGroup Cities
     * @apiVersion 1.0.0
     * @apiName getCities
     * 
     * @apiSuccessExample {json} Success-Response:
     *      HTTP/1.1 200
     *      {
     *        "data": [
     *           {
     *              "id": "251",
     *              "zip_code": "5830",
     *              "city": "Battonya",
     *              "id_county": "4"
     *           }, ],
     *         "message": "OK",
     *         "code": 200
     *      }
     */
    private static function getCities() {}

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
                if($countyId == null){
                    $entities = $dbCity->getAll();
                }
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

    /**
     * @api {delete} /counties/:id Delete county by id
    * @apiParam {Number} id County unique ID
    * @apiName delete
    * @apiGroup Counties
    * @apiVersion 1.0.0
    *
    * @apiSuccessExample {json} Success-Response:
    *          HTTP/1.1 204 No content
    *          {
    *              "data":[],
    *              "message":"No content",
    *              "code":204
    *          }
    *
    */
    
    private static function deleteCounty() {}

    /**
     * @api {delete} /counties/:idCounty/cities/:id Delete city by id
    * @apiParam {Number} idCounty County unique ID
    * @apiParam {Number} id City unique ID
    * @apiName delete
    * @apiGroup Cities
    * @apiVersion 1.0.0
    *
    * @apiSuccessExample {json} Success-Response:
    *          HTTP/1.1 204 No content
    *          {
    *              "data":[],
    *              "message":"No content",
    *              "code":204
    *          }
    *
    */   

    private static function deleteCity(){}

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

    /**
    * @api {post} /counties Create new county
    * @apiName post
    * @apiGroup Counties
    * @apiVersion 1.0.0
    *
    * @apiSuccessExample {json} Success-Response:
    *          HTTP/1.1 201 No content
    *          {
    *              "data":[],
    *              "message":"No content",
    *              "code":204
    *          }
    *
    */

    private static function postCounty(){}

    /**
    * @api {post} /countyes/:idCounty/cities Create new city
    * @apiName post
    * @apiParam idCounty County unique ID
    * @apiGroup Cities
    * @apiVersion 1.0.0
    *
    * @apiSuccessExample {json} Success-Response:
    *          HTTP/1.1 201 No content
    *          {
    *              "data":[],
    *              "message":"No content",
    *              "code":204
    *          }
    *
    */

    private static function postCity(){}

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

      /**
      * @api {put} /counties/:id
      * @apiParam {Number} id County unique ID
      * @apiName put
      * @apiGroup Counties
      * @apiVersion 1.0.0
      *
      * @apiSuccessExample {json} Success-Response:
      *          HTTP/1.1 204 No content
      *          {
      *              "data":[],
      *              "message":"No content",
      *              "code":204
      *          }
      */

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
            case 'cities':
                $data = self::getRequestData();
                $db = new CityRespository();
                $entity = $db->getOneById($id);
                $code = 404;
                if($entity) {
                    $result = $db->update($id, ['city' => $data['name'], 'zip_code' => $data['zip-code'], 'id_county' => $data['county-id']]);
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

     /**
      * @api {get} /user/counties/{id} Get one county by id
      * @apiName get_by_id
      * @apiGroup Counties
      * @apiVersion 1.0.0
      * 
      * @apiSuccess {Object[]} City      List of city properties.
      * @apiSuccess {Number} counties.id     County id.
      * @apiSuccess {String} counties.name   County name.
      * 
      * @apiSuccessExample {json} Success-Response:
      *          HTTP/1.1 200 OK
      *          {
      *              "data":[
      *                  {"id":4, "name": "Békés"},
      *              ],
      *              "message":"OK",
      *              "code":200
      *          }
      * 
      * @apiError {json} Object not found.
      *          HTTP/1.1 404 Not Found
      *          {
      *              "data": [],
      *              "message": "not found",
      *              "code: 404
      *          }
      */

    private static function getResourceId($back): int 
    {
        $arrUri = self::getArrUri($_SERVER['REQUEST_URI']);
        $result = 0;
        if (is_numeric($arrUri[count(value: $arrUri) - $back])) {
            $result = $arrUri[count(value: $arrUri) - $back];
        }

        return $result;
    }

    /**
    * @api {get} /counties/:idCounty/cities/:id Get one city by id
    * @apiParam {Number} idCounty County id, can be any county id
    * @apiParam {Number} id City unique ID
    * @apiName get
    * @apiGroup Cities
    * @apiVersion 1.0.0
    *
    * @apiSuccessExample {json} Success-Response:
    *          HTTP/1.1 200 OK
    *           {
    *               "data": [
    *               {
    *               "id": "55",
    *               "zip_code": "3717",
    *               "city": "Alsódobsza",
    *               "id_county": "5"
    *               }
    *             ],
    *               "message": "OK",
    *               "code": 200
    *           }
    *
    */   

    private static function getCityId(){}

    private static function getArrUri(string $requestUri): ?array
    {
        return explode("/", $requestUri) ?? null;
    }



    

    private static function getRequestData(): ?array
    {
        return json_decode(file_get_contents("php://input"), true);
    }
}

