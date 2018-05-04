<?php


namespace app\api\controller;
use app\api\model\OddsModel;
use think\facade\Cache;


/**
 * swagger: 盘口
 */
class Odds extends Base
{
    /**
     * get: 获得赔率
     * path: index
     * method: index
     * param: lotteryid - {string} 彩票ID
     */
    public function index($lotteryid){

        $odds = new OddsModel();

        if($lotteryid=='BJPK10_A' || $lotteryid=='BJPK10_B' || $lotteryid=='XYFT_A'|| $lotteryid=='XYFT_B'|| $lotteryid=='JSSC_A'|| $lotteryid=='JSSC_B'){
            if(!Cache::store('redis')->get($lotteryid)){
                $map['odds'] = $lotteryid;
                $list = $odds->where($map)->order('id')->select();
                $s=0;
                $oddslist = [];
                foreach ($list as $key=>$value){

                    if($key==0){
                        for($i = 0; $i<=99; $i++){
                            $oddslist[$value['type']][$i]['odds'] = $value['h'.$i];
                            $oddslist[$value['type']][$i]['text'] = $value['t'.$i];
                            $oddslist[$value['type']][$i]['css'] = '';
                        }
                    }else{
                        for($i = 0; $i<=20; $i++){
                            $oddslist[$value['type']][$i]['odds'] = $value['h'.$i];
                            $oddslist[$value['type']][$i]['text'] = $value['t'.$i];
                            $oddslist[$value['type']][$i]['css'] = '';
                        }
                    }

                    $s++;
                }
                Cache::store('redis')->set($lotteryid,$oddslist);
            }


        }else{

        }




        return Cache::store('redis')->get($lotteryid);


    }

}