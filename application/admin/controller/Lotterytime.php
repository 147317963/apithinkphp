<?php

namespace app\admin\controller;
use app\admin\model\LotterytimeModel;
use app\admin\model\LotterytypeModel;
use think\Db;

class Lotterytime extends Base
{
    public function index(){
//        $map['type']=20;
//        $map['action_time']=['>=','00:00:00'];
//
//
//       dump( Db::name('lottery_time')->where($map)->find());

        $key = input('key');
        $map = [];
        if($key&&$key!==""){
            $map['type'] =  $key;
        }
        $lotterytime =new LotterytimeModel();
        $lotterytype =new LotterytypeModel();

        $arr = $lotterytype->column("id,game_title"); //获取用户列表
        $Nowpage = input('get.page') ? input('get.page'):1;
        $limits = config()['list_rows'];// 获取总条数
        $count = $lotterytime->where($map)->count();//计算总页面
        $allpage = intval(ceil($count / $limits));
        $lists = $lotterytime->getLotterytimeWhere($map,$Nowpage, $limits);
        $this->assign('Nowpage', $Nowpage); //当前页
        $this->assign('allpage', $allpage); //总页数
        $this->assign('count', $count);
        $this->assign("search_user",$arr);
        $this->assign('val', $key);
        if(input('get.page')){
            return json($lists);
        }
        return $this->fetch();
    }

}