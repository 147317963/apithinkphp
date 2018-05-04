<?php

namespace app\admin\model;
use think\Model;

class LogModel extends Model
{
    protected $name = 'log';

    protected $autoWriteTimestamp = 'timestamp';   // 开启自动写入时间戳

    /**
     * 删除日志
     */
    public function delLog($id)
    {
        try{
            $this->where(['id'=>$id])->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除日志成功'];
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

}