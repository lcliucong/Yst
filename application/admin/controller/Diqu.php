<?php
namespace app\admin\controller;
use think\Controller;
use app\common\Common;
use think\facade\Cache;
class Diqu extends Common{

    public function diqu(){
        $userid=input('userid');
        $useridquanxian=$userid.'quanxian';
        $cache = cache::get($useridquanxian);
        if($cache==null){return json(['code'=>0,'message'=>'您没有此操作权限']);}
        if(!in_array(['mid'=>3],$cache,false)){
            return json(['code'=>0,'message'=>'您没有此操作权限']);
        }
        $data=db('diqu')->select();
        $last_names = array_column($data,'diquname');
        array_multisort($last_names,$data);

        if($data){
            $useridcaozuo=$userid.'caozuo';
            $useridname=$userid.'name';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'查看了地区管理';
            if(!cache($useridcaozuo)==$caozuo['data']){
                $this->caozuojilu($caozuo);

                cache($useridcaozuo,$caozuo['data']);
            }else{
                //相同不添加;
            }

            return json(['code'=>200,'message'=>'成功','data'=>$data]);
        }else{
            return json(['code'=>0,'message'=>'未查询']);
        }
    }
    public function diquadd(){
        $data['diquname']=input('diquname');
        $rel=db('diqu')->insert($data);
        if($rel){
            $userid=input('userid');
            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'添加了地区';
            $caozuojilu=db('caozuojilu')->insert($caozuo);
            cache($useridcaozuo,$caozuo['data']);
            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'失败']);
        }
    }
    public function diquedit(){
        $data['diqu']=input('diqu');
        $data['diquname']=input('diquname');
        $rel=db('diqu')->update($data);

        if($rel){
            $userid=input('userid');
            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'修改了地区';
            $caozuojilu=db('caozuojilu')->insert($caozuo);
            cache($useridcaozuo,$caozuo['data']);
            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'未修改']);
        }
    }
    public function diqudel(){
        $data['diqu']=input('diqu');
        $rel=db('diqu')->delete($data);
        if($rel){
            $userid=input('userid');
            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'删除了地区';
            $caozuojilu=db('caozuojilu')->insert($caozuo);
            cache($useridcaozuo,$caozuo['data']);
            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'失败']);
        }
    }
}