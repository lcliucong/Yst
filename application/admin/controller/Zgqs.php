<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use app\admin\model\Zgqs as Qs;
class Zgqs extends Controller
{

    public function sonlist(Request $request){
        if($request->isGet()){
            $qs = new Qs;
            $list = Qs::all();
            return $this->fetch('zgqs',['data'=>$list]);
        }else{

        }
    }
    public function status(Request $request){
        $data = $request->param();
        $qs =new Qs;
        $res = $qs->save(['status'=>$data['status']],['id'=>$data['id']]);
        if($res){
            return $this->returnres(1,'修改成功');
        }else{
            return $this->returnres(2,'修改失败');
        }
    }  //多图上传
    public function imgadds(Request $request)
    {
        header("Content-Type:text/html;charset=utf8");
        header("Access-Control-Allow-Origin: *"); //解决跨域
        header('Access-Control-Allow-Methods:POST');// 响应类型
        $file = request()->file('image');
        if ($file) {
            $ipt = input('hidid');
            $name = date("Ymd") . rand(1000, 9999);
            $info = $file->move('images/' . date("Ymd") . "/", $name);
            if ($info) {
                $file = $info->getSaveName();
                $url = 'http://yst.kuguoyunfu.com/public/' . 'images/' . date("Ymd") . "/";
                $sqlurl = 'http://yst.kuguoyunfu.com/' . 'images/' . date("Ymd") . "/";
                $files = $sqlurl . $file;
                $qs = Qs::get($ipt);
                $qs->image=$files;
                $res = $qs->save();
                if($res){
                    echo $this->success('图片上传成功','sonlist');
                }
            } else {
                echo $this->returnres(1, "图片上传失败");
                exit;
            }
        }
    }
    public function qsadd(Request $request){
        if($request->isGet()){
            return $this->fetch('qsadd');
        }else{
            $data = $request->param();
            if(!$data){
                echo $this->returnres(2,'获取失败');
            }else{
                if(isset($data['status'])){
                    $status=$data['status'];
                    $qs = new Qs;
                    $res = $qs->data([
                        'title'=>$data['title'],
                        'content'=>$data['content'],
                        'auth'=>$data['auth'],
                        'status'=>$status
                    ])->save();
                    if($res){
                        $this->success('添加成功');
                    }else{
                        $this->error('添加失败');
                    }
                }else{
                    $status = ['status'=>0];
                 
                    $qs = new Qs;
                    $res = $qs->data([
                        'title'=>$data['title'],
                        'content'=>$data['content'],
                        'auth'=>$data['auth'],
                        'status'=>$status['status']
                    ])->save();
                    if($res){
                        $this->success('添加成功');
                    }else{
                        $this->error('添加失败');
                    }
                }

            }
        }
    }
    public function qsedit(Request $request){
        if($request->isGet()){
            $id = $request->param();
            $qs = new Qs;
            $data = $qs::get($id);
            return $this->fetch('qsedit',['data'=>$data]);
        }else{
            $data =$request->param();
            $qs = new Qs();
            $result = $qs->save($data,['id'=>$data['id']]);
            if($result){
                echo $this->success('修改成功');
            }else{
                echo $this->error('修改失败');
            }
        }
    }
    public function qsdel(Request $request){
        $id = $request->param();
        $res = Qs::destroy($id['id']);
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