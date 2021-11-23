<?php
namespace app\admin\controller;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch('index');
    }
    public function welcome(){
        return $this->fetch('welcome');
    }
    public function adminInfo(){
        return $this->fetch('admin-edit');
    }
}
