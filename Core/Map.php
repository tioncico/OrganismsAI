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
        if ($coordinate[0] <=0) {
            $coordinate[0] = 1;
        } elseif ($coordinate[0] > $this->w) {
            $coordinate[0] = $this->w;
        }

        if ($coordinate[1] <=0) {
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
        foreach ($this->map as $key => $obj_levels) {
            if (($i) % $this->w == 0) {
                echo "\n";
            }
            krsort($obj_levels);
            $value = current($obj_levels);
            if ($value instanceof Road) {
                echo " [] ";
            } elseif ($value instanceof Organisms) {
                echo " -- ";
            } else {
                echo " () ";
            }
            $i++;
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

}