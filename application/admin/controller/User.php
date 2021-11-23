<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use app\admin\model\User as Usr;
class User extends Controller
{
    public function userlist(Request $request){
        $user = new Usr;
        $list = $user->field(['id','username','password'])->paginate(10);;
        $this->assign('list', $list);
    // 渲染模板输出
        return $this->fetch('userlist',['data'=>$list]);
    }
    public function useredit(Request $request){
        if(Request::instance()->isGet()){
            $id = Request::instance()->get();
            $user =new Usr;
            $data = $user::get($id);

           # return $this->assign('data',$data);
            return $this->fetch('',['data'=>$data]);
        }else{
            $data = Request::instance()->param();
            $user = new Usr;
            if(!$data){
                return $this->returnres(22,'数据获取失败');
            }else{
                $res = $user->where('id',$data['id'])->update($data);
                if($res){
                  $this->success('修改成功');
                }else{
                  $this->error('修改失败');
                }
            }
        }
    }
    public function userdel(Request $request){
        $id = $request->param();
        $res = Usr::destroy($id['id']);
        if($res){
            return json(['code'=>1,'message'=>'删除成功']);
        }else{
            return json(['code'=>2,'message'=>'删除失败']);
        }
    }

    public function returnres($code='',$msg="",$data=array()){
        return json_encode(array(
            "code"=>$code,
            "msg"=>$msg,
            "data"=>$data
        ));
    }
}