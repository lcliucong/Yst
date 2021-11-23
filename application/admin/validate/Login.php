<?php 
namespace app\admin\validate;
use think\Validate;

class Login extends Validate
{
    protected $rule = [
        'admin_phone|手机号' =>'require|length:11|number',
        'admin_password|密码' =>'require|length:6,12',
        'admin_repassword|确认密码' =>'require|confirm:admin_password',
        'admin_nickname|昵称' =>'require|length:2,12|chsAlphaNum',
        'admin_email|邮箱' =>'require|email'
    ];
    protected $message = [
        'admin_phone.require' =>'手机号不能为空',
        'admin_phone.length' =>'手机号长度错误',
        'admin_password.require' =>'请输入密码',
        'admin_password.length' =>'密码长度必须在6-12个字符之间',
        'admin_repassword.require' =>'请输入确认密码',
        'admin_repassword.confirm' =>'密码和确认密码不一致',
        'admin_nickname.require' => '请输入昵称',
        'admin_nickname.length' => '昵称长度必须在2-12个字符之间',
        'admin_nickname.chsAlphaNum' => '昵称格式必须为汉字，英文或数字',
        'admin_email.require'=>'请输入邮箱',
        'admin_email.email'=>'邮箱格式错误'
    ];
    protected $scene = [
        'login'=>['admin_phone','admin_password'],
        'reg'=>['admin_phone','admin_password','admin_repassword','admin_nickname','admin_email'],
        'repass'=>['admin_phone','admin_password','admin_repassword']
    ];
}




?>