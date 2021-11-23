<?php 
namespace app\admin\validate;
use think\Validate;

class Yz extends Validate{
    protected $rule = [
        'username|用户名'=> 'require|max:12',
        'password|密码'=>'require|min:6',
        'repassword|确认密码'=>'require|confirm:passowrd'
    ];
    protected $message = [
        'username.require'=>'请填写用户名',
        'username.max'=>'用户名最大为12位'
    ];
    protected $scene = [
        'login'=> ['username','password']
    ];
//     protected function repass($value,$rule,$data){
//         if($value==$data['repassword']){
//             return true;
//         }else{
//             return '密码不一致';
//         }
//     }
}






?>