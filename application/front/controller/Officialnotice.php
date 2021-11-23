<?php
namespace app\front\controller;

use think\Request;
use think\Controller;
use app\front\controller\HeadCommon;
use app\front\model\Officialnotice as ofalNotice;
class Officialnotice extends Controller
{
    #通用returnres
    public function returnres($code='',$msg="",$data=''){
        return json_encode(array(
            "code"=>$code,
            "msg"=>$msg,
            "data"=>$data
        ));

    }
    public function officialnotice(Request $request){
        header("Access-Control-Allow-Origin:*");
        header('Access-Control-Allow-Methods:POST,GET');
        header('Access-Control-Allow-Headers:x-requested-with, content-type');
        $officialnotice = new ofalNotice;
        $data = $officialnotice::all();
        if($data){
            return $this->returnres(1,'查询成功',$data);
        }else{
            return $this->returnres(2,'查询失败');
        }
    }

}