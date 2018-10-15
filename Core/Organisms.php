<?php
/**
 * Created by PhpStorm.
 * User: tioncico
 * Date: 18-10-14
 * Time: 下午10:06
 */

namespace Core;


class Organisms extends MapObj
{

    protected $view = 5;//视野能力默认为5

    protected $life = 5;//5点生命

    protected $level = 2;

    protected $ability = [
        SysConst::ABILITY_MOVE, SysConst::ABILITY_EAT, SysConst::ABILITY_ATTACK, SysConst::ABILITY_ESCAPE
    ];//默认拥有能力

    protected $hungry = 50;//50回合之后开始饿

    protected $ability_data = [
        SysConst::ABILITY_MOVE => [
            'move_priority' => [
                SysConst::MOVE_TOP    => 0,
                SysConst::MOVE_BOTTOM => 0,
                SysConst::MOVE_LEFT   => 0,
                SysConst::MOVE_RIGHT  => 0
            ]
        ],
    ];//能力保留数据

    protected $map_data = [
        SysConst::MAP_ROAD => ['obj' => [], 'distance' => []],
        SysConst::MAP_O    => ['obj' => [], 'distance' => []],
        SysConst::MAP_MINE => ['obj' => [], 'distance' => []],
        SysConst::MAP_FOOD => ['obj' => [], 'distance' => []],
    ];


    public function ai()
    {
        $life_priority = $this->checkLifePriority();
        if ($life_priority >= 100) {
            return $this->getMoveTypeForLife();
        }
    }

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->ability_data[SysConst::ABILITY_MOVE]['move_priority'] = $this->AI->countMoveAbility($this->coordinate,$this->ability_data[SysConst::ABILITY_MOVE]['move_priority'] );


    }

    /**
     * 获取移动指令
     * @param Map $map
     */
    public function getMoveTypeForLife()
    {
        $array = [
            'type'  => SysConst::ABILITY_MOVE,
            'value' => ''
        ];
        //获取最近的食物
        if (empty($this->map_data[SysConst::MAP_FOOD])) {
            arsort($this->ability_data[SysConst::ABILITY_MOVE]['move_priority']);
            $array['value'] = key($this->ability_data[SysConst::ABILITY_MOVE]['move_priority']);
        } else {
            array_multisort(array_column($this->map_data[SysConst::MAP_FOOD], 'distance'), SORT_NUMERIC, SORT_ASC, $this->map_data[SysConst::MAP_FOOD]);
            $obj            = current($this->map_data[SysConst::MAP_FOOD])['obj'];
            $array['value'] = $this->AI->moveType($this->coordinate, $obj->getCoordinate());
        }
        return $array;
    }

    /**
     * 获取地图视野的所有对象
     */
    public function getMapObjs()
    {
        $coordinate_1 = [$this->coordinate[0] - $this->view, $this->coordinate[1] - $this->view];
        $coordinate_2 = [$this->coordinate[0] + $this->view, $this->coordinate[1] + $this->view];
        $coordinate_1 = $this->map->checkCoordinate($coordinate_1);
        $coordinate_2 = $this->map->checkCoordinate($coordinate_2);
        $map_objs     = $this->map->getObjs($coordinate_1, $coordinate_2);
        foreach ($map_objs as $obj) {
            if ($obj instanceof Road) {
                $this->map_data[SysConst::MAP_ROAD][$obj->coordinateToString()]['obj']      = $obj;
                $this->map_data[SysConst::MAP_ROAD][$obj->coordinateToString()]['distance'] = $this->map->countDistance($this->coordinate, $obj->getCoordinate());
            } elseif ($obj instanceof Organisms) {
                if ($this === $obj) {
                    $this->map_data[SysConst::MAP_MINE][$obj->coordinateToString()]['obj']      = $obj;
                    $this->map_data[SysConst::MAP_MINE][$obj->coordinateToString()]['distance'] = $this->map->countDistance($this->coordinate, $obj->getCoordinate());
                } else {
                    $this->map_data[SysConst::MAP_O][$obj->coordinateToString()]['obj']      = $obj;
                    $this->map_data[SysConst::MAP_O][$obj->coordinateToString()]['distance'] = $this->map->countDistance($this->coordinate, $obj->getCoordinate());
                }
            } elseif ($obj instanceof Food) {
                $this->map_data[SysConst::MAP_FOOD][$obj->coordinateToString()]['obj']      = $obj;
                $this->map_data[SysConst::MAP_FOOD][$obj->coordinateToString()]['distance'] = $this->map->countDistance($this->coordinate, $obj->getCoordinate());
            }
        }
    }


    /**
     * 验证生命值优先级
     * @return bool|int
     */
    public function checkLifePriority()
    {
        if ($this->life >= 3) {
            return 0;//生命值正常
        }

        switch ($this->life) {
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


}