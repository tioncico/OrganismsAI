<?php
/**
 * Created by PhpStorm.
 * User: tioncico
 * Date: 18-10-14
 * Time: 下午9:55
 */

namespace Core;


class Map
{
    protected $w, $h;
    protected $map = [];

    public function __construct($w = 10, $h = 10)
    {
        $this->w = $w;
        $this->h = $h;
        $this->initMap();
    }

    public function initMap()
    {
        $map_w_num = range(1, $this->w);
        $map_h_num = range(1, $this->h);
        foreach ($map_w_num as $x) {
            foreach ($map_h_num as $y) {
                $this->map["{$x}_{$y}"] = [];
            }
        }
    }

    public function getWH()
    {
        return [$this->w, $this->h];
    }

    /**
     * 计算距离
     * @param $coordinate_1
     * @param $coordinate_2
     */
    public function countDistance($coordinate_1, $coordinate_2)
    {
        $distance = abs(($coordinate_1[0] + $coordinate_1[1]) - ($coordinate_2[0] + $coordinate_2[1]));
        return $distance;
    }

    /**
     * 判断并返回正确的坐标
     * @param $coordinate_1
     * @param $coordinate_2
     */
    public function checkCoordinate(array $coordinate)
    {
//        var_dump($coordinate);
        if ($coordinate[0] <= 0) {
            $coordinate[0] = 1;
        } elseif ($coordinate[0] > $this->w) {
            $coordinate[0] = $this->w;
        }

        if ($coordinate[1] <= 0) {
            $coordinate[1] = 1;
        } elseif ($coordinate[1] > $this->h) {
            $coordinate[1] = $this->h;
        }
        return $coordinate;
    }

    /**
     * 获取坐标对象
     * @param $x
     * @param $y
     */
    public function getObj($x, $y)
    {
        return $this->map[$x . '_' . $y];
    }

    /**
     * 获取该区域所有对象
     * @param $coordinate_1
     * @param $coordinate_2
     */
    public function getObjs($coordinate_1, $coordinate_2)
    {
        $array = [];

        for ($x = $coordinate_1[0]; $x <= $coordinate_2[0]; $x++) {
            for ($y = $coordinate_1[1]; $y <= $coordinate_2[1]; $y++) {
                $array["{$x}_{$y}"] = $this->map["{$x}_{$y}"];
            }
        }
        return $array;
    }

    public function countCoordinatePosition($coordinate)
    {
        return (intval($coordinate[1] - 1) * $this->h) + $coordinate[0] - 1;
    }

    public function mapToString()
    {
        $i = 0;
        for ($y = 1; $y <= $this->h; $y++) {
            for ($x = 1; $x <= $this->w; $x++) {
                $objs = $this->getObj($x, $y);
                krsort($objs);
                $obj = current($objs);
                if ($obj instanceof Road) {
                    echo " [] ";
                } elseif ($obj instanceof Organisms) {
                    echo " -- ";
                } else {
                    echo " () ";
                }
            }
            echo "\n";
        }
    }

    /**
     * 设置地图对象
     * @param $x
     * @param $y
     * @param $class_name
     */
    public function setMapObj(int $x, int $y, MapObj $class)
    {
        $this->map["{$x}_{$y}"][$class::$level] = $class;
        return $class;
    }

    public function getMaps()
    {
        return $this->map;
    }

    /**
     * 移动地图对象
     * @param int $move_type
     * @param $num
     * @param MapObj $map_obj
     */
    public function moveMapObj(int $move_type, $num, MapObj $map_obj)
    {
        $coordinate = $map_obj->getProperty('coordinate');
//        var_dump($coordinate);
        switch ($move_type) {
            case SysConst::MOVE_TOP:
                $coordinate[1] -= 1;
                break;
            case SysConst::MOVE_BOTTOM:
                $coordinate[1] += 1;
                break;
            case SysConst::MOVE_LEFT:
                $coordinate[0] -= 1;
                break;
            case SysConst::MOVE_RIGHT:
                $coordinate[0] += 1;
                break;
        }
        if ($coordinate[0] > $this->w || $coordinate[0] <= 0 || $coordinate[1] > $this->h || $coordinate[1] <= 0) {
            throw new \Exception('坐标错误!');
        }
        //获取地图坐标的对象
        $map_obj_arr = $this->getObj($coordinate[0], $coordinate[1]);
//        var_dump($coordinate);
        if (isset($map_obj_arr[$map_obj->getProperty('level')])) {
            $this->impact($map_obj, $map_obj_arr[$map_obj->getProperty('level')]);
        }else{
            //先删除地图的该对象
            $this->destroyMapObj($map_obj);
            $map_obj->addProperty('coordinate',$coordinate);
            //移动对象
            $this->setMapObj($coordinate[0], $coordinate[1], $map_obj);
        }
        return $coordinate;
    }

    public function destroyMapObj(MapObj $mapObj)
    {
        unset($this->map[$mapObj->coordinateToString()][$mapObj->getProperty('type')]);
    }

    /**
     * 碰撞效果
     */
    public function impact(MapObj $mapObj, MapObj $target)
    {
        var_dump($target->getProperty('type'));
        switch ($target->getProperty('type')){
            case SysConst::MAP_OBJ_ROAD:
                break;
            case SysConst::MAP_OBJ_O://如果是同类碰撞,则双方减少一格生命
                $mapObj->setLife(-1);
                $target->setLife(-1);
                break;
            case SysConst::MAP_OBJ_FOOD://销毁该食物,增加一个血,前进到该位置
                $mapObj->setLife(1);
                $this->destroyMapObj($target);
                $coordinate = $target->getProperty('coordinate');
                //移动对象
                $this->setMapObj($coordinate[0], $coordinate[1], $mapObj);
                var_dump(1);
                break;
        }
    }
}