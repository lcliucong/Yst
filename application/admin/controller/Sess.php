<?php 
namespace app\admin\controller;
use think\Controller;
use think\Session;

class Sess extends Controller
{
    public function s1(){
        session::set('name','张三');
    }
    public function s2(){
        $res = session::get('name');
        echo $res;
    }
}














?>