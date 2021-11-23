<?php
namespace app\admin\controller;
use think\Controller;
use think\Loader;
use think\Request;
use app\admin\model\Reviewer;
class Review extends Controller
{
    public function reviewer(Request $request){
        $reviewer = new Reviewer;
        $list = $reviewer::all();
        return $this->fetch('reviewer',['data'=>$list]);
    }
    public function revieweradd(Request $request){
        if($request->isGet()){
            return $this->fetch('revieweradd');
        }else{
            $data = $request->param();
            $file = request()->file();
            // 移动到框架应用根目录/public/uploads/ 目录下
            if($file){
                $info = $file->move(ROOT_PATH . 'public' . DS .'matchnews'. DS. 'imgs');
                if($info){
                    return json(['code'=>1,'message'=>'上传成功']);
                }else{
                    echo $file->getError();
                }
            }
            if(!$data){
                echo $this->returnres(2,'获取失败');
            }else{
                    $reviewer = new Reviewer;
                    $res = $reviewer->data([
                        'name'=>$data['name'],
                        'content'=>$data['content'],
                        'auth'=>$data['auth'],
                        #'images'=>$_FILES['file']['name']
                    ])->allowField(true)->save();
                    if($res){
                        $this->success('添加成功');
                    }else{
                        $this->error('添加失败');
                    }
            }
        }
    }
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
                $reviewer = Reviewer::get($ipt);
                $reviewer->image=$files;
                $res = $reviewer->save();
                if($res){
                    echo $this->success('图片上传成功','reviewer');
                }
            } else {
                echo $this->returnres(1, "图片上传失败");
                exit;
            }
        }
    }
    public function revieweredit(Request $request){
    if($request->isGet()){
        $id = $request->param();
        $msult = Reviewer::get($id);
        return $this->fetch('revieweredit',['li'=>$msult]);
    }else{
        $data = $request->param();
        if(isset($data['type'])  &&  $data['type']==='update'){
            $reviewer = new Reviewer();
            $result = $reviewer->save($data,['id'=>$data['id']]);
            if($result){
                echo $this->success('修改成功');
            }else{
                echo $this->error('修改失败');
            }
        }else{
            $reviewer = new Reviewer;
            $res = $reviewer->update($data);
            if($res) {
                $this->success('修改成功');
            }else{
                $this->error('修改失败');
            }
        }
    }
}
    public function reviewerdel(Request $request){
        $id = $request->param();
        $res = Reviewer::destroy($id['id']);
        if($res){
            return json(['code'=>1,'message'=>'删除成功']);
        }else{
            return json(['code'=>2,'message'=>'删除失败']);
        }
    }
}