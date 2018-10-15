<?php
/**
 * Created by PhpStorm.
 * User: tioncico
 * Date: 18-10-14
 * Time: 下午9:53
 */

namespace App\Controller;


use Core\Controller;
use Core\Map;
use Core\Organisms;

class Index extends Controller
{
    function index(){
        $map = new Map(100,100);
        $map->setMapObj(1,1,Organisms::class);
        $map->setMapObj(3,1,Organisms::class);
        $map->setMapObj(1,9,Organisms::class);
        $map->setMapObj(8,6,Organisms::class);
        $map->setMapObj(3,100,Organisms::class);
        $map->mapToString();
//        var_dump($map);
    }


}