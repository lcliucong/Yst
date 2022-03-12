<?php
namespace app\admin\controller;
use think\Controller;
use app\common\Common;
use think\facade\Cache;

class Division extends Common{
//    public function  initialize(){
//    header('Access-Control-Allow-Origin: *');
//    header('Access-Control-Allow-Methods:POST,GET,OPTIONS,DELETE,PUT'); // 允许请求的类型
//    header('Access-Control-Allow-Credentials: true');
//    header('Access-Control-Allow-Headers:x-requested-with,Content-Type,X-CSRF-Token');
//    header('Access-Control-Allow-Headers: *');
//    session('userid',1);
//    $user=session('userid');
//
//    $rel=db('level l')
//        ->join('limit','l.level=limit.level')
//        ->where('l.levelid',$user)
//        ->where('limit.level',5)
//        ->select();
//    //dump($rel);
//    if(empty($rel)){
//        $this->error('有内鬼，撤退');
//    }
//}
    public function division(){
        $userid=input('userid');
        $useridquanxian=$userid.'quanxian';
        $useridcaozuo=$userid.'caozuo';

        $cache = cache::get($useridquanxian);
        if($cache==null){return json(['code'=>0,'message'=>'您没有此操作权限']);}
        if(!in_array(['mid'=>14],$cache,false)){
            return json(['code'=>0,'message'=>'您没有此操作权限']);
        }

        $division=db('division d')
                ->join('class c','d.bid=c.cid')
                ->order('d.bid')
                ->order('c.cid')
                ->select();
        //dump($division);
        if($division){
            $userid=input('userid');
            $useridname=$userid.'name';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'查看了分类管理';
            if(!cache($useridcaozuo)==$caozuo['data']){
                $this->caozuojilu($caozuo);
                cache($useridcaozuo,$caozuo['data']);
            }else{
                //相同不添加;
            }

            return json(['code'=>200,'message'=>'成功','division'=>$division]);
        }else{
            return json(['code'=>0,'message'=>'失败']);
        }
    }
    public function divisionadd(){
        $division['prescription']=input('prescription');
        $data['cid']=db('division')->insertGetId($division);

        $class=input('class');

        if(is_array($class)){
            foreach ($class as $value){
                $c['class']=$value;
                $c['cid']=$data['cid'];
                $rel2=db('class')->insert($c);
            }
        }else{
            $c['class']=input('class');
            $c['cid']=$data['cid'];
            $rel2=db('class')->insert($c);
        }

        if($data['cid'] || $rel2){
            $userid=input('userid');
            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'添加了分类';
            $caozuojilu=db('caozuojilu')->insert($caozuo);
            cache($useridcaozuo,$caozuo['data']);
            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'失败']);
        }
    }
    public function divisionedit(){
        $division['bid']=input('bid');
        $division['prescription']=input('prescription');
        $rel=db('division')->update($division);

        $classid=input('classid');
        $class['class']=input('class');
        $rel2=db('class')->where('classid',$classid)->update($class);

        if($rel || $rel2){
            $userid=input('userid');
            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'修改了分类';
            $caozuojilu=db('caozuojilu')->insert($caozuo);
            cache($useridcaozuo,$caozuo['data']);
            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'失败']);
        }
    }
    public function divisiondel(){
        $delc=input('classid');
        $rel=db('class')->where('classid',$delc)->delete();

        $cid=input('cid');
        $count=db('class')->where('cid',$cid)->count();
        $rel2='';
        if($count==0){
            $rel2=db('division')->where('bid',$cid)->delete();
        }
        if($rel || $rel2){
            $userid=input('userid');
            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'删除了分类';
            $caozuojilu=db('caozuojilu')->insert($caozuo);
            cache($useridcaozuo,$caozuo['data']);
            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'失败']);
        }
    }
}