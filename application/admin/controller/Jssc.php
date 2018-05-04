<?php
namespace app\admin\controller;
use app\admin\model\AutoModel;
use QL\QueryList;
use think\Controller;


class Jssc extends Controller
{
    public function Jssc_js()
    {

    }

    public function auto_jssc_1()
    {
        $automodel = new AutoModel();
        try {
            $header = array('CLIENT-IP:58.68.44.66', 'X-FORWARDED-FOR:58.68.44.66');
            $url = "http://speedylottos.com/speedy10-result.php";
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_URL, $url);//登录提交的地址
            curl_setopt($curl, CURLOPT_HEADER, 0);//是否显示头信息
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//是否自动显示返回的信息
            curl_setopt($curl, CURLOPT_TIMEOUT, 10);
            $result = curl_exec($curl);
            curl_close($curl);

            $dataResult = QueryList::html($result)->rules(array(
                'number' => array('.row.bordergray2.pb10.pt10>.col-xs-5', 'text'),
                'list' => array('.row.bordergray2.pb10.pt10>.col-xs-7', 'html')
            ), '.container.resulttable.graytxt>.col-xs-7.col-md-6')->query()->getData(function ($item) {
                $item['list'] = QueryList::html($item['list'])->rules(array(
                    'list' => array('.resultnum3', 'text')
                ))->query()->getData();
                return $item;
            });
            foreach ($dataResult as $key => $value) {
                $data['number'] = $value['number'];
                $data['data'] = BuLings($value['list'][0]['list']) . ',' . BuLings($value['list'][1]['list']) . ',' . BuLings($value['list'][2]['list']) . ',' . BuLings($value['list'][3]['list']) . ',' . BuLings($value['list'][4]['list']) . ',' . BuLings($value['list'][5]['list']) . ',' . BuLings($value['list'][6]['list']) . ',' . BuLings($value['list'][7]['list']) . ',' . BuLings($value['list'][8]['list']) . ',' . BuLings($value['list'][9]['list']);
                $data['type'] = '90';
                if ($automodel->where(['number' => $data['number'], 'type' => $data['type']])->find() == null) {
                    $automodel->insert($data);
                }

                if ($key == 0) {
                    $this->assign('data', $data);
                }
            }

            return $this->fetch();

        } catch (\Exception $e) {
            // 回滚事务

            return $this->fetch();


        }

    }


    //获得开奖结果2
    public function auto_jssc_2()
    {
        $automodel = new AutoModel();
        try {
            $header = [
                'User-Agen' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.104 Safari/537.36 Core/1.53.2604.400 QQBrowser/9.6.10897.400',
                'Referer' => 'http://1680210.com/html/jisusaiche/pk10kai.html',
                'Host' => 'api.api68.com',
//            'Origin'=>'http://1680210.com'
            ];


//      $url="http://api.1680210.com/pks/getLotteryPksInfo.do?lotCode=10037";
            $url = "http://api.api68.com/pks/getLotteryPksInfo.do?lotCode=10037";
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);//登录提交的地址
            curl_setopt($curl, CURLOPT_HEADER, 0);//是否显示头信息
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//是否自动显示返回的信息
            curl_setopt($curl, CURLOPT_TIMEOUT, 10);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            $result = @curl_exec($curl);
            curl_close($curl);

            if ($result) {
                $contents = json_decode($result, true);
                if (isset($contents['result']['data'])) {
                    $number = explode(",", $contents['result']['data']['preDrawCode']);
                    $data['number'] = $contents['result']['data']['preDrawIssue'];
                    $data['data'] = BuLings($number[0]) . ',' . BuLings($number[1]) . ',' . BuLings($number[2]) . ',' . BuLings($number[3]) . ',' . BuLings($number[4]) . ',' . BuLings($number[5]) . ',' . BuLings($number[6]) . ',' . BuLings($number[7]) . ',' . BuLings($number[8]) . ',' . BuLings($number[9]);
                    $data['type'] = '90';

                    if ($automodel->where(['number' => $data['number'], 'type' => $data['type']])->find() == null) {
                        $automodel->insert($data);
                    }
                    $this->assign('data', $data);

                }
            }

            return $this->fetch();

        } catch (\Exception $e) {
            // 回滚事务

            return $this->fetch();


        }

    }


}