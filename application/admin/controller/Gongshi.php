<?php
namespace app\admin\controller;
use think\Db;
use PHPExcel;
use think\facade\Request;
use app\mainmenu\controller\Common;
use think\Collection;

class Gongshi extends Collection{
    public function lst(){
        $data=db('gongshi')->select();
        if(!empty($data))
            return json(['code'=>200,'data'=>$data]);
        else
            return json(['code'=>0,'data'=>'没内容']);
    }
    public function add(){
        $data['daibiao']=input('daibiao');
        $data['bumen']='基层预算部';
        $data['content']='开喉剑喷雾剂儿童20ml不满足四盒不算进货';
        $rel=db('gongshi')->insert($data);
        if($rel)
            return json(['code'=>200]);
        else
            return json(['code'=>0,'mes'=>'添加失败']);
    }
    public function edit(){
        $data['id']=input('id');
        $data['daibiao']=input('daibiao');
        $rel=db('gongshi')->update($data);
        if($rel)
            return json(['code'=>200]);
        else
            return json(['code'=>0,'mes'=>'修改失败']);
    }
    public function del(){
        $id=input('id');
        $rel=db('gongshi')->delete($id);
        if($rel)
            return json(['code'=>200]);
        else
            return json(['code'=>0,'mes'=>'删除失败']);
    }
}
