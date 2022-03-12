<?php
namespace app\delivery\model;
use app\mainmenu\model\ModelBase;

class Flowofmedone extends ModelBase{
    /**
     * createBy phpstorm
     * auth : lc
     * Date : 2021/11/25
     * Time : 15:40
     */
    protected function initialize()
    {
        parent::initialize();
    }
    public function flonelist(){
        return $this->order('id desc')->select()->toArray();
    }
}