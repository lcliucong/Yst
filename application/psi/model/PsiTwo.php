<?php
namespace app\psi\model;
use app\mainmenu\model\ModelBase;

class PsiTwo extends ModelBase
{
    protected function initialize()
    {
        parent::initialize();
    }
    public function listtwo($where=[],$name='',$b=''){
        return $this->getlist($where,$name,$b);
    }
    /**
     * @param string $name  本表名称
     * @param string $b 指代goods表
     * @param array $where 查询条件 null
     * @param string $data 接收数据
     * @param string $field 字段名称
     * @return mixed
     */
    public function selpsitwo($name='',$b='',$where=[],$data='',$field=''){
        $field='a.name,a.distrib_two,a.upper_level,a.terminal_name,a.responsibler';
        return $this->sellist($name,$b,$where,$data,$field);
    }
}