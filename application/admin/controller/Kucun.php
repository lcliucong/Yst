<?php

namespace app\admin\controller;
use think\Controller;
use think\Db;
use app\common\Common;
use think\facade\Cache;

class Kucun extends Common{
    public function kucunsearch(){
        $data=input('unit');
        $dataa=['unit','=',$data];
        $res=db('kucun')
            ->where([$dataa])
            ->select();

        if($res){
            return json(['code'=>200,'message'=>'成功','data'=>$res]);
        }else{
            return  json(['code'=>0,'message'=>'失败']);
        }
    }
    public function kucunsearchfan(){
        $data=input('unit');
        $dataa=['djid','=',4];
        $res=db('renwu')
            ->where([$dataa])
            ->select();

        $a=db('renwu')->select();

        foreach ($a as  $v) {
            $v=implode(',',$v);
            $temp1[]=$v;

        };
        foreach ($res as  $value) {
            $value=implode(',',$value);
            $temp2[]=$value;
        };
        $fdata=array_diff($temp1,$temp2);
        dump($fdata);
        if($res){
            return json(['code'=>200,'message'=>'成功','fdata'=>$fdata]);
        }else{
            return  json(['code'=>0,'message'=>'失败']);
        }

    }
}