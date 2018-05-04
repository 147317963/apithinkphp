<?php
/**
 * Created by PhpStorm.
 * User: Zhu
 * Date: 2018/3/15
 * Time: 2:28
 */

namespace app\admin\model;




use think\Model;

class PushModel extends Model
{
    protected $name = 'push';

    protected $autoWriteTimestamp = 'timestamp';   // 开启自动写入时间戳

    public function insertPush($param)
    {
        $member = new MemberModel();
        try{
            $result = $this->allowField(true)->save($param);
            if(false === $result){
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                $username=$member->field('username')->where(['id'=>$param['uid']])->value('username');
                writelog(session('id'),session('username'),'推送【'.$username.'】消息成功',1);
                return ['code' => 1, 'data' => '', 'msg' => '推送成功'];
            }
        }catch( PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * 删除推送
     */
    public function delLog($id)
    {
        try{
            $this->where(['id'=>$id])->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除推送成功'];
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
}