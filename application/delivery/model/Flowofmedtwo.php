<?php
namespace app\delivery\model;
use app\mainmenu\model\ModelBase;

class Flowofmedtwo extends ModelBase{
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
    public function fltwolist(){
        return $this->order('id desc')->select()->toArray();
    }
}