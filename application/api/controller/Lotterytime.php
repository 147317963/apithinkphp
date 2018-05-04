<?php

namespace app\api\controller;
use app\api\model\LotteryTimeModel;




/**
 * swagger: 获得彩票时间
 */

class Lotterytime extends Base
{




    /**
     * post: 获取彩票封盘时间
     * path: getStopTime
     * method: getStopTime
     * param: type - {int} 彩种类型
     */
    public function getStopTime($type, $lotteryid)
    {
        if ($type == '20') {

            $lotterytimemodel = new LotteryTimeModel();
            $odds                  = new  Odds();

            $time = time();
            $map[] = ['action_time', '>=', date('H:i:s', $time)];
            $map[] = ['type', '=', $type];
            $lastno = (floor(($time - strtotime("2018-2-21 00:00:00")) / 3600 / 24) - 1) * 179 + 667278;
            $list = $lotterytimemodel->where($map)->find();
            $fengpan = strtotime(date("Y-m-d", $time) . ' ' . $list['stop_time']) - $time;





            $data['code'] = 200;
            $data['datas'] = [
                'number' => $lastno + $list['action_no'],
                'endtime' => $fengpan,
                'oddslist' =>$odds->index($lotteryid),
            ];

            $data['msg'] = '获取成功';
        } else {

        }

        return json($data);


    }

}