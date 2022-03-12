<?php
namespace app\admin\controller;
use think\Controller;
use app\common\Common;
use think\facade\Cache;

class Data extends Common{
//    public function  initialize(){
//
//        header('Access-Control-Allow-Origin: *');
//        header('Access-Control-Allow-Methods:POST,GET,OPTIONS,DELETE,PUT'); // 允许请求的类型
//        header('Access-Control-Allow-Credentials: true');
//        header('Access-Control-Allow-Headers:x-requested-with,Content-Type,X-CSRF-Token');
//        header('Access-Control-Allow-Headers: *');
//
//    }
    public function data(){
     
        $userid=input('userid');
   
        $name=input('name');
        $data=db('goods')->where('name',$name)->select();
        //dump($data);
        if($data){
            return json(['code'=>200,'message'=>'成功','data'=>$data]);
        }else{
            return json(['code'=>0,'message'=>'未查询到']);
        }
    }
}