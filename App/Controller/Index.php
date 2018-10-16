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
//        $map->setMapObj(3,100,Organisms::class);
        $manage->generateMap();
        $manage->getMap()->mapToString();
//        var_dump((memory_get_usage()/1024/1024)."M");
//        var_dump($map);
    }


}