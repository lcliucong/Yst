<?php
namespace app\mainmenu\model;

use app\mainmenu\model\ModelBase;

class Mainmenu extends ModelBASE
{
    /**
     * createBy phpstorm
     * auth : lc
     * Date : 2021/11/19
     * Time : 15:40
     */

    protected $resultSetType = 'collection';
    
    public $autoWriteTimestamp=true;
    /**
     * @var mixed|string
     */
    /***
     * @param $data
     * @param int $pid
     * @return array
     * if($v['pid']=$pid) 第一次循环:此时$v['pid']=0;这样就会得到所有的一级菜单。$children[$k]=$v，让children的K等于得到的数据。
     * 开始循环刚被赋值一级菜单的children数组，foreach ($children as $k1=>$v1)，$v1['children'] =   $this->sort($data,$v1['id']);
     * 让children的$v['children']，也就是每条的子级children数组， 等于 $this->sort($data,$v1['id']); 再次调用本方法实现递归。
     * $v1['children'] =   $this->sort($data,$v1['id']); 让每个一级菜单的子级children数组，走第二次循环，第二次循环开始，将$children数组定义为空，
     * 此时 if($v['pid']==$pid)就是$data[$v]的父级id 是否等于$children[$v1]的id(也就是children数组中一级菜单的id);如果等于，让children的$k = 这条数据，也就是$k=每个一级菜单的子级。
     * 然后继续循环$children，如此往复。
     */

    public function mcatetree()
    {
        $res = $this->where('status', 1)->order('mid', 'asc')->all();
        $data = $this->sortss($res,$pid = 0);
        $this->toarr($data);
        return $data;
    }
    public function catetrees()
    {
        #$res = Db::name('mainmenu')->where('status', 1)->column('id,pid,name,iconsize,title,micon');
        $res = mM::where('status', 1)->order('mid', 'asc')->all();
        $data = $this->sortss($res,$pid = 0);
        $this->toarr($data);
        return json(['code'=>1,'message'=>'success','data'=>$data]);
        exit;
    }
    public function sortss($data,$pid=0)
    {
        //定义一个数组，用来存储递归后的数据
        $children = [];
        foreach ($data as $k => $v){
            //$v 每个元素的整个数组
            if($v['pid']==$pid){   //查询一级菜单
                $children[] = $v;   //写入进数组
                foreach ($children as $k1=>$v1){  //循环children 第一次肯定是父ID=0的时候，这样他会得到children的k键名和V键值
                    $v1['children'] =   $this->sortss($data,$v1['mid']); // 让children的子级=$this->sort(data,pid=$v1['id']) 此时，$v1['children']就会循环$v1
                }
            }
        }

        return $children;
    }
    public function sorts($data,$pid=0)
    {
        //定义一个数组，用来存储递归后的数据
        $children = [];
        foreach ($data as $k => $v){
            //$v 每个元素的整个数组
            if($v['pid']==$pid){   //查询一级菜单
                $children[$k] = $v;   //写入进数组
                foreach ($children as $k1=>$v1){  //循环children 第一次肯定是父ID=0的时候，这样他会得到children的k键名和V键值
                    $v1['children'] =  $this->sorts($data,$v1['mid']); // 让children的子级=$this->sort(data,pid=$v1['id']) 此时，$v1['children']就会循环$v1
                }
            }
        }
        return $children;
    }
}