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

    protected $map_data = [
        SysConst::MAP_ROAD => ['obj' => [], 'distance' => []],
        SysConst::MAP_O    => ['obj' => [], 'distance' => []],
        SysConst::MAP_MINE => ['obj' => [], 'distance' => []],
        SysConst::MAP_FOOD => ['obj' => [], 'distance' => []],
    ];

//    public function

    public function init(Map $map, AI $AI)
    {
        $AI->organismsInit($this,$map);
    }
}