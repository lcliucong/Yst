<?php
namespace app\admin\controller;


use think\Controller;

use think\Request;

use think\Session;

use app\admin\model\Ystheader;

use app\admin\model\Admin as admins;

class Admin extends Basic
{
 
   public function index()
    {

        $data = session('user_nickname');
  
      $ystheader = new Ystheader;
//
        $list = $ystheader->where('')
   
     return $this->fetch('index',['data'=>$data]);
    }
 
   public function welcome(){
   
     return $this->fetch('welcome');
   
 }
    public function welcome1(){
        return $this->fetch(('welcome1'));
    }
    public function admininfo(Request $request){
       $data = $request->param();
       $datas = Session::get('id');
        $admins = new Admins;
        return $this->fetch('admininfo');
    }
}
