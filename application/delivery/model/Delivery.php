<?php

namespace app\delivery\model;

use app\mainmenu\model\ModelBase;

class Delivery extends ModelBase{
    protected function initialize()
    {
        parent::initialize();
    }

    /**
     * @return mixed
     * 列表数据渲染
     */
    public function delist(){
       $result = $this->order('id desc')->select()->toArray();
       if(!empty($result)){
           foreach ($result as $k=>$v){
               if($v['month_time']=='1111-11-11'){
                   $v['month_time']='';
               }
               if($v['invoice_date']=='1111-11-11'){
                   $v['invoice_date']='';
               }
               if($v['collection_date']=='1111-11-11'){
                   $v['collection_date']='';
               }
               $resultarr[]=$v;
           }
       }else{
           $resultarr = [];
       }
       return $resultarr;
    }
    public function deselects($data,$field){
        $name = $data['name'];
        $month = $data['month_time'];
        $bussunit = $data['ssid'];
        if(empty($name)&&empty($month)&&empty($bussunit)){
//            dump(123);die;
            $result = $this->delist();
//            dump($result);die;
            $sums='nosums';
            return [$result,$sums];
        }
        if(!empty($month)){
            $time1 = $month.'-01';
            $time2 = date("t",strtotime($month));
            $time2 = $month.'-'.$time2;
        }
//        }
        if(empty($name)&&empty($month)){
            $result = $this->where('factory_name',$bussunit)->order('id','desc')->select();
//            $counts = $this->where('factory_name',$bussunit)->sum('fac_num');
            if(count($result)>0){
                $sumfield = $this->where('factory_name',$bussunit)->
                field('fac_num,invoice_num,invoice_money,collection_num,collection_num_2,collection_num_3,collection_money,collection_money_2,collection_money_3,surplus_num,surplus_money')->select();
                foreach ($sumfield as $k=>$v){
                    $fac_num[] = $v['fac_num'];
                    $invoice_num[] = $v['invoice_num'];
                    $invoice_money[] = $v['invoice_money'];
                    $collection_num[] = $v['collection_num'];
                    $collection_num_2[] = $v['collection_num_2'];
                    $collection_num_3[] = $v['collection_num_3'];
                    $collection_money[] = $v['collection_money'];
                    $collection_money_2[] = $v['collection_money_2'];
                    $collection_money_3[] = $v['collection_money_3'];
                    $surplus_num[] = $v['surplus_num'];
                    $surplus_money[] = $v['surplus_money'];
                }
                $fac_numsum = array_sum($fac_num);
                $invoice_numsum = array_sum($invoice_num);
                $invoice_monsum = array_sum($invoice_money);
                $collection_numsum = array_sum($collection_num);
                $collection_numsum2 = array_sum($collection_num_2);
                $collection_numsum3 = array_sum($collection_num_3);
                $collection_monsum = array_sum($collection_money);
                $collection_monsum2 = array_sum($collection_money_2);
                $collection_monsum3 = array_sum($collection_money_3);
                $surplus_numsum = array_sum($surplus_num);
                $surplus_moneysum = array_sum($surplus_money);
                $sums['fac_num'] = $fac_numsum;
                $sums['invoice_num'] = $invoice_numsum;
                $sums['invoice_money'] = $invoice_monsum;
                $sums['collection_num'] = $collection_numsum;
                $sums['collection_num_2'] = $collection_numsum2;
                $sums['collection_num_3'] = $collection_numsum3;
                $sums['collection_money'] = number_format($collection_monsum,2);
                $sums['collection_money_2'] = number_format($collection_monsum2,2);
                $sums['collection_money_3'] = number_format($collection_monsum3,2);
                $sums['surplus_num'] = $surplus_numsum;
                $sums['surplus_money'] = number_format($surplus_moneysum,2);
            }else{
                    $sums='nosums';
            }
        }else if(empty($name)&&empty($bussunit)){
            $result = $this->where('month_time','between time',[$time1,$time2])->order('id','desc')->select();
            if(empty($result)){
                $result=[];
                $sums=0;
            }else{
                $sums = 'nosums';
            }
        }else if(empty($month)&&empty($bussunit)){
            $result = $this->where("concat($field) LIKE '%$name%'")->order('id','desc')->select();
            if(count($result)>0){
                $sumfield = $this->where("concat($field) LIKE '%$name%'")->
                field('fac_num,invoice_num,invoice_money,collection_num,collection_num_2,collection_num_3,collection_money,collection_money_2,collection_money_3,surplus_num,surplus_money')->select();
                foreach ($sumfield as $k=>$v){
                    $fac_num[] = $v['fac_num'];
                    $invoice_num[] = $v['invoice_num'];
                    $invoice_money[] = $v['invoice_money'];
                    $collection_num[] = $v['collection_num'];
                    $collection_num_2[] = $v['collection_num_2'];
                    $collection_num_3[] = $v['collection_num_3'];
                    $collection_money[] = $v['collection_money'];
                    $collection_money_2[] = $v['collection_money_2'];
                    $collection_money_3[] = $v['collection_money_3'];
                    $surplus_num[] = $v['surplus_num'];
                    $surplus_money[] = $v['surplus_money'];
                }
                $fac_numsum = array_sum($fac_num);
                $invoice_numsum = array_sum($invoice_num);
                $invoice_monsum = array_sum($invoice_money);
                $collection_numsum = array_sum($collection_num);
                $collection_numsum2 = array_sum($collection_num_2);
                $collection_numsum3 = array_sum($collection_num_3);
                $collection_monsum = array_sum($collection_money);
                $collection_monsum2 = array_sum($collection_money_2);
                $collection_monsum3 = array_sum($collection_money_3);
                $surplus_numsum = array_sum($surplus_num);
                $surplus_moneysum = array_sum($surplus_money);
                $sums['fac_num'] = $fac_numsum;
                $sums['invoice_num'] = $invoice_numsum;
                $sums['invoice_money'] = $invoice_monsum;
                $sums['collection_num'] = $collection_numsum;
                $sums['collection_num_2'] = $collection_numsum2;
                $sums['collection_num_3'] = $collection_numsum3;
                $sums['collection_money'] = number_format($collection_monsum,2);
                $sums['collection_money_2'] = number_format($collection_monsum2,2);
                $sums['collection_money_3'] = number_format($collection_monsum3,2);
                $sums['surplus_num'] = $surplus_numsum;
                $sums['surplus_money'] = number_format($surplus_moneysum,2);
            }else{
                $sums='nosums';
            }
        }elseif(empty($name)){
            $result = $this->where("factory_name",$bussunit)->where('month_time','between time',[$time1,$time2])->order('id','desc')->select();
            if(count($result)>0){
                $sumfield = $this->where("factory_name",$bussunit)->where('month_time','between time',[$time1,$time2])->
                field('fac_num,invoice_num,invoice_money,collection_num,collection_num_2,collection_num_3,collection_money,collection_money_2,collection_money_3,surplus_num,surplus_money')
                    ->order('id','desc')->select();
                foreach ($sumfield as $k=>$v){
                    $fac_num[] = $v['fac_num'];
                    $invoice_num[] = $v['invoice_num'];
                    $invoice_money[] = $v['invoice_money'];
                    $collection_num[] = $v['collection_num'];
                    $collection_num_2[] = $v['collection_num_2'];
                    $collection_num_3[] = $v['collection_num_3'];
                    $collection_money[] = $v['collection_money'];
                    $collection_money_2[] = $v['collection_money_2'];
                    $collection_money_3[] = $v['collection_money_3'];
                    $surplus_num[] = $v['surplus_num'];
                    $surplus_money[] = $v['surplus_money'];
                }
                $fac_numsum = array_sum($fac_num);
                $invoice_numsum = array_sum($invoice_num);
                $invoice_monsum = array_sum($invoice_money);
                $collection_numsum = array_sum($collection_num);
                $collection_numsum2 = array_sum($collection_num_2);
                $collection_numsum3 = array_sum($collection_num_3);
                $collection_monsum = array_sum($collection_money);
                $collection_monsum2 = array_sum($collection_money_2);
                $collection_monsum3 = array_sum($collection_money_3);
                $surplus_numsum = array_sum($surplus_num);
                $surplus_moneysum = array_sum($surplus_money);
                $sums['fac_num'] = $fac_numsum;
                $sums['invoice_num'] = $invoice_numsum;
                $sums['invoice_money'] = $invoice_monsum;
                $sums['collection_num'] = $collection_numsum;
                $sums['collection_num_2'] = $collection_numsum2;
                $sums['collection_num_3'] = $collection_numsum3;
                $sums['collection_money'] = number_format($collection_monsum,2);
                $sums['collection_money_2'] = number_format($collection_monsum2,2);
                $sums['collection_money_3'] = number_format($collection_monsum3,2);
                $sums['surplus_num'] = $surplus_numsum;
                $sums['surplus_money'] = number_format($surplus_moneysum,2);
            }else{
                $sums='nosums';
            }
        }elseif(empty($month)) {
            $result = $this->where("factory_name", $bussunit)->where("concat($field) LIKE '%$name%'")->order('id', 'desc')->select();
            if (count($result) > 0) {
                $sumfield = $this->where("factory_name", $bussunit)->where("concat($field) LIKE '%$name%'")->
                field('fac_num,invoice_num,invoice_money,collection_num,collection_num_2,collection_num_3,collection_money,collection_money_2,collection_money_3,surplus_num,surplus_money')
                    ->order('id', 'desc')->select();
                foreach ($sumfield as $k => $v) {
                    $fac_num[] = $v['fac_num'];
                    $invoice_num[] = $v['invoice_num'];
                    $invoice_money[] = $v['invoice_money'];
                    $collection_num[] = $v['collection_num'];
                    $collection_num_2[] = $v['collection_num_2'];
                    $collection_num_3[] = $v['collection_num_3'];
                    $collection_money[] = $v['collection_money'];
                    $collection_money_2[] = $v['collection_money_2'];
                    $collection_money_3[] = $v['collection_money_3'];
                    $surplus_num[] = $v['surplus_num'];
                    $surplus_money[] = $v['surplus_money'];
                }
                $fac_numsum = array_sum($fac_num);
                $invoice_numsum = array_sum($invoice_num);
                $invoice_monsum = array_sum($invoice_money);
                $collection_numsum = array_sum($collection_num);
                $collection_numsum2 = array_sum($collection_num_2);
                $collection_numsum3 = array_sum($collection_num_3);
                $collection_monsum = array_sum($collection_money);
                $collection_monsum2 = array_sum($collection_money_2);
                $collection_monsum3 = array_sum($collection_money_3);
                $surplus_numsum = array_sum($surplus_num);
                $surplus_moneysum = array_sum($surplus_money);
                $sums['fac_num'] = $fac_numsum;
                $sums['invoice_num'] = $invoice_numsum;
                $sums['invoice_money'] = $invoice_monsum;
                $sums['collection_num'] = $collection_numsum;
                $sums['collection_num_2'] = $collection_numsum2;
                $sums['collection_num_3'] = $collection_numsum3;
                $sums['collection_money'] = number_format($collection_monsum,2);
                $sums['collection_money_2'] = number_format($collection_monsum2,2);
                $sums['collection_money_3'] = number_format($collection_monsum3,2);
                $sums['surplus_num'] = $surplus_numsum;
                $sums['surplus_money'] = number_format($surplus_moneysum,2);
            }else{
                $sums='nosums';
            }
        }elseif(empty($bussunit)){
            $result = $this->where("month_time",'between time',[$time1,$time2])->where("concat($field) LIKE '%$name%'")->order('id', 'desc')->select();
            if (count($result) > 0) {
                $sumfield = $this->where("month_time",'between time',[$time1,$time2])->where("concat($field) LIKE '%$name%'")->
                field('fac_num,invoice_num,invoice_money,collection_num,collection_num_2,collection_num_3,collection_money,collection_money_2,collection_money_3,surplus_num,surplus_money')
                    ->order('id', 'desc')->select();
                foreach ($sumfield as $k => $v) {
                    $fac_num[] = $v['fac_num'];
                    $invoice_num[] = $v['invoice_num'];
                    $invoice_money[] = $v['invoice_money'];
                    $collection_num[] = $v['collection_num'];
                    $collection_num_2[] = $v['collection_num_2'];
                    $collection_num_3[] = $v['collection_num_3'];
                    $collection_money[] = $v['collection_money'];
                    $collection_money_2[] = $v['collection_money_2'];
                    $collection_money_3[] = $v['collection_money_3'];
                    $surplus_num[] = $v['surplus_num'];
                    $surplus_money[] = $v['surplus_money'];
                }
                $fac_numsum = array_sum($fac_num);
                $invoice_numsum = array_sum($invoice_num);
                $invoice_monsum = array_sum($invoice_money);
                $collection_numsum = array_sum($collection_num);
                $collection_numsum2 = array_sum($collection_num_2);
                $collection_numsum3 = array_sum($collection_num_3);
                $collection_monsum = array_sum($collection_money);
                $collection_monsum2 = array_sum($collection_money_2);
                $collection_monsum3 = array_sum($collection_money_3);
                $surplus_numsum = array_sum($surplus_num);
                $surplus_moneysum = array_sum($surplus_money);
                $sums['fac_num'] = $fac_numsum;
                $sums['invoice_num'] = $invoice_numsum;
                $sums['invoice_money'] = $invoice_monsum;
                $sums['collection_num'] = $collection_numsum;
                $sums['collection_num_2'] = $collection_numsum2;
                $sums['collection_num_3'] = $collection_numsum3;
                $sums['collection_money'] = number_format($collection_monsum,2);
                $sums['collection_money_2'] = number_format($collection_monsum2,2);
                $sums['collection_money_3'] = number_format($collection_monsum3,2);
                $sums['surplus_num'] = $surplus_numsum;
                $sums['surplus_money'] = number_format($surplus_moneysum,2);
            }else{
                $sums='nosums';
            }
        }else{
            $result = $this->where("month_time",'between time',[$time1,$time2])->where('factory_name',$bussunit)->where("concat($field) LIKE '%$name%'")->order('id', 'desc')->select();
            if (count($result) > 0) {
                $sumfield = $this->where("month_time",'between time',[$time1,$time2])->where('factory_name',$bussunit)->where("concat($field) LIKE '%$name%'")->
                field('fac_num,invoice_num,invoice_money,collection_num,collection_num_2,collection_num_3,collection_money,collection_money_2,collection_money_3,surplus_num,surplus_money')
                    ->order('id', 'desc')->select();
                foreach ($sumfield as $k => $v) {
                    $fac_num[] = $v['fac_num'];
                    $invoice_num[] = $v['invoice_num'];
                    $invoice_money[] = $v['invoice_money'];
                    $collection_num[] = $v['collection_num'];
                    $collection_num_2[] = $v['collection_num_2'];
                    $collection_num_3[] = $v['collection_num_3'];
                    $collection_money[] = $v['collection_money'];
                    $collection_money_2[] = $v['collection_money_2'];
                    $collection_money_3[] = $v['collection_money_3'];
                    $surplus_num[] = $v['surplus_num'];
                    $surplus_money[] = $v['surplus_money'];
                }
                $fac_numsum = array_sum($fac_num);
                $invoice_numsum = array_sum($invoice_num);
                $invoice_monsum = array_sum($invoice_money);
                $collection_numsum = array_sum($collection_num);
                $collection_numsum2 = array_sum($collection_num_2);
                $collection_numsum3 = array_sum($collection_num_3);
                $collection_monsum = array_sum($collection_money);
                $collection_monsum2 = array_sum($collection_money_2);
                $collection_monsum3 = array_sum($collection_money_3);
                $surplus_numsum = array_sum($surplus_num);
                $surplus_moneysum = array_sum($surplus_money);
                $sums['fac_num'] = $fac_numsum;
                $sums['invoice_num'] = $invoice_numsum;
                $sums['invoice_money'] = $invoice_monsum;
                $sums['collection_num'] = $collection_numsum;
                $sums['collection_num_2'] = $collection_numsum2;
                $sums['collection_num_3'] = $collection_numsum3;
                $sums['collection_money'] = number_format($collection_monsum,2);
                $sums['collection_money_2'] = number_format($collection_monsum2,2);
                $sums['collection_money_3'] = number_format($collection_monsum3,2);
                $sums['surplus_num'] = $surplus_numsum;
                $sums['surplus_money'] = number_format($surplus_moneysum,2);
            }else{
                $sums='nosums';
            }
        }
        return [$result,$sums];
    }
    /**
     * @param string $time
     * @param string $time2
     * @return mixed
     * 时间查询 废弃
     */
    public function timesele($time='',$time2=''){
        $result = $this->whereTime('month_time','between',[$time,$time2])->select()->toArray();
        if(!empty($result)){

        }else{
            $result =[];
        }
        return $result;
    }

    /**
     * @param $data
     * @param $field
     * @return mixed
     * 普通查询  废弃
     */
    public function commsele($data,$field){
        $result = $this->where("concat($field) LIKE '%$data%'")->order('id','desc')->select()->toArray();
        if(!empty($result)){

        }else{
            $result =[];
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
    public function allsele($time,$time2,$data,$field){
        $result = $this->where('month_time','between time',["$time","$time2"])->where("concat($field) LIKE '%$data%'")->order('id','desc')->select();
        if(!empty($result)){

        }else{
            $result =[];
        }
        return $result;
    }

    /**
     * @param string $data
     * @param bool $field
     * @return mixed
     */
    public function deadd($data=[],$field=true){
        $result = $this->allowField($field)->isUpdate(false)->data($data)->save();
        return $result;
    }
    public function deaddgetid($data=[],$sequence = null,$field=true){
        $result = $this->allowField($field)->isUpdate(false)->insertGetId($data);
        return $result;
    }

    /**
     * @param array $data
     * @param string $field
     * @param string $name
     */
    public function deedit($name='',$data=[]){
        $result = $this->where('id',$data['id'])->update($data);
        return $result;
    }

}