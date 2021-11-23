<?php 
namespace app\admin\controller;
use think\Controller;
use think\Loader;
use think\Request;
use app\admin\model\Admin;

class Login extends Controller
{
    ##############登录
    public function login(Request $request){
       if($request->isGet()){           
           return $this->fetch('login');
       }else{
               $data = $request->param();
               $validate = Loader::validate('Login');
               $result = $validate->scene('login')->check($data);
               if($result){
                   $res = Admin::get(['admin_phone'=>$data['admin_phone'],'admin_password'=>$data['admin_password']]);
                   if($res){
                       session('admin',$res);
                       return json(['code'=>1,'message'=>'登录成功']);
                   }else{
                       return json(['code'=>2,'message'=>'用户名不存在或密码错误']);
                   }
               }else{
                   $errmsg = $validate->getError();
                   return json(['code'=>2,'message'=>$errmsg]);
               }
       }
    }
    ##############注册
    public function Reg(Request $request){
        if($request->isGet()){
            return $this->fetch('reg');
        }else{
            #判断数据库中是否存在手机号
            $data = $request->param();
            $get = Admin::get(['admin_phone'=>$data['admin_phone']]);
            #存在
            if($get){
                return json(['code'=>2,'message'=>'sorry,您已经注册过了']);
            }else{
             #不存在
                $validate = Loader::validate('Login');
                $result = $validate->scene('reg')->check($data);
                if($result){
                    $admin = new Admin($data);
                    $res = $admin->allowField(true)->save();
                    if($res){
                        return json(['code'=>1,'message'=>'注册成功']);
                    }else{
                        return json(['code'=>2,'message'=>'注册失败']);
                    }
                    
                }else{
                    return json(['code'=>2,'message'=>$validate->getError()]);
                }
            }
           
        }
        
    }
    
    ###########忘记密码
    public function newPass(Request $request){
        if($request->isGet()){
            return $this->fetch('newpass');
        }else{
            $data = $request->param();
            $admin = Admin::get(['admin_phone'=>$data['admin_phone']]);
            if($admin){
                if($data['admin_password']!=$data['admin_repass']){
                    return json(['code'=>2,'message'=>'对不起，两次输入密码不一致']);
                }else{
                    $res = $admin->admin_password = $data['admin_password'];
                    return json(['code'=>1,'message'=>'修改成功']);
                }
               
            }else{
                return json(['code'=>2,'message'=>'对不起，您的手机号还未注册']);
            }
        }
       
    }
}













?>