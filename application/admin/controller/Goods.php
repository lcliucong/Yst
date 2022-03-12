<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use app\common\Common;
use think\facade\Cache;

class Goods extends Common{

//    public function  initialize(){
//
//        header('Access-Control-Allow-Origin: *');
//        header('Access-Control-Allow-Methods:POST,GET,OPTIONS,DELETE,PUT'); // 允许请求的类型
//        header('Access-Control-Allow-Credentials: true');
//        header('Access-Control-Allow-Headers:x-requested-with,Content-Type,X-CSRF-Token');
//        header('Access-Control-Allow-Headers: *');
//        session('userid',1);
//        $user=session('userid');
//
//        $rel=db('level l')
//            ->join('limit','l.level=limit.level')
//            ->where('l.levelid',$user)
//            ->where('limit.level',3)
//            ->select();
//        //dump($rel);
//        if(empty($rel)){
//            $this->error('有内鬼，撤退');
//        }
//    }
    public function Goods(){
        $userid=input('userid');
        $useridcaozuo=$userid.'caozuo';
   

        $data=Db::query("
            select g.*,d.prescription,c.class
            from tp51_goods g 
            left join tp51_division d 
            on g.prescription=d.bid
            left join tp51_class c 
            on g.class=c.classid
        ");

        //var_dump($data);
        if($data){
            $userid=input('userid');
            $useridname=$userid.'name';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'查看了商品';
            if(!cache($useridcaozuo)==$caozuo['data']){
                $this->caozuojilu($caozuo);
                cache($useridcaozuo,$caozuo['data']);
            }else{
                //相同不添加;
            }

            return json(['code'=>200,'message'=>'成功','data'=>$data]);
        }else{
            return json(['code'=>0,'message'=>'失败']);
        }
    }
    public function GoodsAdd(){
        $data['lotnumber']=input('lotnumber');
        $data['number']=input('number');
        $data['name']=input('name');
        $data['brand']=input('brand');
        $data['specs']=input('specs');
        $data['prescription']=input('prescription');
        $data['class']=input('class');
        $data['composition']=input('composition');
        $data['color']=input('color');
        $data['library_number']=input('library_number');
        $data['sales']=input('sales');
        $data['create_time']=input('create_time');
        $data['update_time']=input('update_time');
        $data['unit']=input('unit');
        //var_dump($data);


        $res=db('goods')->strict(false)->insert($data);
        if($res){
            $userid=input('userid');
            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'添加了商品';
            $caozuojilu=db('caozuojilu')->insert($caozuo);
            cache($useridcaozuo,$caozuo['data']);
            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'失败']);
        }

    }
    public function GoodsEdit(){
        if(request()->isPost()){
            $data['id']=input('id');
            $data['number']=input('number');
            $data['lotnumber']=input('lotnumber');
            $data['library_number']=input('library_number');
            $data['name']=input('name');
            $data['brand']=input('brand');
            $data['prescription']=input('prescription');
            $data['class']=input('class');
            $data['composition']=input('composition');
            $data['color']=input('color');
            $data['sales']=input('sales');
            $data['specs']=input('specs');
            $data['update_time']=input('update_time');
            $data['create_time']=input('create_time');
            $data['unit']=input('unit');

            $res=db('goods')->update($data);
            if($res){
                $userid=input('userid');
                $useridname=$userid.'name';
                $useridcaozuo=$userid.'caozuo';
                $caozuo['time']=date('Y-m-d H:i:s',time());
                $caozuo['data']='用户'.cache($useridname).'修改了商品';
                $caozuojilu=db('caozuojilu')->insert($caozuo);
                cache($useridcaozuo,$caozuo['data']);
                return json(['code'=>200,'message'=>'成功']);
            }else{
                return json(['code'=>0,'message'=>'失败']);
            }
        }else{
            return json(['code'=>100,'message'=>'未提交表单']);
        }


    }
    public function GoodsDel(){
        $data['id']=input('id');
        $res=db('goods')->delete($data);
        if($res){
            $userid=input('userid');
            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'删除了商品';
            $caozuojilu=db('caozuojilu')->insert($caozuo);
            cache($useridcaozuo,$caozuo['data']);
            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'失败']);
        }

    }
    public function GoodsSearch(){
        $data=input('name');
        //$where=['name','link','%'.$data.'%'];
        $res=db('goods')
            ->where([['name','like','%'.$data.'%'],['color','=','白色']])
            ->select();

        if($res){
            return json(['code'=>200,'message'=>'成功','data'=>$res]);
        }else{
            return json(['code'=>0,'message'=>'未查询到']);
        }
    }
}
