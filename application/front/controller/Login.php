<?php
namespace app\front\controller;
use think\Controller;
use think\Request;
use app\front\model\User;
use think\Loader;
use think\Session;
class Login extends Controller
{
    protected $loginway = [
        'username',
        'user_nickname'
    ];
    public function Reg(Request $request){
        header("Access-Control-Allow-Origin:*");
        header('Access-Control-Allow-Methods:POST,GET');
        header('Access-Control-Allow-Headers:x-requested-with, content-type');
            $data = $request->param();


//            $get = User::get(['username'=>$data['username']]);
//            if($get){
//                return json(['code'=>2,'message'=>'sorry,您已经注册过了']);
//            }else{
                //$validate = Loader::validate('Login');
             //   $result = $validate->scene('reg')->check($data);
               // if($result){
                    $user = new user();
                    $res = $user->data([
                        'username'=>$data['username'],
                        'password'=>$data['password']
                        //'user_nickname'=>$data['user_nickname']
                    ])->save();
                    if($res){
                        return json(['code'=>123,'message'=>'注册成功,即将为您跳转到登陆页面']);
                    }else{
                        return json(['code'=>234,'message'=>'注册失败']);
                    }
//                }else{
//                    return json(['code'=>2,'message'=>$validate->getError()]);
//                }
//            }
    }
    public function login(Request $request){
        header("Access-Control-Allow-Origin:*");
        header('Access-Control-Allow-Methods:POST,GET');
        header('Access-Control-Allow-Headers:x-requested-with, content-type');
            $data = $request->param();
            $validate = Loader::validate('Login');
            //$result = $validate->scene('login')->check($data);
//            if($result){
//                foreach ($this->loginway as $k=>$v){
//                    $admin = new Admin;
//                    $res1 =$admin->where($v,$data['user_phone'])->where('user_password',$data['user_password'])->find();
//                    if($res1){
//                        break;
//                    }
//                }
                $user = new User;
                $res1 = $user->where('username',$data['username'])->where('password',$data['password'])->find();
                if($res1){
                    Session::set('user',$res1);
                    return json(['code'=>1,'message'=>'登录成功']);
                }else{
                    return json(['code'=>2,'message'=>'查询不到您的账号数据或密码错误']);
                }
//            }else{
//                return json(['code'=>2,'message'=>$validate->getError()]);
//            }
    }
}