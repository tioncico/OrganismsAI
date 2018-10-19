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
    protected $map_w, $map_h;
    protected $map;

    public function __construct(Map $map)
    {
        $this->map_w = $map->getWH()[0];
        $this->map_h = $map->getWH()[1];
        $this->map   = $map;
        return $this;
    }

    /**
     * 判断路径走法
     * @param $coordinate_1
     * @param $coordinate_2
     * @return int|null
     */
    public function moveType($coordinate_1, $coordinate_2)
    {
        if ($coordinate_1[0] > $coordinate_2[0]) {
            return SysConst::MOVE_LEFT;
        } elseif ($coordinate_1[0] < $coordinate_2[0]) {
            return SysConst::MOVE_RIGHT;
        }
        if ($coordinate_1[1] > $coordinate_2[1]) {
            return SysConst::MOVE_TOP;
        } elseif ($coordinate_1[1] < $coordinate_2[1]) {
            return SysConst::MOVE_BOTTOM;
        }
        return null;
    }

    /**
     * 计算移动优先级
     * @param $coordinate
     * @param $ability_data
     */
    public function countMoveAbility($coordinate, $ability_data)
    {
        if ($coordinate[0] > $this->map_w / 2) {
            $ability_data[SysConst::MOVE_LEFT] += 1;
        } else {
            $ability_data[SysConst::MOVE_RIGHT] += 1;
        }
        if ($coordinate[1] > $this->map_h / 2) {
            $ability_data[SysConst::MOVE_TOP] += 1;
        } else {
            $ability_data[SysConst::MOVE_BOTTOM] += 1;
        }
        return $ability_data;
    }

    /**
     * 获取地图视野的所有对象
     */
    public function getMapObjs($coordinate, $view)
    {
//        var_dump($coordinate);
//        $map_data     = [];
        $coordinate_1 = [$coordinate[0] - $view, $coordinate[1] - $view];
        $coordinate_2 = [$coordinate[0] + $view, $coordinate[1] + $view];
        $coordinate_1 = $this->map->checkCoordinate($coordinate_1);
        $coordinate_2 = $this->map->checkCoordinate($coordinate_2);
        $map_objs     = $this->map->getObjs($coordinate_1, $coordinate_2);
        $map_data = [];
        foreach ($map_objs as $level_array) {
            foreach ($level_array as $obj){
                if ($obj instanceof Road) {
                    $map_data[SysConst::MAP_ROAD][$obj->coordinateToString()] = $obj->toArray();
                } elseif ($obj instanceof Organisms) {
                    if ($this === $obj) {
                        $map_data[SysConst::MAP_MINE][$obj->coordinateToString()] = $obj->toArray();
                    } else {
                        $map_data[SysConst::MAP_O][$obj->coordinateToString()] = $obj->toArray();
                    }
                } elseif ($obj instanceof Food) {
//                var_dump(66);
                    $map_data[SysConst::MAP_FOOD][$obj->coordinateToString()] = $obj->toArray();
                }
            }
        }
        return $map_data;
    }

    /**
     * 获取生命危及移动指令
     * @param Map $map
     */
    public function getMoveTypeForLife($coordinate, $map_data, $ability_data)
    {
        $array = [
            'type'  => SysConst::ABILITY_MOVE,
            'value' => ''
        ];
        //获取最近的食物
//        var_dump($map_data[SysConst::MAP_FOOD]);
        if (empty($map_data[SysConst::MAP_FOOD])) {
            arsort($ability_data[SysConst::ABILITY_MOVE]['move_priority']);
            $array['value'] = key($ability_data[SysConst::ABILITY_MOVE]['move_priority']);
        } else {
               array_multisort(array_column($map_data[SysConst::MAP_FOOD], 'distance'), SORT_NUMERIC, SORT_ASC, $map_data[SysConst::MAP_FOOD]);
            $coordinate_2   = current($map_data[SysConst::MAP_FOOD])['coordinate'];
            $array['value'] = $this->moveType($coordinate, $coordinate_2);
        }
        return $array;
    }

    /**
     * 验证生命值优先级
     * @return bool|int
     */
    public function checkLifePriority  ($life)
    {
        if ($life >= 3) {
            return 0;//生命值正常
        }

        switch ($life) {
            case 0:
                return false;
                break;
            case 1:
                return 100;
            case 2:
                return 50;
                break;
            default:
                return 0;
                break;
        }
    }

    /**
     * 生物ai动作
     * @return array
     */
    public function organismsAi(Organisms $organisms)
    {
        //处理周围环境
        $organisms->addProperty('map_data',$this->getMapObjs($organisms->getProperty('coordinate'),$organisms->getProperty('view')));

        //判断生命权重
        $life_priority = $this->checkLifePriority($organisms->getProperty('life'));
//        var_dump($organisms->getProperty('life'));
        if ($life_priority >= 100) {
//            var_dump($organisms->getProperty('coordinate'));
            $array = $this->getMoveTypeForLife($organisms->getProperty('coordinate'),$organisms->getProperty('map_data'),$organisms->getProperty('ability_data'));
//            var_dump($array);
        }


    }

    public function organismsInit(Organisms $organisms)
    {
        //修改移动优先级
            $ability_data = $organisms->getProperty('ability_data');
        $ability_data[SysConst::ABILITY_MOVE]['move_priority'] = $this->countMoveAbility($organisms->getProperty('coordinate'), $ability_data[SysConst::ABILITY_MOVE]['move_priority']);
        $organisms->addProperty('ability_data',$ability_data);


        return true;
    }


}