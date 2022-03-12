<?php
namespace app\psi\model;
use app\mainmenu\model\ModelBase;

class PsiOne extends ModelBase
{
    protected function initialize()
    {
        parent::initialize();
    }
    public function listone($where=[],$name='',$b=''){
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
    public function selpsione($name='',$b='',$where=[],$data='',$field=''){
        $field='a.class_one,a.terminal_name,a.person,a.company,a.name';
        return $this->sellist($name,$b,$where,$data,$field);
    }
}