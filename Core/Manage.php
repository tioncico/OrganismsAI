<?php
/**
 * Created by PhpStorm.
 * User: tioncico
 * Date: 18-10-16
 * Time: 下午7:56
 */

namespace Core;


class Manage
{
    protected $map;
    protected $AI;

    public function __construct($w, $h)
    {
        $this->map = new Map($w, $h);
        $this->AI  = new AI($this->map);
    }

    public function initData($data)
    {
        foreach ($data as $key => $value) {
            $coordinate = explode('_', $key);
            foreach ($value as $level => $obj_arr) {
                switch ($obj_arr['type']) {
                    case SysConst::MAP_OBJ_ROAD:
                        $map_obj = new Road($obj_arr);
                        $this->map->setMapObj($coordinate[0], $coordinate[1], $map_obj);
                        break;
                    case SysConst::MAP_O:
                        $map_obj = new Organisms($obj_arr);
                        $this->map->setMapObj($coordinate[0], $coordinate[1], $map_obj);
                        break;
                    case SysConst::MAP_OBJ_FOOD:
                        $map_obj = new Food($obj_arr);
                        $this->map->setMapObj($coordinate[0], $coordinate[1], $map_obj);
                        break;
                }
            }
        }
        return true;
    }

    public function getMap()
    {
        return $this->map;
    }

    public function getAI()
    {
        return $this->AI->init($this->map);
    }

    /**
     * 生成地图
     */
    public function generateMap()
    {
        $wh = $this->map->getWH();
        for ($i = 0; $i < $wh[0] * $wh[1]; $i++) {
            $x       = (int)($i % $wh[0]) + 1;
            $y       = (int)($i / $wh[1]) + 1;
            $map_obj = new Road(['coordinate' => [$x, $y]]);
            $this->map->setMapObj($x, $y, $map_obj);
            $map_obj->init($this->map, $this->AI);
        }
    }

    /**
     * 随机生成生物
     * @param $num
     */
    public function randOrganisms($num)
    {
        $this->randMapObj($num, Organisms::class);
    }

    public function randFood($num)
    {
        $this->randMapObj($num, Food::class);
    }

    public function randMapObj($num, $mapObj)
    {
        $coordinates = $this->map->getMaps();
        $array       = array_rand($coordinates, $num);
        foreach ($array as $coordinate_key) {
            $coordinate = explode('_', $coordinate_key);
            $map_obj    = new $mapObj(['coordinate' => $coordinate]);
            $this->map->setMapObj($coordinate[0], $coordinate[1], $map_obj);
            $map_obj->init($this->map, $this->AI);
        }
    }

    public function ergodicObj()
    {
        foreach ($this->map->getMaps() as $key => $value) {
            foreach ($value as $level => $obj_arr) {
                switch ($obj_arr->getProperty('type')) {
                    case SysConst::MAP_OBJ_ROAD:
                        break;
                    case SysConst::MAP_O:
                        $this->AI->organismsAi($obj_arr);
                        break;
                    case SysConst::MAP_OBJ_FOOD:
                        break;
                }
            }
        }
    }
}