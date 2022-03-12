<?php
namespace app\stock\model;
use app\mainmenu\model\ModelBase;

class StockDirection extends ModelBase
{
    protected function initialize()
    {
        parent::initialize();
    }
    /**
     * @param array $where
     * @return mixed
     *
     */
    public function getstocklist($where=[]){
        $direct = new StockDirection;
        $res = $direct->alias('a')//->leftjoin('goods b','a.goods_id=b.id')->
        ->where($where)->field('a.*')->order('a.id','desc')->select()->toArray();
        return $res;
    }
    /**
     * @param array $where
     * 联动真实删除
     */
    public function stdiredel($where=[]){
        return StockDirection::where('goods_id','in',$where)->delete();
    }
    /**
     * @param array $where
     * 删除本表
     */
    public function stdel($where=[]){
        return StockDirection::where('id','in',$where)->delete();
    }
    /**
     * @param array $data
     * @param string $field
     */
    public function listedit($data=[]){
        $ress = (new Goods)->where('id',$data['id'])->update($data);
        return $ress;
    }
}