<?php
namespace app\admin\controller;
use think\Controller;
use think\Loader;
use think\Request;
use app\admin\model\Excellentwork as exWork;

class Excellentwork extends Basic
{
    public function worklist(Request $request){
            $exwrok = new exWork;
            if($request->isGet()){
                $list = exWork::all();
                return $this->fetch('worklist',['data'=>$list]);
            }else{
                $data = $request->param();
                $res = $exwrok->where('work_retitle','like','%'.$data['work_retitle'].'%')->select();
                if($res){
                    return $this->fetch('worklist',['data'=>$res]);
                }else{
                    return ('暂无数据');
                }
            }
    }
    //多图上传防止图片重名，时间戳
    /*public function tim(){
        $time = time();
//        var_dump($time);
        echo substr($time,4);
    }*/
    //多图上传
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
                $exwork = exWork::get($ipt);
                $exwork->work_image=$files;

                $res = $exwork->save();
                if($res){
                    echo $this->success('图片上传成功','worklist');
                }
            } else {
                echo $this->returnres(1, "图片上传失败");
                exit;
            }
        }
    }
    public function workadd(Request $request){
        if($request->isGet()){
            return $this->fetch('workadd');
        }else{
            $data = $request->param();
            if(!$data){
                echo $this->returnres(2,'获取失败');
            }else{
                if(isset($data['work_status'])){
                    $workstatus=$data['work_status'];
                    $exWork = new exWork;
                    $res = $exWork->data([
                        'work_title'=>$data['work_title'],
                        'retitle'=>$data['retitle'],
                        'work_auth'=>$data['work_auth'],
                        'auth_class'=>$data['auth_class'],
                        'teacher_name'=>$data['teacher_name'],
                        'work_content'=>$data['work_content'],
                        'auth_describe'=>$data['auth_describe'],
                        'teacher_describe'=>$data['teacher_describe'],
                        'uploader'=>$data['uploader'],
                        'work_status'=>$workstatus
                    ])->save();
                    if($res){
                        $this->success('添加成功');
                    }else{
                        $this->error('添加失败');
                    }
                }else{
                    $workstatus = ['work_status'=>2];
                    $exWork = new exWork;
                    $res = $exWork->data([
                        'work_title'=>$data['work_title'],
                        'retitle'=>$data['retitle'],
                        'work_auth'=>$data['work_auth'],
                        'auth_class'=>$data['auth_class'],
                        'teacher_name'=>$data['teacher_name'],
                        'work_content'=>$data['work_content'],
                        'auth_describe'=>$data['auth_describe'],
                        'teacher_describe'=>$data['teacher_describe'],
                        'uploader'=>$data['uploader'],
                        'work_status'=>$workstatus['work_status']
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
    public function workedit(Request $request){
        if($request->isGet()){
            $id = $request->param();
            $msult = exWork::get($id);
            return $this->fetch('workedit',['li'=>$msult]);
        }else{
            $data = $request->param();
            if(isset($data['type'])  &&  $data['type']==='update'){
                $exWork = new exWork();
                $result = $exWork->save($data,['id'=>$data['id']]);
                if($result){
                    echo $this->success('修改成功');
                }else{
                    echo $this->error('修改失败');
                }
            }else{
                $res = exWork::update($data);
                if($res) {
                     $this->success('修改成功');
                }else{
                     $this->error('修改失败');
                }
            }
        }
    }
    public function workdel(Request $request){
        $id = $request->param();
        $res = exWork::destroy($id['id']);
        if($res){
            return json(['code'=>1,'message'=>'删除成功']);
        }else{
            return json(['code'=>2,'message'=>'删除失败']);
        }
    }
}

