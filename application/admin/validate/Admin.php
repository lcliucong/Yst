<?php
namespace app\admin\validate;
use think\Validate;

class Admin extends Validate{
    protected $rule = array(
        'username' => 'require|max:10|unique:admin', // 必填、最长10个字符、不能重复
        'password'=>'require|max:10'

    );

    protected $message = array(
        'username.require'=>'账号必填',
        'username.max'=>'账号太长了',
        'username.unique'=>'账号重复',
        'password.require'=>'密码必填',
        'password.max'=>'密码太长了'
    );

    protected $scene = array(
        'add' => array('username','password'),
        'edit'=>array('password','username')
    );

}