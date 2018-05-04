<?php

namespace app\api\validate;

use think\Validate;

class LotteryOrderValidate extends Validate
{


    protected $rule = [
        'type' => 'number:require',
        'date' => 'dateFormat:Y-m-d:require',
        'page' => 'number:require',


    ];

    protected $message  =   [
        'page.require'=>'分页不能为空',
        'page.number'=>'分页必须为数字',
        'type.require'=>'彩种类型不能为空',
        'type.number'=>'彩种类型必须是数字',
        'date.require' => '日期不能为空',
        'date.dateFormat' => '日期格式不对',
    ];
    protected $scene = [
        'getOrder'   =>  ['type','date','page'],//查询订单

    ];
}