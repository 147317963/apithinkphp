<?php
/**
 * Created by PhpStorm.
 * User: Zhu
 * Date: 2018/3/16
 * Time: 13:36
 */

namespace app\admin\model;


use think\Model;

class AuthGroupAccessModel extends Model
{
    protected $name = 'auth_group_access';

    protected $autoWriteTimestamp = false;   // 开启自动写入时间戳
}
