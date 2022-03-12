<?php
namespace app\delivery\model;

use app\mainmenu\model\ModelBase;

class Option extends ModelBase
{
    protected function initialize()
    {
        parent::initialize();
    }
    public function busslist(){
        return $this->order('id asc')->where('mark',0)->select()->toArray();
    }
    public function drugslist(){
        return $this->order('id asc')->where('mark',1)->select()->toArray();
    }

    /**
     * @param string $data
     * @return mixed
     */
    public function addoplist($data){
        return $this->save([
            'business_unit_1'=>$data,
            'mark'=>'0'
        ]);
    }

    /**
     * @param string $data
     * @return mixed
     */
    public function addoplist2($data){
        return $this->save([
            'fac_name_1'=>$data['fac_name_1'],
            'fac_specs_1'=>$data['fac_specs_1'],
            'factory_name_1'=>$data['factory_name_1'],
            'mark'=>'1'
        ]);
    }
}