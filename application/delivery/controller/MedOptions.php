<?php
namespace app\delivery\controller;
use app\delivery\model\Option;
use app\mainmenu\controller\Common;

class MedOptions extends Common{
    protected function initialize()
    {
        parent::initialize();
    }
    public function oplist(){
        $bussunit = (new Option)->busslist();
        $drugslist = (new Option)->drugslist();
        return self::returns(200,'success',[['busslist'=>$bussunit],['medlist' => $drugslist]]);
    }
    public function opadd(){
        $data = $this->requests();
        if (isset($data['type1'])){
            $res = (new Option)->addoplist($data['type1']);
        }else{
            $res = (new Option)->addoplist2($data['type2'][0]);
        }
        if($res){
            return self::returns(200,'success');
        }
    }
    public function opdel(){
        $opt = new Option;
        $data = $this->requests();
        $res = $opt->listdel($opt,$data['id']);
        if($res){
            return self::returns(200,'success');
        }
    }
    public function opEdit(){
        $datas = $this->requests('edit');
        foreach ($datas[0] as $k=>$v){
            $data[$k] = trim($v);
        }
        $res = (new Option)->editlist(new Option,$data);
        if($res){
            return $this->returns(200,'success');
        }
    }
}