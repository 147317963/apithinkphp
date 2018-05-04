<?php
/**
 * Created by PhpStorm.
 * User: Zhu
 * Date: 2018/3/15
 * Time: 2:27
 */

namespace app\admin\controller;


use app\admin\model\MemberModel;
use app\admin\model\PushModel;

class Push extends Base
{
    public function index(){
        $key = input('key');
        $map = [];
        if($key&&$key!==""){
            $map['username'] =  $key;
        }
        $push =new PushModel();
        $Nowpage = input('get.page') ? input('get.page'):1;
        $limits = config()['list_rows'];// 获取总条数
        $count = $push->where($map)->count();//计算总页面
        $allpage = intval(ceil($count / $limits));
        $lists = $push->where($map)->page($Nowpage, $limits)->order('id desc')->select();

        $this->assign('Nowpage', $Nowpage); //当前页
        $this->assign('allpage', $allpage); //总页数
        $this->assign('count', $count);
        $this->assign('val', $key);
        if(input('get.page')){
            return json($lists);
        }
        return $this->fetch();
    }

    public function add_push(){
        if(request()->isAjax()){

            $param = input('post.');
//
            $push = new PushModel();
            $flag = $push->insertPush($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        $id = input('param.id');
        $group = new MemberModel();
        $this->assign('member',$group->where(['id'=>$id])->find());
        return $this->fetch();
    }

    public function delLog(){
        $id = input('param.id');
        $push = new PushModel();
        $flag = $push->delLog($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }
}