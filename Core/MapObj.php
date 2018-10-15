<?php
/**
 * Created by PhpStorm.
 * User: tioncico
 * Date: 18-10-15
 * Time: 下午9:31
 */

namespace Core;


class MapObj
{
    protected $coordinate = [0, 0];//当前坐标

    protected $level = 0;

    protected $map;

    protected $AI;

    public function __construct(int $x,int $y,map $map)
    {
        $this->coordinate= [$x,$y];
        $this->map =$map;
        $this->AI = AI::getInstance($map);
        static::init();
    }

    public function getCoordinate(){
        return $this->coordinate;
    }

    public function coordinateToString(){
        return $this->coordinate[0].'_'.$this->coordinate[1];
    }

    public function init(){

    }

}