<?php

namespace app\api\model;
use think\Model;
use think\Db;

class MemberModel extends Model
{
    protected $name = 'member';  
    protected $autoWriteTimestamp = 'timestamp';   // 开启自动写入时间戳

    protected $readonly = ['id','username']; //只读字段

    protected $insert = ['reg_ip'];




    /**
     * 自动注册写入IP
     */
    protected function setRegipAttr()
    {
        return request()->ip();
    }

    public function getStatusAttr($value)
    {
        $status = [1=>'正常',2=>'禁止',3=>'已删除'];
        return $status[$value];
    }
    public function getGroupidAttr($value)
    {
        $status = [1=>'会员',2=>'代理',3=>'总代'];
        return $status[$value];
    }









}