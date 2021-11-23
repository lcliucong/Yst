<?php 
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Order extends Controller
{
    public function orderList(){
        return $this->fetch('order-list');
    }
    public function orderList1(){
        return $this->fetch('order-list1');
    }
}
?>