<?php
/**
 * Created by PhpStorm.
 * User: Zhu
 * Date: 2018/3/16
 * Time: 13:36
 */

namespace app\admin\model;


use think\Model;

class LotterytimeModel extends Model
{
    protected $name = 'lottery_time';

//    protected $autoWriteTimestamp = 'timestamp';   // 开启自动写入时间戳


    /**
     * 根据搜索条件获取用户列表信息
     */
    public function getLotterytimeWhere($map, $Nowpage, $limits)
    {
        return $this
            ->field('think_lottery_time.*,game_title')
            ->join('think_lottery_type', 'think_lottery_type.id = think_lottery_time.type','LEFT')
            ->where($map)->page($Nowpage, $limits)->order('id asc')->select();
    }
}