<?php

namespace app\admin\model;
use think\Model;

class AutoModel extends Model
{
    protected $name = 'auto';

    protected $autoWriteTimestamp = 'timestamp';   // 开启自动写入时间戳

}