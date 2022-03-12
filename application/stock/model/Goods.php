<?php
namespace app\stock\model;
use app\mainmenu\model\ModelBase;
use think\Db;
class Goods extends ModelBase
{
    
    protected function initialize()
    {
        parent::initialize();
    }
//    public fu nction stockdirection(){
//        return $this->hasOne('StockDirection');
//    }
    /**
     * @param array $where condition
     * @param string $order orderBy
     *
     */
    public function getlistgoods($where=[],$order=''){
            $list =Db::name("$this->name")->alias('a')->leftJoin('division b','a.prescription=b.bid')
                   ->leftJoin('class c','a.class=c.cid')->field('a.*,b.prescription,c.class')->order('a.id','desc')->where($where)->select();
            return $list;
    }

    /**
     * @param array $where
     * 真实删除
     */
    public function listdelone($where=[]){
        return Goods::destroy($where);
    }

    /**
     * @param array $data
     * @param string $field
     */
    public function listedit($data=[]){
        $ress = (new Goods)->where('id',$data['id'])->update($data);
        return $ress;
    }

    /**
     * @param array $data
     * @param null $sequence
     */
    public function listadd($data=[],$sequence = null,$field=true){
        $result = $this->allowField($field)->isUpdate(false)->insertGetId($data);
        return $result;
    }
}