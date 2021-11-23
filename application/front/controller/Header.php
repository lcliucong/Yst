<?php
namespace app\front\controller;
use think\Controller;
use app\front\model\Ystheader;
use think\Request;
class Header extends Controller
{

    public function header(Request $request)
    {
        header("Access-Control-Allow-Origin:*");
        header('Access-Control-Allow-Methods:POST,GET');
        header('Access-Control-Allow-Headers:x-requested-with, content-type');
        $ystheader = new YstHeader;
        $data = $ystheader::all();
        if($data){
             return $this->returnres(1,'查询成功',$data);
        }else{
            return $this->returnres(2,'查询失败');
        }
    }
    public function head(){
        $ystheader = new YstHeader;
        $datas = $ystheader::all();
        if($datas){
            return $this->returnres(1,'查询成功',$datas);
           # $this->assign('datas',$datas);

        }else{
            return $this->returnres(1,'查询失败');
        }
    }
    public function returnres($code='',$msg="",$data=''){

        return json_encode(array(
            "code"=>$code,
            "msg"=>$msg,
            "data"=>$data
        ));
    }
}
