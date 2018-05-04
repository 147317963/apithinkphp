<?php

namespace app\admin\controller;
use app\admin\model\AdminModel;
use app\admin\model\UserType;
use think\Db;

class Admin extends Base
{

    /**
     * [index 用户列表]
     * @return [type] [description]
     * @author [田建龙] [864491238@qq.com]
     */
    public function index(){

        $key = input('key');
        $map = [];
        if($key&&$key!=="")
        {
            $map[] = ['username','like',"%" . $key . "%"];
        }
        $admin =new AdminModel();
        $Nowpage = input('get.page') ? input('get.page'):1;
        $limits = config()['list_rows'];// 获取总条数
        $count = $admin->where($map)->count();//计算总页面
        $allpage = intval(ceil($count / $limits));
        $lists = $admin->getUsersByWhere($map, $Nowpage, $limits);
        $this->assign('Nowpage', $Nowpage); //当前页
        $this->assign('allpage', $allpage); //总页数 
        $this->assign('val', $key);
        if(input('get.page'))
        {
            return json($lists);
        }
        return $this->fetch();
    }


    /**
     * [userAdd 添加用户]
     * @return [type] [description]
     * @author [田建龙] [864491238@qq.com]
     */
    public function adminAdd()
    {
        if(request()->isAjax()){

            $param = input('post.');
            $param['password'] = md5($param['password']);
            $admin =new AdminModel();
            $flag = $admin->insertAdmin($param);
            $accdata = array(
                'uid'=> $admin['id'],
                'group_id'=> $param['groupid'],
            );
            $group_access = Db::name('auth_group_access')->insert($accdata);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        $role = new UserType();
        $this->assign('role',$role->getRole());
        return $this->fetch();
    }


    /**
     * [userEdit 编辑用户]
     * @return [type] [description]
     * @author [田建龙] [864491238@qq.com]
     */
    public function adminEdit()
    {
        $admin = new AdminModel();
        if(request()->isAjax()){

            $param = input('post.');
            if(empty($param['password'])){
                unset($param['password']);
            }else{
                $param['password'] = md5($param['password']);
            }
            $flag = $admin->editUser($param);
            $group_access = Db::name('auth_group_access')->where('uid', $admin['id'])->update(['group_id' => $param['groupid']]);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        $id = input('param.id');
        $role = new UserType();
        $this->assign([
            'user' => $admin->getOneUser($id),
            'role' => $role->getRole()
        ]);
        return $this->fetch();
    }


    /**
     * [UserDel 删除用户]
     * @return [type] [description]
     * @author [田建龙] [864491238@qq.com]
     */
    public function adminDel()
    {
        $id = input('param.id');
        $role = new AdminModel();
        $flag = $role->AdminDel($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }



    /**
     * [user_state 用户状态]
     * @return [type] [description]
     * @author [田建龙] [864491238@qq.com]
     */
    public function adminState()
    {
        $admin = new AdminModel();
        $id = input('param.id');
        $status = $admin->where(['id'=>$id])->value('status');//判断当前状态情况
        if($status==1)
        {
            $flag = $admin->where(['id'=>$id])->setField(['status'=>2]);
            return json(['code' => 1, 'data' => $flag['data'], 'msg' => '已禁止']);
        }
        else
        {
            $flag = $admin->where(['id'=>$id])->setField(['status'=>1]);
            return json(['code' => 0, 'data' => $flag['data'], 'msg' => '已开启']);
        }
    
    }

}