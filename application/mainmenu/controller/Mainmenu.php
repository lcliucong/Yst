<?php
namespace app\mainmenu\controller;
use app\mainmenu\model\Mainmenu as mM;
use think\Db;
use app\mainmenu\controller\Common;
use think\facade\Hook;

class Mainmenu extends Common
{
    protected $request;

    protected function initialize()
    {
        parent::initialize();

    }
    public function catetree(){
//        dump($this->request->file());die;
        $mM = new mM;
        Hook::add("run","app\\admin\\behavior\\LogHook");
        $data = $mM->mcatetree();
//        Hook::listen('run','my hook:');
        return json(['code'=>1,'message'=>'success','data'=>$data]);
        exit;
    }
    public function listout(){
        $orders = $this->requests();
        if($orders){
            $show = Db::name('goods')->where($orders)->select();
            if($show){
                return json(['code'=>1,'message'=>'success','data'=>$show]);
            }else{
                return json(['code'=>1,'message'=>'success','data'=>$show]);
            }
        }
    }


}