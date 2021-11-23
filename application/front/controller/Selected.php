<?php
namespace app\front\controller;
use think\Request;
use think\Controller;
use app\front\model\Selected as sel;
class Selected extends Controller
{
    #通用returnres
    public function returnres($code='',$msg="",$data=''){
        return json_encode(array(
            "code"=>$code,
            "msg"=>$msg,
            "data"=>$data
        ));
    }
    public function sel(Request $request){
        header("Access-Control-Allow-Origin:*");
        header('Access-Control-Allow-Methods:POST,GET');
        header('Access-Control-Allow-Headers:x-requested-with, content-type');
        $data = $request->param();
        if($data){
            $get = Sel::get(['att_phone'=>$data['att_phone']]);
            if($get){
                    return $this->returnres(1,'查询成功',$get);
            }else{
                    return $this->returnres(2,'sorry~暂时查询不到您的信息');
            }
        }else{
            return $this->returnres(2,'接收错误');
        }
    }
}