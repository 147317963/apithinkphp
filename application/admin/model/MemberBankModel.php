<?php
/**
 * Created by PhpStorm.
 * User: Zhu
 * Date: 2018/3/16
 * Time: 13:36
 */

namespace app\admin\model;


use think\Model;

class MemberBankModel extends Model
{
    protected $name = 'member_bank';

    protected $autoWriteTimestamp = 'timestamp';   // 开启自动写入时间戳
}