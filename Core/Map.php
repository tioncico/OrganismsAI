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
    use Singleton;
    protected $w, $h;
    protected $map;


    public function __construct($w, $h)
    {
        $this->w   = $w;
        $this->h   = $h;
        $this->map = new \SplFixedArray($w * $h);
//        var_dump($this->map);
        $this->generateMap();
    }

    public function generateMap()
    {
        for ($i = 0; $i < $this->w*$this->h; $i++) {
            $x = (int)($i%$this->w)+1;
            $y = (int)($i/$this->h)+1;
//            var_dump($x,$y);
//            echo $i;
//            echo "\n";
            if ($i==64){
//                var_dump($x,$y);
            }
            $this->setMapObj($x,$y,Road::class);
        }
    }


    public function setMapObj($x, $y,$class_name)
    {
        if ($this->countCoordinatePosition([$x,$y])>9999){
            echo $x." and ".$y;
            echo "result:".$this->countCoordinatePosition([$x,$y]);
            echo "\n";
            return;
        }
//        var_dump($this->countCoordinatePosition([$x,$y]));
        $this->map[$this->countCoordinatePosition([$x,$y])] = new $class_name($x,$y,$this);
    }

    public function getWH(){
        return [$this->w,$this->h];
    }

    /**
     * 计算距离
     * @param $coordinate_1
     * @param $coordinate_2
     */
    public function countDistance($coordinate_1,$coordinate_2){
        $distance = abs(($coordinate_1[0]+$coordinate_1[1])-($coordinate_2[0]+$coordinate_2[1]));
        return $distance;
    }

    /**
     * 判断并返回正确的坐标
     * @param $coordinate_1
     * @param $coordinate_2
     */
    public function checkCoordinate(array $coordinate)
    {
        if ($coordinate[0] < 0) {
            $coordinate[0] = 0;
        } elseif ($coordinate[0] > $this->w) {
            $coordinate[0] = $this->w;
        }

        if ($coordinate[1] < 0) {
            $coordinate[1] = 0;
        } elseif ($coordinate[0] > $this->h) {
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
        return $this->map[$x * $y - 1];
    }

    /**
     * 获取该区域所有对象
     * @param $coordinate_1
     * @param $coordinate_2
     */
    public function getObjs($coordinate_1, $coordinate_2)
    {
        $lT = [$coordinate_1[0],$coordinate_1[1]];
        $rT = [$coordinate_2[0],$coordinate_1[1]];
        $lB = [$coordinate_1[0],$coordinate_2[1]];
        $rB = [$coordinate_2[0],$coordinate_2[1]];
        $start_1 = $this->countCoordinatePosition($lT);
        $end_1 =  $this->countCoordinatePosition($rT);
        $start_2 = $this->countCoordinatePosition($lB);
        $end_2 =  $this->countCoordinatePosition($rB);

        $arr1 = array_slice($this->map->toArray(),$start_1,$end_1-$start_1+1);
        $arr2 = array_slice($this->map->toArray(),$start_2,$end_2-$start_2+1);
        return array_merge($arr1,$arr2);
    }

    public function countCoordinatePosition($coordinate){
        return (intval($coordinate[1]-1)*$this->h)+$coordinate[0]-1;
    }

    public function mapToString(){
        foreach ($this->map->toArray() as$key=>$value){
            if (($key)%$this->w==0){
//                echo $key;
                echo "\n";
            }
            if ($value instanceof Road){
                echo " [] ";
            }elseif ($value instanceof Organisms){
                echo " -- ";
            }else{
                echo " ++ ";
            }



        }




    }

}