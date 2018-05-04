<?php
namespace app\api\controller;


use think\Controller;
use think\facade\Cache;

/**
 * swagger: 读取配置
 */
class config extends Controller
{

    /**
     * get: 获得配置信息
     * path: index
     * method: index
     */
    public function index()
    {
//        if(Cache::store('redis')->get('loginTime')){
//            if(Cache::store('redis')->get('loginTime')+2>time()){
//                header('Content-Type:application/json; charset=utf-8');
//                exit(json_encode(['code'=>403, 'msg'=>'操作过快']));
//
//            }
//        }
//        Cache::store('redis')->set('loginTime',time(),0);

        $data['datas'] = load_config();
        $data['code'] = 200;
        $data['msg'] = '获取成功';
        return json($data);
    }

}