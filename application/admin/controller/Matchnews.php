<?php
namespace app\admin\controller;

use think\Controller;
use think\Loader;
use think\Request;
use app\admin\model\Matchnews as mNews;
class Matchnews extends Controller
{
    #####查询
    public function matchnews(Request $request){
        $mNews = new mNews;
        if($request->isGet()){
            $list = mNews::all();
            return $this->fetch('matchnewslist',['data'=>$list]);
        }else{
            $data = $request->param();
            $res = $mNews->where('news_title','like','%'.$data['news_title'].'%')->select();
            if($res){
                return $this->fetch('matchnewslist',[$data=>$res]);
            }else{
                return ('暂无数据');
            }
        }
    }
    public function newsadds(Request $request){
        if($request->isGet()){
            return $this->fetch('newsadd');
        }else{
            $data = $request->param();
            if(!$data){
                echo $this->returnres(2,'获取失败');
            }else{
                $validate = Loader::validate('Matchnews');
                if(!$validate->check($data)){
                    $this->error($validate->getError());
                }else{
                    $mNews = new mNews;
                    $res = $mNews->data([
                        'news_title'=>$data['news_title'],
                        'news_content'=>$data['news_content'],
                        'news_auth'=>$data['news_auth'],
                        #'images'=>$_FILES['file']['name']
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
                $mnews = mNews::get($ipt);
                $mnews->news_images=$files;
                $res = $mnews->save();
                if($res){
                    echo $this->success('图片上传成功','matchnews');

                }
            } else {
                echo $this->returnres(1, "图片上传失败");
                exit;
            }
        }
    }
    public function newsedit(Request $request){
        if($request->isGet()){
            $id = $request->param();
            $msult = mNews::get($id);
            return $this->fetch('newsedit',['li'=>$msult]);
        }else{
            $data = $request->param();
            if(isset($data['type'])  &&  $data['type']==='update'){
                $mNews = new mNews();
                $result = $mNews->save($data,['id'=>$data['id']]);
                if($result){
                    echo $this->success('修改成功');
                }else{
                    echo $this->error('修改失败');
                }

            }else{
                $res = mNews::update($data);
                if($res) {
                    echo $this->success('修改成功');
                }else{
                    echo $this->error('修改失败');
                }
            }
        }
    }
    public function newsdel(Request $request){
        $id = $request->param();
        $res = mNews::destroy($id['id']);
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