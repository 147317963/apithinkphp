<?php


namespace app\admin\controller;
use app\admin\model\AutoModel;
use think\Controller;


class Cqssc extends Controller
{


    public function Cqssc_js(){

    }

    //获得开奖结果1
    public function auto_cqssc_1(){
        $automodel = new AutoModel();
        try{
        $header=[
            'User-Agen' =>'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.104 Safari/537.36 Core/1.53.2604.400 QQBrowser/9.6.10897.400'
        ];


        $url="http://api.1680210.com/CQShiCai/getBaseCQShiCaiList.do?lotCode=10002";
        $curl=curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);//登录提交的地址
        curl_setopt($curl, CURLOPT_HEADER, 0);//是否显示头信息
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//是否自动显示返回的信息
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        $result = @curl_exec($curl);
        curl_close($curl);

        if($result){

            $contents = json_decode($result, true);
            if (isset($contents['result']['data'])){
                foreach ($contents['result']['data'] as $key=>$value){
                    $number = explode(",",$value['preDrawCode']);
                    $data['number'] = $value['preDrawIssue'];
                    $data['data'] = BuLings($number[0]).','.BuLings($number[1]).','.BuLings($number[2]).','.BuLings($number[3]).','.BuLings($number[4]);
                    $data['type']='1';

                    if ($automodel->where(['number' => $data['number'],'type'=>$data['type']])->find() == null) {
                        $automodel->insert($data);
                    }
                    if($key>=10){
                        break;
                    }elseif($key==0){
                        $this->assign('data',$data);
                    }

                }

            }

        }


        return $this->fetch();
        } catch (\Exception $e) {
            // 回滚事务

            return $this->fetch();


        }


    }
    //获得开奖结果2
    public function auto_cqssc_2(){
        $automodel = new AutoModel();
        try{
        $header=[
            'User-Agen' =>'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.104 Safari/537.36 Core/1.53.2604.400 QQBrowser/9.6.10897.400'
        ];
        $url="http://z.apiplus.net/newly.do?token=67f176514dfd19dc&code=cqssc&rows=1&format=json";
        $curl=curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);//登录提交的地址
        curl_setopt($curl, CURLOPT_HEADER, 0);//是否显示头信息
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//是否自动显示返回的信息
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        $result = @curl_exec($curl);
        curl_close($curl);
        if($result){
            $contents = json_decode($result, true);

            if($contents){
                //开奖号码分解
                $number = explode(",",$contents['data'][0]['opencode']);
                $data['number'] = $contents['data'][0]['expect'];
                $data['data'] = BuLings($number[0]).','.BuLings($number[1]).','.BuLings($number[2]).','.BuLings($number[3]).','.BuLings($number[4]);
                $data['type']='1';
                if ($automodel->where(['number' => $data['number'],'type'=>$data['type']])->find() == null) {
                    $automodel->insert($data);
                }


                $this->assign('data',$data);


            }
        }


        return $this->fetch();
        } catch (\Exception $e) {
            // 回滚事务

            return $this->fetch();


        }
    }


}