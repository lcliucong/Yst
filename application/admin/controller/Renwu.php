<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use app\common\Common;
use think\facade\Cache;
use think\facade\Session;
class Renwu extends Common{
    public function lst(){
            $res=db('renwu')->select();
            return json(['code'=>200,'data'=>$res]);
    }
    public function add(){
        $data=input('');
        $res=db('renwu')->strict(false)->insert($data);
        if($res){
            return json(['code'=>200,'message'=>'success']);
        }else{
            return json(['code'=>0,'message'=>'失败']);
        }
    }
    public function delete(){
        $del=input('id');
        if(is_array($del)){
            foreach ($del as $dela){
                $rel=db('renwu')->where('id',$dela)->delete();
            }
        }else{
            $rel=db('renwu')->where('id',$del)->delete();
        }
        if(($rel>=1)){
            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'失败']);
        }

    }
    public function update(){
        $id=input('');
        $rel=db::name('renwu')->strict(false)->update($id);
        if($rel){
            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'失败']);
        }
    }
}
