<?php
namespace app\psi\model;
use app\mainmenu\model\ModelBase;

class Psi extends ModelBase
{
    protected function initialize()
    {
        parent::initialize();
    }
    public function lists($where=[],$name='',$b=''){
        $res = $this->getlist($where,$name,$b);
//        foreach ($res as $k=>$v){
//            $res[$k]['thismonth']=$res[$k]['goods_num']+$res[$k]['goods_num2'];
//        }
        return $res;
    }

    /**
     * @param string $name
     * @param string $b
     * @param array $where
     * @param string $data
     * @param string $field
     * @return mixed  此方法废弃 被selpsi取代
     */
    public function cs($name='',$b='',$where=[],$data='',$field=''){
        /**
         * @param string $name 本表名称
         * @param string $b 链接表明，本方法指代goods表
         * @param array $where
         * @param string $data requests参数
         * @param string $field 本表字段名称
         * @return mixed
         */
        $where=[
            'agent'
            ,'company'
        ];
            $result = $name->alias('a')->leftjoin("$b b",'a.goods_id=b.id')->
            where('a.goods_id','=',$data)->whereOr('b.name','like','%'.$data.'%')->
            whereOr("a."."$where[0]",'like','%'.$data.'%')->
            whereOr("a."."$where[1]",'like','%'.$data.'%')->
            field('a.*,b.name,b.specs')->order('a.id','desc')->select()->toArray();
            return $result;
    }

    /**
     * @param string $name 本表名称
     * @param string $b 指代goods表
     * @param array $where 查询条件 null
     * @param string $data 接收数据
     * @param string $field 字段名称
     * @return mixed  跳板方法
     */
    public function selpsi($name='',$b='',$where=[],$data='',$field=''){
        $field='a.company,a.agent,a.name';
        return $this->sellist($name,$b,$where,$data,$field);
    }
}