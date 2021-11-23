<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use app\admin\model\Admin;
use think\Loader;
use think\Session;
class Login extends Controller
{
    protected $loginway = [
        'user_phone',
        'user_nickname'
    ];
    public function Reg(Request $request){
        if($request->isGet()){
            return $this->fetch('reg');
        }else{
            $data = $request->param();
            $get = Admin::get(['user_phone'=>$data['user_phone']]);
            if($get){
                return json(['code'=>2,'message'=>'sorry,您已经注册过了']);
            }else{
                $validate = Loader::validate('Login');
                $result = $validate->scene('reg')->check($data);
                if($result){
                    $admin = new Admin();
                    $res = $admin->data([
                        'user_phone'=>$data['user_phone'],
                        'user_password'=>base64_encode($data['user_password']),
                        'user_nickname'=>$data['user_nickname']
                    ])->save();
                    if($res){
                        return json(['code'=>1,'message'=>'注册成功,即将为您跳转到登陆页面']);
                    }else{
                        return json(['code'=>2,'message'=>'注册失败']);
                    }
                }else{
                    return json(['code'=>2,'message'=>$validate->getError()]);
                }
            }
        }
    }
        public function login(Request $request){
            if($request->isGet()){
                #get方式访问，渲染页面
                return $this->fetch('login');
            }else{
                $data = $request->param();
                $validate = Loader::validate('Login');
                $result = $validate->scene('login')->check($data);
                if($result){
                    $admin = new Admin;
                    foreach ($this->loginway as $k=>$v){
                        $res1 =$admin->where($v,$data['user_phone'])->find();

                        if($res1){
                            break;
                        }
                    }
                    if(!$res1){
                        return json(['code'=>22,'message'=>'或许您还没有注册']);
                    }

                    if($res1==NULL){
                        return json(['code'=>22,'message'=>'请输入手机号或者用户名']);
                    }
                    if ($res1){
                        if($res1['code']==0){
                            if(time()-$res1['err_time']>20){
                                $res1->code=1;
                                $res1->save();
                            }else{
                                return json(['code'=>2,'message'=>'您已被锁定']);
                            }
                        }

                        $pwd = base64_decode($res1['user_password']);
                        if($data['user_password']==$pwd){
                            Session::set('user',$res1);
                            return json(['code'=>1,'message'=>'登录成功!']);
                        }else{
                            $user = $admin::get($res1['id']);
                            if($user['err_count']<3) {
                                $user->err_count++;
                                $user->save();
                            }else{
                                $user->err_time=time();
                                $user->code=0;
                                $user->err_count=0;
                                $user->save();
                                return json(['code'=>4,'message'=>'您已经输错三次密码了，已被停用，请20秒后再试']);
                            }
                            return json(['code'=>2,'message'=>'sorry~查询不到您的账号数据或密码错误']);
                        }
                    }
                }else{
                    return json(['code'=>2,'message'=>$validate->getError()]);
                }
            }
        }

        public function done(){
            $done = Session::clear();
            $this->error('退出成功','admin/index');
        }
}