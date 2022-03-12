<?php
namespace app\admin\controller;
use think\Controller;
use app\common\Common;
use think\facade\Cache;
use think\Db;
use app\admin\validate\Chanpin as chanpin;
class Juese extends Common{
//    public function  initialize(){
//        parent::initialize();
//        $fangfa =get_class_methods("app\\admin\\controller\\juese");
//        var_dump($fangfa);
//
//    }
    public function juese(){

        $userid=input('userid');
        $useridcaozuo=$userid.'caozuo';
        $juese=Db::query("
            select j.jueseid,j.name,j.miaoshu,GROUP_CONCAT(distinct m.title) as 'quanxian',GROUP_CONCAT(distinct c.chanpin) as 'chanpin'
            from tp51_juese j 
            left join tp51_juesechanpin j2 
            on j.jueseid=j2.jueseid
            left join tp51_chanpin c
            on j2.chanpinid=c.id
                left join tp51_juesequan j1 
                on j.jueseid=j1.jueseid
                left join tp51_mainmenu m
                on j1.mid=m.mid
               
            GROUP BY j.jueseid
            order by j.jueseid
        ");

        if($juese){

            $useridname=$userid.'name';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'查看了角色';
            if(!cache($useridcaozuo)==$caozuo['data']){
                $this->caozuojilu($caozuo);
                cache($useridcaozuo,$caozuo['data']);
            }else{
                //相同不添加;
            }

            return json(['code'=>200,'message'=>'成功','juese'=>$juese]);
        }else{
            return json(['code'=>0,'message'=>'失败']);
        }

    }
    public function jueselist(){
        $jueseid=input('jueseid');
        $juesequan=db('juesequan j')
            ->join('mainmenu m' , 'j.mid=m.mid')
            ->where('j.jueseid',$jueseid)
            //->field('j.jueseid')
            ->field('m.mid')
            //->field('m.title')
            ->select();
        //dump($juesequan);
        if($juesequan){
            $userid=input('userid');
            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'详细查看了角色ID为'.$jueseid.'的数据';
            $this->caozuojilu($caozuo);
            cache($useridcaozuo,$caozuo['data']);

            return json(['code'=>200,'message'=>'成功','juesequan'=>$juesequan]);
        }else{
            return json(['code'=>0,'message'=>'失败']);
        }
    }
    public function jueseadd(){
        $data['name']=input('name');
        $data['miaoshu']=input('miaoshu');
        $mid['jueseid']=db('juese')->insertGetId($data);

        if($mid){
            $userid=input('userid');
            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'添加了角色';
            $this->caozuojilu($caozuo);
            cache($useridcaozuo,$caozuo['data']);

            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'失败']);
        }
    }
    public function juesedel(){
        $jueseid=input('jueseid');
        if(is_array($jueseid)){
            foreach ($jueseid as $dela){
                $rel=db('juese')->delete($dela);
                $rel1=db('juesequan')->where('jueseid',$dela)->delete();
                $rel2=db('juesechanpin')->where('jueseid',$dela)->delete();
            }

        }else{
            $rel=db('juese')->delete($jueseid);
            $rel1=db('juesequan')->where('jueseid',$jueseid)->delete();
            $rel2=db('juesechanpin')->where('jueseid',$jueseid)->delete();
        }


        if($rel){
            $userid=input('userid');
            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'删除了角色';
            $this->caozuojilu($caozuo);
            cache($useridcaozuo,$caozuo['data']);

            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'失败']);
        }
    }
    public function jueseedit(){
        $data['jueseid']=input('jueseid');
        $data['name']=input('name');
        $data['miaoshu']=input('miaoshu');
        $rel=db('juese')->update($data);
        if($rel){
            $userid=input('userid');
            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'修改了角色';
            $this->caozuojilu($caozuo);
            cache($useridcaozuo,$caozuo['data']);

            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'未修改成功']);
        }
    }
    public function juesequanedit(){
        $data['jueseid']=input('jueseid');
        $rel1=db('juesequan')->where('jueseid',$data['jueseid'])->delete();
        $mid=input('mid');

        if(is_array($mid)){
            foreach ($mid as $value){
                $data['mid']=$value;
                $rel11=db('juesequan')->insert($data);
            }
        }else{
            $data['mid']=input('mid');
            $rel11=db('juesequan')->insert($data);
        }

        if($rel11||$rel1){
            $userid=input('userid');
            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'修改了角色权限';
            $this->caozuojilu($caozuo);
            cache($useridcaozuo,$caozuo['data']);

            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'未修改']);
        }
    }
//    public function juesequanadd(){
//        $data['jueseid']=input('jueseid');
//
//        $mid=input('mid');
//        if(is_array($mid)){
//            foreach ($mid as $value){
//                $data['mid']=$value;
//                $rel=db('juesequan')->insert($data);
//            }
//        }else{
//            $data['mid']=input('mid');
//            $rel=db('juesequan')->insert($data);
//        }
//        if($rel){
//            return json(['code'=>200,'message'=>'成功']);
//        }else{
//            return json(['code'=>0,'message'=>'未成功']);
//        }
//    }
//    public function juesequandel(){
//        $data['jueseid']=input('jueseid');
//        $mid=input('mid');
//        if(is_array($mid)){
//            foreach ($mid as $value){
//                $data['mid']=$value;
//                $rel=db('juesequan')->where('jueseid',$data['jueseid'])->where('mid',$data['mid'])->delete();
//            }
//        }else{
//            $data['mid']=input('mid');
//            $rel=db('juesequan')->where('jueseid',$data['jueseid'])->where('mid',$data['mid'])->delete();
//        }
//        if($rel){
//            return json(['code'=>200,'message'=>'成功']);
//        }else{
//            return json(['code'=>0,'message'=>'未成功']);
//        }
//    }
    public function juesechanpin(){
        $data=db('chanpin')->select();
        if($data){
            return json(['code'=>200,'message'=>'成功','data'=>$data]);
        }else{
            return json(['code'=>100,'message'=>'未成功','data'=>[]]);
        }
    }
    public function juesechanpinxiangqing(){
        $id=input('jueseid');
        $data=db('juesechanpin')->where('jueseid',$id)->field('chanpinid')->select();
        $data=array_column($data,'chanpinid');
        if($data){
            return json(['code'=>200,'message'=>'成功','data'=>$data]);
        }else{
            return json(['code'=>0,'message'=>'未成功']);
        }

    }
    public function juesechanpinedit(){
        $chanpin['jueseid']=input('jueseid');
        $chanpin1=input('chanpinid');
        if(empty($chanpin1)){
            $rel2=db('juesechanpin')->where('jueseid',$chanpin['jueseid'])->delete();
            if($rel2){
                return json(['code'=>200,'message'=>'成功']);
            }else{
                return json(['code'=>0,'message'=>'未成功']);
            }
        }


        if(is_array($chanpin1)){
            foreach ($chanpin1 as $idd){
                $chanpin['chanpinid']=$idd;
                $rel22=db('juesechanpin')->insert($chanpin);
            }
        }else{
            $chanpin['chanpinid']=input('chanpinid');
            $rel22=db('juesechanpin')->insert($chanpin);
        }
        if($rel22){
            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'未成功']);
        }

    }
    public function chanpindel(){
        $del=input('id');

        if(is_array($del)){
            foreach ($del as $dela){
                $rel=db('chanpin')->delete($dela);
            }
        }
        else{
            $rel=db('chanpin')->delete($del);
        }


        if($rel){
            $userid=input('userid');
            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'删除了产品';
            $caozuojilu=db('caozuojilu')->insert($caozuo);
            cache($useridcaozuo,$caozuo['data']);
            return json(['code'=>200,'message'=>'成功','data'=>[]]);
        }else{
            return json(['code'=>0,'message'=>'未成功']);
        }
    }
    public function chanpinedit(){
        $id['id']=input('id');
        $id['chanpin']=input('chanpin');
        $rel=db('chanpin')->update($id);
        if($rel){
            $userid=input('userid');
            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'修改了产品';
            $caozuojilu=db('caozuojilu')->insert($caozuo);
            cache($useridcaozuo,$caozuo['data']);

            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'未修改']);
        }
    }
    public function chanpinadd(){
        $chanpin['chanpin']=trim(input('chanpin'));
        $validate = new chanpin;

        if (!$validate->check($chanpin)) {
            $validate=$validate->getError();
            return json(['code'=>'150','message'=>$validate]);
        }else{
            $res=db('chanpin')->insert($chanpin);
            if($res){
                $userid=input('userid');
                $useridname=$userid.'name';
                $useridcaozuo=$userid.'caozuo';
                $caozuo['time']=date('Y-m-d H:i:s',time());
                $caozuo['data']='用户'.cache($useridname).'添加了产品';
                $caozuojilu=db('caozuojilu')->insert($caozuo);
                cache($useridcaozuo,$caozuo['data']);

                return json(['code'=>200,'message'=>'成功']);
            }else{
                return json(['code'=>0,'message'=>'失败']);
            }

        }

    }
}