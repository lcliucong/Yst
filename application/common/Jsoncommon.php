<?php
namespace app\common;

use think\Controller;
class Jsoncommon extends Controller
{
    public function returnres($code=0,$msg="",$data=array()){
        return json_encode(array(
            "code"=>$code,
            "msg"=>$msg,
            "data"=>$data
        ));
    }
    public function jsons($code,$msg='',$data=array()){
        $result = array(
            'code'=>$code,
            'msg'=>$msg,
            'data'=>$data
        );
        echo json_encode($result);
        exit;
    }
}