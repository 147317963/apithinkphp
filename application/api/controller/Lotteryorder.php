<?php

namespace app\api\controller;
use app\api\model\LotteryOrderModel;

/**
 * swagger: 订单
 */

class Lotteryorder extends Base
{




    /**
     * post: 获取订单
     * path: getOrder
     * method: getOrder
     * param: type - {int} 彩种类型
     * param: date - {string} 日期
     * param: page - {int} 分页
     */
    public function getOrder($type = '' ,$date = '' ,$page = 1){

        $result = $this->validate(compact('type','date','page'), 'app\api\validate\LotteryOrderValidate.getOrder');
        if(true !== $result){
            return json(['code' => 403, 'url' => '', 'msg' => $result]);
        }


        $lotteryorder = new LotteryOrderModel();

        $map [] = ['id','=',$this->user_id];

        $map [] = ['type','=',$type];

        $map [] = ['date','=',$date];


        $Nowpage = $page;

        $limits = config()['list_rows'];

        $count = $lotteryorder->where($map)->count();         //获取总条数

        $allpage = intval(ceil($count / $limits));  //计算总页面

        $lists = $lotteryorder->where($map)->limit($Nowpage,$limits)->order('id desc')->select();

        $data['code'] = 200;

        $data['msg'] = '获取成功';

        $data['datas']=$lists;

        return json($data);



    }

}