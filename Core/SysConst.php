<?php
/**
 * Created by PhpStorm.
 * User: tioncico
 * Date: 18-10-15
 * Time: 下午9:18
 */

namespace Core;


class SysConst
{
    const ABILITY_MOVE = 1;//移动能力
    const ABILITY_EAT = 2;//吃的能力
    const ABILITY_ATTACK = 3;//攻击能力
    const ABILITY_DEFENSE = 4;//防守能力
    const ABILITY_COOPERATION = 5;//协作能力
    const ABILITY_PLANT = 6;//种植能力
    const ABILITY_ESCAPE = 7;//逃跑能力

    //移动常量
    const MOVE_TOP = 1;//上
    const MOVE_BOTTOM = 2;//下
    const MOVE_LEFT = 3;//左
    const MOVE_RIGHT = 4;//右

    const MAP_ROAD = 1;//道路
    const MAP_O = 2;//其他生物
    const MAP_MINE = 3;//自己
    const MAP_FOOD = 4;//吃的东西

    const MAP_OBJ_ROAD =1;
    const MAP_OBJ_O =2;
    const MAP_OBJ_FOOD =3;


}