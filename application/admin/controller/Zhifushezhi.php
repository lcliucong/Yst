<?php
namespace app\admin\controller;
use think\Controller;
use app\common\Common;
use think\Db;

class Zhifushezhi extends Common{
    public function zhifushezhi(){
        $data=db('zhifushezhi')->select();
        if($data){
            return json(['code'=>200,'mes'=>'成功','data'=>$data]);
        }else{
            return json(['code'=>0,'mes'=>'失败']);
        }
    }
    public function zhifushezhiedit(){
        $id=input('id');
        $data['abbiaozhun']=input('abbiaozhun');
        $data['lunwenfei']=input('lunwenfei');
        $data['daibiaojiangjinticheng']=input('daibiaojiangjinticheng');
        $data['jinglijiangjinticheng']=input('jinglijiangjinticheng');
        $data['zhuguanjiangjinticheng']=input('zhuguanjiangjinticheng');

        $res=db('zhifushezhi')->where('id',$id)->update($data);


        if($res){
            return json(['code'=>200,'mes'=>'成功']);
        }else{
            return json(['code'=>150,'mes'=>'未修改']);
        }
    }
}