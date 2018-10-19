<?php
/**
 * Created by PhpStorm.
 * User: tioncico
 * Date: 18-10-15
 * Time: 下午9:31
 */

namespace Core;


use Core\Spl\SplBean;

class MapObj extends SplBean
{
    protected $coordinate = [0, 0];//当前坐标

    static $level = 0;

    protected $type = 0;

    public function coordinateToString(){
        return $this->coordinate[0].'_'.$this->coordinate[1];
    }

    public function init(AI $AI){

    }


}