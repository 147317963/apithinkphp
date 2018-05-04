<?php

namespace app\api\controller;
use app\api\model\DepositModel;
use think\Controller;
use think\Db;


/**
 * swagger: 会员存款
 */
class Deposit extends Base
{

    /**
     * post: 存款
     * path: setDeposit
     * method: setDeposit
     * param: uid - {int} 用户名id
     * param: money - {int} 存款金额
     * param: type - {string} 支付方式
     */
    public function setDeposit($uid = '', $money = 0 ,$type = 'alipay')
    {

        $result = $this->validate(compact('username', 'password'), 'AdminValidate');

       $deposit = new DepositModel();

        $deposit->uid = $uid;

        $deposit->money = $money;

        $deposit->type = $type;

        $deposit->save();

    }

    /**
     * get: 存款历史记录
     * path: getDeposit
     * method: getDeposit
     * param: page - {int} 页数
     */
    public function getDeposit($page = 1)
    {

        $result = $this->validate(compact('page'), 'DepositValidate.getDeposit');

        if(true !== $result){

            return json(['code' => 403, 'url' => '', 'msg' => $result]);

        }


        $deposit = new DepositModel();

        $Nowpage = $page;

        $limits = config()['list_rows'];// 获取总条数

        $map[] = ['uid','=',$this->user_id];

        $lists = $deposit->where($map)->limit($Nowpage,$limits)->order('id desc')->select();

        $data['code'] = 200;

        $data['msg'] = '获取成功';

        $data['datas']=$lists;

        return json($data);


    }


}