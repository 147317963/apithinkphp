<?php

namespace app\api\validate;

use think\Validate;

class DepositValidate extends Validate
{




    protected $message  =   [
        'page.number'=>'参数错误',
    ];
    protected $scene = [
        'getDeposit'   =>  ['page'],//查询存款记录

    ];
}