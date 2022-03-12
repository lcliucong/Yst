<?php
namespace app\actualsales\model;
use app\mainmenu\model\ModelBase;

class ActualSalemode extends ModelBase{

    protected function initialize()
    {
        parent::initialize();
    }
    public function getaslist($where=[]){
        return $this::where($where)->order('id desc')->select()->toArray();
    }

}