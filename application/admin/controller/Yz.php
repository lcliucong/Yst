<?php 
namespace app\admin\controller;
use think\Controller;
use think\Loader;
use think\Validate;
use think\Request;

class Yz extends Controller{
    public function yz(){
        #模拟数据
        $data = [
            'username'=>'xiaoming',
            'password'=>'12345'
        ];
        #定义规则
        $rule = [
            'username'=>'require|max:12',
            'password'=>'require|min:6'
        ];
        #实例化验证类
        $validate = new Validate($rule);
        $res = $validate->check($data);
        #验证通过后的业务逻辑
        if($res){
            var_dump();
        }else{
                #不通过的业务逻辑  //具体错误
                $errmsg = $validate->getError();
                var_dump($errmsg);
            }
        
    }
    public function test(Request $request){
        #模拟数据
        $data = [
            'username'=>'xiaoming',
            'password'=>'123456',
            'repassword'=>'123456'
        ];
        #导入验证器
        $validate = Loader::validate('Yz');
        $res = $validate->check($data);
        if($res){
            echo 'yes';
        }else{
            var_dump($validate->getError());
        }
    }
    public function test1(){
        #模拟数据
        $data = [
            'username'=>'xiaoming1111111',
            'password'=>'123451',
            'repassword'=>'123456'
        ];
        #导入验证器
        $validate = Loader::validate('Yz');
        $res = $validate->check($data);
        if($res){
            
        }else{
            var_dump($validate->getError());
        }
    }
    public function scen(){
        $data = [
            'username'=>'xiaoming',
            'password'=>'1234561',
            'repassword'=>'123456'
        ];
        $validate = Loader::validate('Yz');
        $res = $validate->scene('login')->check($data);
        if($res){
            echo '验证通过';
        }else{
            $errmsg = $validate->getError();
            var_dump($errmsg);
        }
    }
}





?>