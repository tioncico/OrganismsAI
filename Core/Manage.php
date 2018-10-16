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
        $this->AI  = new AI();
    }

    public function getMap(){
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
        $wh = $this->map->getWH();
        $map_w_num = range(1,$wh[0]);
        $map_h_num = range(1,$wh[1]);
        $x_arr_key = array_rand($map_w_num);
        $y_arr_key = array_rand($map_h_num);
        for ($i = 0; $i < $num; $i++) {
            $map_obj = new Road(['coordinate' => [$x, $y]]);
            $this->map->setMapObj($x, $y, $map_obj);
            $map_obj->init($this->map, $this->AI);
        }
    }

    public function randFood($num)
    {

    }


}