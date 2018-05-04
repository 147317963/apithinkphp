<?php
namespace app\admin\controller;




use think\Controller;
use app\admin\model\AutoModel;




class Xyft extends Controller
{
    public function xyft_js(){

    }


    //获得开奖结果1
    public function auto_xyft_1()
    {
        $automodel = new AutoModel();
        try {
            $header = [
                'User-Agen' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.104 Safari/537.36 Core/1.53.2604.400 QQBrowser/9.6.10897.400'
            ];
            $url = "http://www.1393p.cn/Home/Xyft/getXyftHistoryList.html?lotCode=10058";
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);//登录提交的地址
            curl_setopt($curl, CURLOPT_HEADER, 0);//是否显示头信息
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//是否自动显示返回的信息
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            $result = @curl_exec($curl);
            curl_close($curl);
            if ($result) {
                $contents = json_decode($result, true);

                if ($contents) {
                    //开奖号码分解
                    foreach ($contents['result']['data'] as $key => $value) {
                        $number = explode(",", $value['preDrawCode']);
                        $data['number'] = $value['preDrawIssue'];
                        $data['data'] = BuLings($number[0]) . ',' . BuLings($number[1]) . ',' . BuLings($number[2]) . ',' . BuLings($number[3]) . ',' . BuLings($number[4]) . ',' . BuLings($number[5]) . ',' . BuLings($number[6]) . ',' . BuLings($number[7]) . ',' . BuLings($number[8]) . ',' . BuLings($number[9]);
                        $data['type'] = '80';

                        if ($automodel->where(['number' => $data['number'], 'type' => $data['type']])->find() == null) {
                            $automodel->insert($data);
                        }

                        if ($key >= 20) {
                            break;
                        } elseif ($key == 0) {
                            $this->assign('data', $data);
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
    public function auto_xyft_2(){
        $automodel = new AutoModel();
        try{
        $header=[
            'User-Agen' =>'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.104 Safari/537.36 Core/1.53.2604.400 QQBrowser/9.6.10897.400'
        ];
        $url="http://z.apiplus.net/newly.do?token=67f176514dfd19dc&code=mlaft&rows=1&format=json";
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
                $data['data'] = BuLings($number[0]).','.BuLings($number[1]).','.BuLings($number[2]).','.BuLings($number[3]).','.BuLings($number[4]).','.BuLings($number[5]).','.BuLings($number[6]).','.BuLings($number[7]).','.BuLings($number[8]).','.BuLings($number[9]);
                $data['type']='80';

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