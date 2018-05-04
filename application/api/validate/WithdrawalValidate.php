<?php

namespace app\api\validate;

use think\Validate;

class WithdrawalValidate extends Validate
{
    protected $regex = [

        'password'=>'/^[\w\_]{6,16}$/u',

    ];

    protected $rule = [

        'money' => 'number|egt:100|elt:49999',

        'password'=> 'regex:password|require',

        'page' => 'number:require',


    ];

    protected $message  =   [
        'page.require'=>'分页不能为空',
        'page.number'=>'分页必须为数字',
        'money.number'=>'取款金额必须是数字',
        'money.egt'=>'取款最小金额100元',
        'money.elt'=>'取款最大金额49999元',
        'password.require' => '密码不能为空',
        'password.regex' => '支付密码由6到16位,只能有字母或数字',
    ];
    protected $scene = [
        'getWithdrawal'   =>  ['page'],//查询存款记录
        'index'   =>  ['money','password'],//查询存款记录

    ];
}