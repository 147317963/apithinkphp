<?php
/**
 * Created by PhpStorm.
 * User: Zhu
 * Date: 2018/3/17
 * Time: 18:54
 */

namespace app\api\model;


use think\Model;

class LotteryOrderModel extends Model
{
    protected $name = 'lottery_order';

    protected $autoWriteTimestamp = 'timestamp';   // 开启自动写入时间戳


//    public function getWhere($map, $Nowpage, $limits)
//    {
//        return $this
//            ->field('think_lottery_order.*,think_lottery_type.game_title')
//            ->join('think_lottery_type', 'think_lottery_order.type = think_lottery_type.id','LEFT')
//            ->where($map)->page($Nowpage, $limits)->order('id desc')->select();
//    }
}