<?php
namespace app\actualsales\controller;
use think\facade\Request;
use app\mainmenu\controller\Common;
use app\actualsales\model\ActualSalemode;

class ActSales extends Common{

    protected function initialize()
    {
        parent::initialize();
    }
    public function acslist(){
        $acsale = new ActualSalemode;
        if($this->request->isGet()){
            $res = (new ActualSalemode)->getaslist();
            return self::returns(200,'success',$res);
        }else{
            $data = $this->requests();
            var_dump($data);die;
        }

//        if($res){
//            return self::returns(200,'success',$res);
//        }
    }
}