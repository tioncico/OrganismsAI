<?php
/**
 * Created by PhpStorm.
 * User: tioncico
 * Date: 18-10-14
 * Time: 下午9:53
 */

namespace App\Controller;


use Core\Controller;
use Core\Manage;
use Core\Map;
use Core\Organisms;

class Index extends Controller
{
    function index(){
        ini_set('memory_limit','3072M');
        $manage = new Manage(10,10);
        $manage->generateMap();
        $manage->getMap()->setMapObj(1,1,new Organisms(['coordinate' => [1,1]]));
        $manage->getMap()->setMapObj(10,10,new Organisms(['coordinate' => [10,10]]));
        $manage->getMap()->setMapObj(1,10,new Organisms(['coordinate' => [1,10]]));
        $manage->getMap()->setMapObj(10,1,new Organisms(['coordinate' => [10,1]]));
        $manage->getMap()->setMapObj(5,5,new Organisms(['coordinate' => [5,5]]));
        $manage->getMap()->setMapObj(4,8,new Organisms(['coordinate' => [4,8]]));
        $manage->randFood(5);

        file_put_contents('map.json',json_encode($manage->getMap()->getMaps()));
        $manage->getMap()->mapToString();
    }

    function action(){
        ini_set('memory_limit','3072M');
        $map_data = json_decode(file_get_contents('map.json'),1);
        $manage = new Manage(10,10);
        $manage->initData($map_data);
//        var_dump($manage->getMap()->getMaps());
        $manage->ergodicObj();
//        var_dump(json_encode($manage->getMap()->getMaps()));
        file_put_contents('map.json',json_encode($manage->getMap()->getMaps()));
        $manage->getMap()->mapToString();
    }


}