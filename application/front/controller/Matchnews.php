<?php
namespace app\front\controller;
use think\Controller;
use think\Request;
use app\front\model\Matchnews as mNews;
class Matchnews extends Controller
{
    #通用returnres
    public function returnres($code='',$msg="",$data=''){
        return json_encode(array(
            "code"=>$code,
            "msg"=>$msg,
            "data"=>$data
        ));

    }
    public function matchnews(Request $request){
        header("Access-Control-Allow-Origin:*");
        header('Access-Control-Allow-Methods:POST,GET');
        header('Access-Control-Allow-Headers:x-requested-with, content-type');
        $mNews = new mNews;
        $data = $mNews::all();
        if($data){
           return $this->returnres(1,'查询成功',$data);
        }else{
           return $this->returnres(2,'查询失败');
        }
    }
    public function matchnewsbot(Request $request){
        header("Access-Control-Allow-Origin:*");
        header('Access-Control-Allow-Methods:POST,GET');
        header('Access-Control-Allow-Headers:x-requested-with, content-type');
        $mNews = new mNews;
        $data = $mNews->limit(6)->select();
        if($data){
            return $this->returnres(1,'查询成功',$data);
        }else{
            return $this->returnres(2,'查询失败');
        }
    }
}