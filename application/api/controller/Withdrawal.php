<?php

namespace app\api\controller;
use app\api\model\MemberModel;
use app\api\model\WithdrawalModel;
use think\Db;


/**
 * swagger: 会员取款
 */
class Withdrawal extends Base
{

    /**
     * post: 取款
     * path: index
     * method: index
     * param: money - {int} 取款金额
     * param: password - {string} 取款密码
     */
    public function index($money = 0,$password = '')
    {

        $result = $this->validate(compact('money','password'), 'WithdrawalValidate.index');

        if(true !== $result){

            return json(['code' => 403, 'url' => '', 'msg' => $result]);

        }
//        if(!is_numeric($money) || !$money>=config('')['min_deposlt_money']){
//            $data['code'] = 403;
//            $data['msg'] = '取款金额必须大于等于'.config('')['min_deposlt_money'];
//            return json($data);
//        }
//        if (!preg_match("/^[\w\_]{6,16}$/u", $password)) {
//            $data['code'] = 403;
//            $data['msg'] = '支付密码由6到16位的字母';
//            return json($data);
//        }

        $member = new MemberModel();
        $withdrawal = new WithdrawalModel();
        $map[] = ['think_member.id','=',$this->user_id];


        $user = $member->getMemberByWhere($map);



        if ($user['withdrawal_password'] != md5($password)) {
            $data['code'] = 403;
            $data['msg'] = '取款密码错误';
            return json($data);
        }
        Db::startTrans();
        try{
            unset($map);
            $map[]=['money','>=',$money];
            $result = $member->where($map)->setDec('money',$money);
            if($result==1){
                $param = [
                    'order_id'=>date('YmdHis').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8),
                    'uid'=>$user['id'],
                    'date' => date("Y-m-d",time()),
                    'money' => $money,
                ];
                $withdrawal->save($param);
                $data['code'] = 200;
                $data['msg'] = '取款成功.一般1分钟内到账.碰到高峰期10分钟不等 谢谢合作';
            }else{
                $data['code'] = 403;
                $data['msg'] = '取款失败请核实你的余额';

            }
            // 提交事务
            Db::commit();
            return json($data);
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }

    }

    /**
     * get: 取款历史记录
     * path: getWithdrawal
     * method: getWithdrawal
     * param: page - {int} 页数
     */
    public function getWithdrawal($page = 1)
    {

        $result = $this->validate(compact('page'), 'WithdrawalValidate.getWithdrawal');

        if(true !== $result){

            return json(['code' => 403, 'url' => '', 'msg' => $result]);

        }

        $withdrawal = new WithdrawalModel();

        $Nowpage = $page;

        $limits = config()['list_rows'];// 获取总条数

        $map[] = ['uid','=',$this->user_id];

        $lists = $withdrawal->where($map)->limit($Nowpage,$limits)->order('id desc')->select();

        $data['code'] = 200;

        $data['msg'] = '获取成功';

        $data['datas']=$lists;

        return json($data);


    }
}