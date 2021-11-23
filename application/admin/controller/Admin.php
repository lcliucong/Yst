<?php 
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Admin extends Controller
{
    public  function adminList(){
        return $this->fetch('admin-list');
    }
}
?>