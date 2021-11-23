<?php
namespace app\front\validate;
use think\Validate;

class Login extends Validate
{
    protected $rule = [
        'username|手机号' =>'require',
        'password|密码' =>'require|length:6,12',
        //'user_repassword|确认密码'=>'require|confirm:user_password',
        'user_nickname|用户名'=>'require|length:4,12'
    ];
    protected $message = [
        'username.require' =>'请输入手机号',
        #'user_phone.length' =>'手机号长度错误',
        'password.require'=>'请输入密码',
        'password.length'=>'密码长度必须在6-12位之间',
//        'user_repassword.require'=>'请输入确认密码',
//        'user_repassword.confirm'=>'密码和确认密码不一致',
//        'user_nickname.require'=>'请输入您的用户名',
//        'user_nickname.length'=>'用户名长度必须在4-12位中间'
    ];
    protected $scene = [
        'login'   =>  ['username','password'],
        'reg'  =>  ['username','password']
    ];
}
?>