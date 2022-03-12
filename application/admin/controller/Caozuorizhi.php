<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use app\common\Common;
use think\facade\Cache;

class Caozuorizhi extends Common{
    public function caozuorizhi(){

        $data=db('caozuojilu')->order('id','desc')->field('data,time')->select();
        if($data){
            return json(['code'=>200,'message'=>'成功','data'=>$data]);
        }else{
            return json(['code'=>0,'message'=>'失败']);
        }
    }
    public function edit(){

    }
    public function caozuorizhisearch(){
        $xingming=input('xingming');
//        $where[] = ['managername','like', '%'.$data.'%'];
//        $where[] =['telephone','like','%'.$telephone.'%'];

//        $manager=db('manager')
//            ->where([$where])
//            ->select();
        $xingming=Db::query("
            select *
            from tp51_caozuojilu c 
            where (c.data like '%$xingming%' )
            ");

        //var_dump($manager);

        if($xingming){
            return json(['code'=>200,'message'=>'成功','data'=>$xingming]);
        }else{
            if(empty($manager)){
                return json(['code'=>100,'message'=>'未查询到','data'=>[]]);
            }
            return  json(['code'=>0,'message'=>'失败']);
        }
    }
    public function qingkong(){

        $rel=db('caozuojilu')->delete(true);
        if($rel){
            return json(['code'=>200,'message'=>'成功','data'=>[]]);
        }else{
            return  json(['code'=>0,'message'=>'未删除','data'=>[]]);
        }
    }
}