<?php

namespace app\api\validate;

use think\Validate;

class MemberValidate extends Validate
{
    protected $regex = [
        'username' => '/^[\w\_]{6,16}$/u',
        'password'=>'/^[\w\_]{6,16}$/u',
        'name'=>'/^([\xe4-\xe9][\x80-\xbf]{2}){2,4}$/',
        'qq'=>'/^[0-9]{5,20}$/',
        'oldpwd'=>'/^[\w\_]{6,16}$/u',
        'newpwd'=>'/^[\w\_]{6,16}$/u',
        'card'=>'/^[\w\_]{15,19}$/u',
    ];


    protected $rule = [
        'username'=> 'regex:username|require',
        'password'=> 'regex:password|require',
        'name'=> 'regex:name|require',
        'qq'=>'regex:qq|require',
        'oldpwd'=> 'regex:oldpwd|require',
        'newpwd'=> 'regex:newpwd|require',
        'card'=>'regex:card|require',
        'info'=>'require',
        'id'=>'number',
    ];
    protected $message  =   [
        'id.number'=>'参数错误',
        'username.require' => '用户不能为空',
        'username.regex' => '用户由6到16位,只能有字母或数字',
        'password.require' => '密码不能为空',
        'password.regex' => '密码由6到16位,只能有字母或数字',
        'name.require'     => '姓名不能为空',
        'name.regex' => '必须填写真实姓名2-3位,方便取款字',
        'qq.require'   => 'QQ不能为空',
        'qq.regex'   => '请输QQ号码5-20位',
        'oldpwd.require'  => '旧密码或支付密码不能为空',
        'oldpwd.regex'  => '旧密码或支付密码由6到16位,只能有字母或数字',
        'newpwd.require'  => '新密码或新支付密码不能为空',
        'newpwd.regex'  => '新密码或新支付密码由6到16位,只能字母或数字',
        'email.require'        => '邮箱不能为空',
        'card.require'   => '银行卡不能为空',
        'card.regex'  => '请正确输入银行卡15-19位',
        'info.require'  =>'搜索参数错误',
    ];
    protected $scene = [
        'login'   =>  ['username','password'],//登录
        'register'  =>  ['username','password','name','qq','id'],//注册
        'editqq'  =>  ['qq'],//修改QQ
        'editpwd'  =>  ['oldpwd','newpwd'],//修改密码
        'editCard'  =>  ['card'],//添加银行卡
        'editnwithdrawalpwd' => ['oldpwd','newpwd'],//修改支付密码
        'getUserInfo' => ['info'],//查询用户资料
    ];
}