<?php
namespace app\admin\controller;
use think\Db;
use PHPExcel;
use think\facade\Request;
use app\mainmenu\controller\Common;
use think\Collection;

class Zhuguan extends Common{
    public function zhuguan(){
        $data=db('zhifu')->field('diqu,bumen,bumenjingli,zhuguan,avg(wanchenglv)')
            ->group('zhuguan')
            ->select();
        dump($data);
    }
}