<?php

namespace app\api\controller;
use app\api\model\MemberBankModel;
use app\api\model\MemberloginmsgModel;
use app\api\model\MemberModel;
use think\facade\Cache;
use think\Db;
use Zhuzhichao\BankCardInfo\BankCard;

/**
 * swagger: 会员中心
 */
class Member extends Base
{
	/**
	 * post: 登陆
	 * path: login
	 * method: login
	 * param: username - {string} 用户名
	 * param: password - {string} 密码
	 */
    public function login($username = '', $password = '')
    {
        if(session('loginTime')){
            if(session('loginTime')+2>time()){
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(['code'=>403, 'msg'=>'操作过快']));

            }
        }
        session('loginTime',time());
        $result = $this->validate(compact('username','password'), 'app\api\validate\MemberValidate.login');

        if(true !== $result){
            return json(['code' => 403, 'url' => '', 'msg' => $result]);
        }
        $map['username'] = $username;
        $member = new MemberModel();
        $user = $member->where($map)->find();
        if($user == false){
            $data['code'] = 403;
            $data['msg'] = '账号或密码错误';
            $datas['uid'] = 0;
        }else if($user['password'] != md5($password)){
            $data['code'] = 403;
            $data['msg'] = '账号或密码错误';
            $datas['uid'] = 0;
        }else if($user['status']=='禁止'){

            $data['code'] = 403;
            $data['msg'] = '账号禁止登录';
            $datas['uid'] = 0;

        }else{
            $data['code'] = 200;
            $data['datas'] = $user;
            $data['msg'] = '登录成功';
            $authKey = md5($username.$password.session_id());
            Cache::store('redis')->set('Auth_'.$authKey,$user,86400);
            $data['datas']['authKey']=$authKey;
            $datas['uid'] = $user['id'];
        }




        //登录信息记录
        $memberloginmsg = new MemberloginmsgModel();
        $datas['os'] = PHP_OS;
        $datas['browser'] = $_SERVER['HTTP_USER_AGENT'];
        $datas['msg']=$data['msg'];


        $memberloginmsg->allowField(true)->save($datas);
        return json($data);

    }
    /**
     * post: 获取余额
     * path: info
     * method: info
     */
    public function info(){
        $member = new MemberModel();
        $user = $member
            ->field('money,name,number,address,status')
            ->join('think_member_bank', 'think_member.id = think_member_bank.uid','LEFT')
            ->where(['think_member.id'=>$this->user_id])
            ->find();
        $data['code'] = 200;
        $data['msg'] = '获取成功';
        $data['datas'] = $user;
        return json($data);

    }

	/**
	 * post: 注册
	 * path: register
	 * method: register
	 * param: username - {string} 用户名
	 * param: password - {string} 密码
     * param: name - {string} 姓名
     * param: qq - {string} qq
     * param: id - {int} id
	 */
	public function register($username = '', $password = '', $name = '', $qq = '', $id = 1) {

        if(session('loginTime')){
            if(session('loginTime')+2>time()){
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(['code'=>403, 'msg'=>'操作过快']));

            }
        }
        session('loginTime',time());

        $result = $this->validate(compact('username', 'password','name','qq','id'), 'app\api\validate\MemberValidate.register');
        if(true !== $result){
            return json(['code' => 403, 'url' => '', 'msg' => $result]);
        }
        $member = new MemberModel();

        $map['username'] = $username;

        $user = $member->where($map)->find();
        if($user){
            $data['code'] = 403;
            $data['msg'] = '账户已存在';
            return json($data);
        }else{
            Db::startTrans();
            try{
                $member->username = $username;
                $member->name = $name;
                $member->password = md5($password);
                $member->qq = $qq;
                $member->save();
                // 提交事务
                Db::commit();
                $data['code'] = 200;
                $data['msg'] = '注册成功';
                return json($data);
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                $data['code'] = 403;
                $data['datas'] = $user;
                $data['msg'] = '注册失败';
                return json($data);
            }
        }
    }


	/**
	 * get: 获取用户信息
	 * path: getUserInfo
	 * method: getUserInfo
	 * param: info - {string} 用户名|姓名|QQ
	 */
	public function getUserInfo($info = '') {
        $result = $this->validate(compact('info'), 'app\api\validate\MemberValidate.getUserInfo');
        if(true !== $result){
            return json(['code' => 403, 'url' => '', 'msg' => $result]);
        }


            $member = new MemberModel();
			$user = $member->where(['username|name|qq'=>$info])->select();
			if ($user) {
				$data['code'] = 200;
				$data['datas'] = $user;
				$data['msg'] = '获取用户信息成功';
			} else {
				$data['code'] = 403;
				$data['msg'] = '无任何数据';
			}
        return json($data);
		
	}


	/**
	 * post: 修改QQ号
	 * path: editqq
	 * method: editqq
	 * param: qq - {int} qq
	 */
    public function editqq($qq = 0)
    {




        $result = $this->validate(compact('qq'), 'MemberValidate.editqq');
        if(true !== $result){
            return json(['code' => 403, 'url' => '', 'msg' => $result]);
        }

        $member = new MemberModel();
        $user = $member->where(['id' => $this->user_id])->field('qq')->find();
        if ($user['qq'] == $qq) {
            $data['code'] = 403;
            $data['msg'] = '新QQ不能与旧QQ相同';
            return json($data);
        }
        $info = $member->save(['qq' => $qq], ['id' => $this->user_id]);
        if ($info) {
            $data['code'] = 200;
            $data['msg'] = '修改成功';
            return json($data);
        } else {
            $data['code'] = 403;
            $data['msg'] = '修改失败';
            return json($data);
        }

    }


	/**
	 * post: 修改密码
	 * path: editPwd
	 * method: editPwd
	 * param: oldpwd - {string} 旧密码
	 * param: newpwd - {string} 新密码
	 */
    public function editPwd($oldpwd = '', $newpwd = '')
    {

        $result = $this->validate(compact('oldpwd','newpwd'), 'app\api\validate\MemberValidate.editpwd');
        if(true !== $result){
            return json(['code' => 403, 'url' => '', 'msg' => $result]);
        }
        $oldpwd = md5($oldpwd);
        $newpwd = md5($newpwd);
        $member = new MemberModel();
        $user = $member->where(['id' => $this->user_id])->field('username,password')->find();
        if ($user['password'] != $oldpwd) {
            $data['code'] = 403;
            $data['msg'] = '原密码错误';
            return json($data);
        }
        if ($user['password'] == $newpwd) {
            $data['code'] = 403;
            $data['msg'] = '新密码不能与旧密码相同';
            return json($data);
        }
        $pwd['password'] = $newpwd;
        $info = $member->save($pwd, ['id' => $this->user_id]);
        if ($info) {
            $data['code'] = 200;
            $data['msg'] = '修改成功';
            return json($data);
        } else {
            $data['code'] = 403;
            $data['msg'] = '修改失败';
            return json($data);
        }


    }
    /**
     * post: 绑定银行卡
     * path: editCard
     * method: editCard
     * param: card - {int} 银行卡
     */
	public function editCard($card = 0)
    {
        $result = $this->validate(compact('card'), 'app\api\validate\MemberValidate.editCard');
        if(true !== $result){
            return json(['code' => 403, 'url' => '', 'msg' => $result]);
        }
        $memberbank = new MemberBankModel();
        $map['uid'] =$this->user_id;
        $user = $memberbank->where($map)->find();
        $address = BankCard::info($card);
        if(!$user){
            $memberbank->save(['uid'=>$this->user_id]);
            $user['number']='';
        }
        if($user['number']){
            $data['code'] = 403;
            $data['msg'] = '该用户已绑定过银行卡,如需更改请联系客服';
            return json($data);
        }
        if ($address['validated']==false) {
            $data['code'] = 403;
            $data['msg'] = '该卡无法识别归属银行..请联系客服绑定';
            return json($data);
        }
        $param = [
            'number' => $card,
            'address' => $address['bankName'],
        ];



        $result = $memberbank->save($param,['uid' => $this->user_id]);
        if($result){
            $data['code'] = 200;
            $data['msg'] = '绑定成功';
            return json($data);
        }else{
            $data['code'] = 403;
            $data['msg'] = '绑定失败';
            return json($data);
        }




    }

    /**
     * post: 修改支付密码
     * path: editnWithdrawalPwd
     * method: editnWithdrawalPwd
     * param: oldpwd - {int} 旧支付密码
     * param: newpwd - {int} 新支付密码
     */
    public function editnWithdrawalPwd($oldpwd = '', $newpwd = '')
    {
        $result = $this->validate(compact('oldpwd','newpwd'), 'app\api\validate\MemberValidate.editnwithdrawalpwd');
        if(true !== $result){
            return json(['code' => 403, 'url' => '', 'msg' => $result]);
        }
        $oldpwd = md5($oldpwd);
        $newpwd = md5($newpwd);

        $memberbank = new MemberBankModel();
        $map = 'uid = '.$this->user_id;
        $user = $memberbank->where($map)->find();
        if(!$user){
            $memberbank->save(['uid'=>$this->user_id]);
            $user['withdrawal_password']='e10adc3949ba59abbe56e057f20f883e';
        }
        if ($user['withdrawal_password'] != $oldpwd) {
            $data['code'] = 403;
            $data['msg'] = '原支付密码错误';
            return json($data);
        }
        if ($user['withdrawal_password'] == $newpwd) {
            $data['code'] = 403;
            $data['msg'] = '新支付密码不能与密码相同';
            return json($data);
        }
        $param = [
            'withdrawal_password' => $newpwd
        ];
        $result = $memberbank->save($param, ['uid' => $user['id']]);
        if ($result) {
            $data['code'] = 200;
            $data['msg'] = '修改支付密码成功';
            return json($data);
        } else {
            $data['code'] = 403;
            $data['msg'] = '修改支付密码失败';
            return json($data);
        }


    }
    /**
     * get: 退出登录
     * path: loginout
     * method: loginout
     */
    public function loginout()
    {
        /*获取头部信息*/
        $header = request()->header();
        Cache::store('redis')->rm('Auth_' . $header['authkey']);
        $data['code'] = 200;
        $data['msg'] = '退出成功';
        return json($data);
    }
}