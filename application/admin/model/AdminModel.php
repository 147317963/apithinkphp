<?php

namespace app\admin\model;
use think\Db;
use think\Model;

class AdminModel extends Model
{
    protected $name = 'Admin';

    protected $autoWriteTimestamp = 'timestamp';   // 开启自动写入时间戳


    /**
     * 根据搜索条件获取用户列表信息
     */
    public function getUsersByWhere($map, $Nowpage, $limits)
    {
        return $this->field('think_admin.*,title')->join('think_auth_group', 'think_admin.groupid = think_auth_group.id')
            ->where($map)->page($Nowpage, $limits)->order('id desc')->select();
    }

    /**
     * 根据搜索条件获取所有的用户数量
     * @param $where
     */
    public function getAllUsers($where)
    {
        return $this->where($where)->count();
    }

    /**
     * 插入管理员信息
     * @param $param
     */
    public function insertAdmin($param)
    {
        try{
            $result = $this->validate('UserValidate')->allowField(true)->save($param);
            if(false === $result){
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                writelog(session('id'),session('username'),'添加【'.$param['username'].'】管理员成功',1);
                return ['code' => 1, 'data' => '', 'msg' => '添加用户成功'];
            }
        }catch( PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * 编辑管理员信息
     * @param $param
     */
    public function editUser($param)
    {
        try{
            $result =  $this->validate('UserValidate')->allowField(true)->save($param, ['id' => $param['id']]);
            if(false === $result){
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
                writelog(session('id'),session('username'),'编辑【'.$param['username'].'】管理员成功',1);
                return ['code' => 1, 'data' => '', 'msg' => '编辑用户成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }


    /**
     * 根据管理员id获取角色信息
     * @param $id
     */
    public function getOneUser($id)
    {
        return $this->where('id', $id)->find();
    }


    /**
     * 删除管理员
     * @param $id
     */
    public function adminDel($id)
    {
        try{
            $admin = $this->where(['id'=>$id])->value('username');
            $this->where(['id'=>$id])->delete();
            $authgroupaccess = new AuthGroupAccessModel();
            $authgroupaccess->where(['uid'=>$id])->delete();
            writelog(session('id'),session('username'),'删除【'.$admin.'】管理员成功',1);
            return ['code' => 1, 'data' => '', 'msg' => '删除用户成功'];

        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

}