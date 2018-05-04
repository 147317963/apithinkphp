<?php

namespace app\api\controller;
use think\facade\Cache;
use think\Controller;

class Base extends Controller
{
    public $user_id;

    public function initialize()
    {


        header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId");
        if($_SERVER['REQUEST_METHOD']=='OPTIONS'){
            exit;
        }

//        if(Cache::store('redis')->get('loginTime')){
//            if(Cache::store('redis')->get('loginTime')+2>time()){
//                header('Content-Type:application/json; charset=utf-8');
//                exit(json_encode(['code'=>403, 'msg'=>'操作过快']));
//
//            }
//        }
//        Cache::store('redis')->set('loginTime',time(),0);

//        if(session('loginTime')){
//            if(session('loginTime')+2>time()){
//                header('Content-Type:application/json; charset=utf-8');
//                exit(json_encode(['code'=>403, 'msg'=>'操作过快']));
//
//            }
//        }
//        session('loginTime',time());




        $module     = strtolower(request()->module());
        $controller = strtolower(request()->controller());
        $action     = strtolower(request()->action());
        $url        = $module."/".$controller."/".$action;

        /*获取头部信息*/
        $header = request()->header();


        // 校验sessionid和authKey
        if (isset($header['authkey'])==false || $header['authkey']=='undefined') { //false是没有登录

            if($url !='api/member/login' && $url != 'api/member/register'){//没有登录 判断是不是现在登录或者正在注册
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(['code'=>203,'data'=>'login', 'msg'=>'请登录']));
            }

        }else if(false == Cache::store('redis')->get('Auth_'.$header['authkey'])){
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(['code'=>203,'data'=>'login', 'msg'=>'登录过期请重新登录']));
        }else{
            $this->user_id=Cache::store('redis')->get('Auth_'.$header['authkey'])['id'];


        }





        $config = Cache::store('redis')->get('db_config_data');
        if(!$config){
            $config = load_config();
            Cache::store('redis')->set('db_config_data',$config,60);
        }
        config($config);

        if(config()['web_site_close'] == 0){
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(['code'=>203,'data'=>'javascript:history.back(-1);', 'msg'=>'站点已经关闭，请稍后访问~']));
        }

        if(config()['admin_allow_ip']){
            if(in_array(request()->ip(),explode('#',config('admin_allow_ip')))){
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(['code'=>203,'data'=>'javascript:history.back(-1);', 'msg'=>'禁止访问']));
            }
        }

    }
}