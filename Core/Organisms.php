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

    static $level = 2;

    protected $type = SysConst::MAP_OBJ_O;

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

    public function init(AI $AI)
    {
        $AI->organismsInit($this);
    }

    /**
     * 更新饥饿值
     * @param $num
     * @return int
     */
    public function setHungry($num){
        $this->hungry+=$num;
        if($this->hungry<=0){
            $this->setLife(-1);
        }
        return $this->hungry;
    }

    /**
     * 设置生命,只要修改了什么,饥饿值直接补满
     * @param $num
     * @return mixed
     */
    public function setLife($num){
        $this->hungry=50;
        return $this->life+=$num;
    }
}