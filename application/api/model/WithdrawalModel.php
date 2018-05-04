<?php
/**
 * Created by PhpStorm.
 * User: Zhu
 * Date: 2018/3/17
 * Time: 18:54
 */

namespace app\api\model;


use think\Model;

class WithdrawalModel extends Model
{
    protected $name = 'withdrawal';

    protected $autoWriteTimestamp = 'timestamp';   // 开启自动写入时间戳



    public function getStatusAttr($value)
    {
        $status = [1=>'等待',2=>'取消',3=>'已支付',4=>'删除'];
        return $status[$value];
    }
}