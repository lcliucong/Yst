<?php

namespace app\delivery\model;

use app\mainmenu\model\ModelBase;
use think\Db;

class Flowofmed extends ModelBase{
    protected function initialize()
    {
        parent::initialize();
    }
    public function flowlist($page){
            $time = date("Y-m");
            $time1 = $time.'-01';
            $time2 = date("t",strtotime($time));
            $time2 = $time.'-'.$time2;
            $res = Db::name('flowofmed')->where('in_time','between',[$time1,$time2])->order('id desc')->page((int)$page['page_num']??1,10)->select();
            if(!empty($res)){
                $count = Db::name('flowofmed')->where('in_time','between',[$time1,$time2])->field('id')->count();
                foreach ($res as $k=>$v){
                   if($v['in_time']=='1111-11-11'){
                       $v['in_time']='';
                   }
                   $resultarr[]=$v;
               }
           }else{
               $resultarr = [];
               $count = Db::name('flowofmed')->where('in_time','between',[$time1,$time2])->field('id')->count();
           }
        return [$resultarr,$count];
    }

    public function selects($data,$field){

        $name = $data['name'];
        $ssid = $data['ssid'];
        $month = $data['month_time'];
        $page = $data['page_num'];

        if(empty($month)){
            $time = date("Y-m");
            $time1 = $time.'-01';
            $time2 = date("t",strtotime($time));
            $time2 = $time.'-'.$time2;
        }else{
            $time1 = $month.'-01';
            $time2 = date("t",strtotime($month));
            $time2 = $month.'-'.$time2;
        }
        if(empty($ssid)&&empty($name)){
            $result = Db::name('flowofmed')->whereTime('in_time','between',[$time1,$time2])->order('id','desc')->page((int)$page?:1,10)->select();
            $count = Db::name('flowofmed')->whereTime('in_time','between',[$time1,$time2])->field('id')->count();
        }else if(empty($ssid)){
            $result = Db::name('flowofmed')->where('in_time','between time',[$time1,$time2])->where("concat($field) LIKE '%$name%'")->order('id','desc')->page((int)$page?:1,10)->select();
            $count = Db::name('flowofmed')->whereTime('in_time','between',[$time1,$time2])->where("concat($field) LIKE '%$name%'")->field('id')->count();
        }else if(empty($name)){
            $result = Db::name('flowofmed')->where('in_time','between time',[$time1,$time2])->where('ssid',$ssid)->order('id','desc')->page((int)$page?:1,10)->select();
            $count = Db::name('flowofmed')->whereTime('in_time','between',[$time1,$time2])->where("ssid",$ssid)->field('id')->count();
        }else{
            $result = Db::name('flowofmed')->where('in_time','between time',[$time1,$time2])->where("concat($field) LIKE '%$name%'")->where('ssid',$ssid)->order('id','desc')->page((int)$page?:1,10)->select();
            $count = Db::name('flowofmed')->whereTime('in_time','between',[$time1,$time2])->where("concat($field) LIKE '%$name%'")->where('ssid',$ssid)->field('id')->count();
        }
//        dump($result);die;
        return [$result,$count];
    }
    /**
     * @param string $time
     * @param string $time2
     * @return mixed
     * 时间查询  废弃
     */
    public function timeseles($time='',$time2='',$ssid){
        if(empty($ssid)){
            $result = $this->whereTime('in_time','between',[$time,$time2])->select()->toArray();
        }else{
            $result = $this->whereTime('in_time','between',[$time,$time2])->where('ssid',$ssid)->select()->toArray();
        }
        return $result;
    }
    /**
     * @param string $data
     * @param string $field
     * @return mixed
     * 普通查询   废弃
     */
    public function commseles($data,$field,$ssid){
        if(empty($ssid)){
            $result = $this->where("concat($field) LIKE '%$data%'")->order('id','asc')->select();
        }else{
            $result = $this->where("concat($field) LIKE '%$data%'")->where('ssid',$ssid)->order('id','asc')->select();
        }
        return $result;
    }

    /**
     * @param string $time 月初时间
     * @param string $time2 月末时间
     * @param string $data 上传的查询数据
     * @param string $field 查询字段
     * @return mixed
     * 选定时间范围后的内容查询  废弃
     */
    public function comtimesele($time,$time2,$data,$field,$ssid){
        if(empty($ssid)){
            $result = $this->where('in_time','between time',["$time","$time2"])->where("concat($field) LIKE '%$data%'")->order('id','asc')->select();
        }else{
            $result = $this->where('in_time','between time',["$time","$time2"])->where("concat($field) LIKE '%$data%'")->where('ssid',$ssid)->order('id','asc')->select();
        }
        return $result;
    }
    public function timeopt($array){
        foreach ($array as $k=>$v){
            if ($v['in_time']=='1111-11-11'){
                $v['in_time']='';
            }
            $resultarr[] = $v;
        }
        if(empty($resultarr)){
            $resultarr = [];
        }
        return $resultarr;
    }
    public function seles($ssid){
        $result = $this->where('ssid',$ssid)->order('id','asc')->select();
        return $result;
    }
    /**
     * @param string $data
     * @param bool $field
     * @return mixed
     */
    public function fladd($data=[],$field=true){
        $result = $this->allowField($field)->isUpdate(false)->data($data)->save();
        return $result;
    }

}