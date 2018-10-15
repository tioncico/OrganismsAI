<?php
/**
 * Created by PhpStorm.
 * User: tioncico
 * Date: 18-10-15
 * Time: 下午11:00
 */

namespace Core;


class AI
{
    use Singleton;
    protected $map;
    protected $map_w,$map_h;


    public function __construct(map $map)
    {
        $this->map = $map;
        $this->map_w = $this->map->getWH()[0];
        $this->map_h = $this->map->getWH()[1];

    }

    /**
     * 判断路径走法
     * @param $coordinate_1
     * @param $coordinate_2
     * @return int|null
     */
    public function moveType($coordinate_1, $coordinate_2){
        if ($coordinate_1[0]>$coordinate_2[0]){
            return SysConst::MOVE_LEFT;
        }elseif($coordinate_1[0]<$coordinate_2[0]){
            return SysConst::MOVE_RIGHT;
        }
        if ($coordinate_1[1]>$coordinate_2[1]){
            return SysConst::MOVE_TOP;
        }elseif ($coordinate_1[1]<$coordinate_2[1]){
            return SysConst::MOVE_BOTTOM;
        }
        return null;
    }

    /**
     * 计算移动优先级
     * @param $coordinate
     * @param $ability_data
     */
    public function countMoveAbility($coordinate,$ability_data){
        if ($coordinate[0]>$this->map_w/2){
            $ability_data[SysConst::MOVE_LEFT]+=1;
        }else{
            $ability_data[SysConst::MOVE_RIGHT]+=1;
        }
        if ($coordinate[1]>$this->map_h/2){
            $ability_data[SysConst::MOVE_TOP]+=1;
        }else{
            $ability_data[SysConst::MOVE_BOTTOM]+=1;
        }
        return $ability_data;
    }


}