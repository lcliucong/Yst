<?php
namespace app\admin\controller;
use think\Db;
use PHPExcel;
use think\facade\Request;
use app\mainmenu\controller\Common;
use think\Collection;


class Zhifu extends Common{
    public function zhifuadd(){
        ini_set('memory_limit','1024M');
        set_time_limit(0);
        $timenow=input('time');
        $time=input('time');
        $time=explode('-',$time);
        $ces=array_pop($time);
        $time=$time['0'];
        $time2 = date("t",strtotime($timenow));
        if($ces>1){
            $ces-=1;
            if(strlen($ces)<2){
                $ces='0'.$ces;
            }
        }else{
            $time-=1;
            $ces=12;
        }
        $timeup=$time.'-'.$ces;
        $i=0;

        $result=array();
        $nomate=array();

        $yaoming=Db::name('flowofmed')->where('in_time','between time',[$timenow.'-01',$timenow.'-'.$time2])->distinct(true)->field('med_name')->select();
        foreach($yaoming as $ym){
            $xinxibeian = Db::name('out')->field('zhongduanmingcheng,yiyuanjibie,yewuyuan,zhuguan,bumenjingli,diqu,pinming,guige,status,bumen,daibiao,renwu,zhuguanjiangjinticheng,jinglijiangjinticheng,abbiaozhunshuihou,lunwenfei,daibiaojiangjinticheng,zhongduanmingcheng2,zhifufangfa')
//            ->where('zhifufangfa',1)
                ->order('pinming')
                ->where('pinming', $ym['med_name'])
                ->select();
            $data = Db::name('flowofmed')->field('sum(med_salenum) as med_salenum,facname,med_name,med_specs,customer_name,customer_nameb,in_time,med_price,buss_origin,buss_name,med_batchnum,med_unit')
                ->where('in_time', 'between time', [$timenow . '-01', $timenow . '-' . $time2])
                ->where('med_name',$ym['med_name'])
                ->group('customer_name,med_specs')
                ->order('med_name')->select();
//dump($ym['med_name']);
//dump(count($data));
//dump(count($xinxibeian));
//dump($xinxibeian);exit;

            foreach ($data as $j => $value) {

                $zhifu['med_name'] = $value['med_name'];
                $zhifu['med_specs'] = $value['med_specs'];
                $zhifu['customer_name'] = $value['customer_name'];
                $zhifu['customer_nameb'] = $value['customer_nameb'];

                foreach ($xinxibeian as $v) {
                    //dump($v);exit;


                    if (
                        $zhifu['med_name'] == $v['pinming'] &&
                        $zhifu['med_specs'] == $v['guige'] &&
                        $zhifu['customer_name'] == $v['zhongduanmingcheng'] &&
                        $zhifu['customer_nameb'] == $v['zhongduanmingcheng2']
                    ) {

                        $shangyueyushu = Db::name('zhifu')->where(function ($query) use ($zhifu) {
                            $query->where('kehumingcheng1', $zhifu['customer_name'])->where('kehumingcheng2', $zhifu['customer_nameb']);
                        })
                            ->where('pinming', $zhifu['med_name'])->where('guige', $zhifu['med_specs'])
                            ->where('yuefen', $timeup)
                            //->where('shangyegongsi', $value['facname'])
                            ->sum('benyueyushu');
                        if (empty($shangyueyushu)) {
                            $shangyueyushu = 0;
                        }


                        $result[$i]['yuefen'] = $timenow;
                        $result[$i]['diqu'] = $v['diqu'];
                        $result[$i]['bumen'] = $v['bumen'];
                        $result[$i]['yewuyuan'] = $v['yewuyuan'];
                        $result[$i]['daibiao'] = $v['daibiao'];
                        $result[$i]['zhuguan'] = $v['zhuguan'];
                        $result[$i]['kehumingcheng1'] = $value['customer_name'];
                        $result[$i]['kehumingcheng2'] = $value['customer_nameb'];
                        $result[$i]['bumenjingli'] = $v['bumenjingli'];
                        $result[$i]['yiyuanjibie'] = $v['yiyuanjibie'];
                        $result[$i]['shangyegongsi'] = $value['facname'];
                        $result[$i]['pinming'] = $v['pinming'];
                        $result[$i]['guige'] = $v['guige'];
                        $result[$i]['shangyegongsi'] = $value['facname'];
                        $result[$i]['shangyueyushu'] = $shangyueyushu;
                        $result[$i]['benyuejinhuo'] = $value['med_salenum'];
                        $result[$i]['med_salenum'] = $value['med_salenum'];
                        $result[$i]['benyuexiaoshou'] = '';
                        $result[$i]['benyueyushu'] = '';
                        $result[$i]['abbiaozhunshuihou'] = $v['abbiaozhunshuihou'];
                        $result[$i]['abjine'] = '';
                        $result[$i]['lunwenfei'] = $v['lunwenfei'];
                        $result[$i]['lunwenfeijine'] = '';
                        $result[$i]['daibiaojiangjinticheng'] = $v['daibiaojiangjinticheng'];
                        $result[$i]['daibiaojiangjin'] = '';
                        $result[$i]['zhuguanjiangjinticheng'] = $v['zhuguanjiangjinticheng'];
                        $result[$i]['zhuguanjiangjin'] = '';
                        $result[$i]['jinglijiangjinticheng'] = $v['jinglijiangjinticheng'];
                        $result[$i]['jinglijiangjin'] = '';
                        $result[$i]['shangyegonghuojia'] = $value['med_price'];
                        $result[$i]['wanchengjine'] = '';
                        $result[$i]['renwu'] = $v['renwu'];
                        $result[$i]['wanchenglv'] = '';
                        $result[$i]['jiangfa'] = '';
                        $result[$i]['shizhijine'] = '';
                        $result[$i]['zhifufangfa'] = $v['zhifufangfa'];

                        if (empty($result[$i]['kehumingcheng1']) || empty($result[$i]['pinming']) || empty($result[$i]['guige'])) {

                            return json(['code' => 0, 'mes' => '数据为空或格式不对', 'data' => $result[$i]]);
                        }
                        unset($v);
                        break;
                    }

                    unset($v);

                }

                if (empty($result[$i])) {
                    //添加未匹配成功原因

                    $data[$j]['message'] = '信息备案不存在（相等两条及以下视为不存在）';
                    foreach ($xinxibeian as $xinxi2) {
                        if($zhifu['med_name']!==$xinxi2['pinming']&&$zhifu['med_specs']==$xinxi2['guige']&&$zhifu['customer_name']==$xinxi2['zhongduanmingcheng']&&$zhifu['customer_nameb']==$xinxi2['zhongduanmingcheng2']
                        ) {
                            $data[$j]['message'] = '品名有误（存在规格，终端名称，终端别名一致）';break;
                        } elseif ($zhifu['customer_name']!==$xinxi2['zhongduanmingcheng']&&$zhifu['med_name']==$xinxi2['pinming']&&$zhifu['med_specs']==$xinxi2['guige']&&$zhifu['customer_nameb']==$xinxi2['zhongduanmingcheng2']) {
                            $data[$j]['message'] = '终端名称有误（存在品名，规格，终端别名一致）';break;
                        } elseif ($zhifu['med_specs']!==$xinxi2['guige']&&$zhifu['med_name']==$xinxi2['pinming']&&$zhifu['customer_name']==$xinxi2['zhongduanmingcheng']&&$zhifu['customer_nameb']==$xinxi2['zhongduanmingcheng2']) {
                            $data[$j]['message'] = '规格有误（存在品名，终端名称，终端别名一致）';break;
                        } elseif ($zhifu['customer_nameb']!==$xinxi2['zhongduanmingcheng']&&$zhifu['med_name']==$xinxi2['pinming']&&$zhifu['med_specs']==$xinxi2['guige']&&$zhifu['customer_name']==$xinxi2['zhongduanmingcheng']) {
                            $data[$j]['message'] = '终端别名有误（存在品名，规格，终端名称一致）';break;
                        }
//
                    }
//                for($m=0;$m<2615;$m++){
//                    if($zhifu['med_specs']==$xinxibeian[$m]['guige']&&$zhifu['customer_name']==$xinxibeian[$m]['zhongduanmingcheng']&&$zhifu['customer_nameb']==$xinxibeian[$m]['zhongduanmingcheng2']
//                    ) {
//                        $data[$j]['message'] = '品名有误（存在规格，终端名称，终端别名一致）';break;
//                    } elseif ($zhifu['med_name']==$xinxibeian[$m]['pinming']&&$zhifu['med_specs']==$xinxibeian[$m]['guige']&&$zhifu['customer_nameb']==$xinxibeian[$m]['zhongduanmingcheng2']) {
//                        $data[$j]['message'] = '终端名称有误（存在品名，规格，终端别名一致）';break;
//                    } elseif ($zhifu['med_name']==$xinxibeian[$m]['pinming']&&$zhifu['customer_name']==$xinxibeian[$m]['zhongduanmingcheng']&&$zhifu['customer_nameb']==$xinxibeian[$m]['zhongduanmingcheng2']) {
//                        $data[$j]['message'] = '规格有误（存在品名，终端名称，终端别名一致）';break;
//                    } elseif ($zhifu['med_name']==$xinxibeian[$m]['pinming']&&$zhifu['med_specs']==$xinxibeian[$m]['guige']&&$zhifu['customer_name']==$xinxibeian[$m]['zhongduanmingcheng']) {
//                        $data[$j]['message'] = '终端别名有误（存在品名，规格，终端名称一致）';break;
//                    }
//
//                }
                    $nomate[] = $data[$j];
                }
                $i++;

            }//
        }


        $result2=array_filter($result,function($result){
            return $result['zhifufangfa']==1;
        });
//        dump(count($result));
//        dump(count($result2));
//        dump(count($nomate));exit;

//
//
//        exit;
        if(!empty($result2)){        $result2=array_values($result2);}
        if(!empty($nomate)){        $nomate=array_values($nomate);}

        if($result2||$nomate){
            return json(['code'=>200,'mes'=>'成功','pipei'=>$result2,'weipipei'=>$nomate]);
        }else{
            return json(['code'=>0,'mes'=>'不存在匹配结果','pipei'=>[],'weipipei'=>[]]);
        }

    }
    public function zhifubaocun(){
//        $a=1;
//        $b=2;
//        $d=10;
//        $c=bcmul((bcadd($a,$b,10)),$d,4);
//        dump($c);exit;

        $data=$this->zhifuadd();
        //getContent将文件读取，由json_decode转换成数组
        $data=  json_decode($data->getContent(),true);

        $weipipei=$data['weipipei'];

//        if(!empty($weipipei)){
//            return json(['code'=>0,'mes'=>'存在未匹配流水，修改后才能保存']);
//        }
        $data=$data['pipei'];

        $extends=array_filter($data);

        if(!$extends){
            return json(['code'=>0,'mes'=>'当前没有匹配数据']);
        }

        foreach ($data as $key => $row) {
            $aa[$key]=$row['yuefen'];
            $bb[$key]=$row['diqu'];
            $cc[$key]=$row['yewuyuan'];
            $dd[$key]=$row['daibiao'];
            $ee[$key]=$row['zhuguan'];
            $ff[$key]=$row['pinming'];
            $gg[$key]=$row['guige'];
            $hh[$key]=$row['bumenjingli'];
            $ii[$key]=$row['shangyegongsi'];
            $jj[$key]=$row['kehumingcheng2'];
            $kk[$key]=$row['kehumingcheng1'];
        }
        array_multisort($aa, $bb,$cc,$dd,$ee,$ff,$gg,$hh,$ii,$kk,$data);


        if(!empty($data)){
            $linshi['yuefen']='';
            $linshi['diqu']='';
            $linshi['yewuyuan']='';
            $linshi['daibiao']='';
            $linshi['zhuguan']='';
            $linshi['kehumingcheng1']='';
            $linshi['kehumingcheng2']='';
            $linshi['bumenjingli']='';
            $linshi['yiyuanjibie']='';
            $linshi['shangyegongsi']='';
            $linshi['pinming']='';
            $linshi['guige']='';
            $a=-1;
            $fahuo=[];
            foreach ($data as $k => $v){


                if(
                    $linshi['yuefen']== $v['yuefen']&&
                    $linshi['diqu']==$v['diqu']&&
                    $linshi['yewuyuan']== $v['yewuyuan']&&
                    $linshi['zhuguan']==$v['zhuguan']&&
                    $linshi['daibiao']== $v['daibiao']&&
                    $linshi['kehumingcheng1']== $v['kehumingcheng1']&&
                    $linshi['kehumingcheng2']== $v['kehumingcheng2']&&
                    $linshi['bumenjingli']== $v['bumenjingli']&&
                    $linshi['shangyegongsi']== $v['shangyegongsi']&&
                    $linshi['pinming']==$v['pinming']&&
                    $linshi['guige']== $v['guige']&&
                    $linshi['yiyuanjibie']== $v['yiyuanjibie']

                ){

                    $fahuo[$a]['benyuejinhuo']+=$data[$k]['med_salenum'];
                    //$fahuo[$a]['med_salenum']+=$data[$k]['med_salenum'];

                    $fahuo[$a]['benyuexiaoshou']=round(bcmul((bcadd($fahuo[$a]['shangyueyushu'],$fahuo[$a]['benyuejinhuo'],2)),0.7,2));
                    $fahuo[$a]['benyueyushu']=bcsub(bcadd($fahuo[$a]['shangyueyushu'],$fahuo[$a]['benyuejinhuo'],3),$fahuo[$a]['benyuexiaoshou'],2);

                    $fahuo[$a]['lunwenfeijine']=round(bcmul($fahuo[$a]['lunwenfei'],$fahuo[$a]['benyuexiaoshou'],10),2);
                    $fahuo[$a]['zhuguanjiangjin']=round(bcmul($fahuo[$a]['zhuguanjiangjinticheng'],$fahuo[$a]['benyuexiaoshou'],10),2);
                    $fahuo[$a]['jinglijiangjin']=round(bcmul($fahuo[$a]['jinglijiangjinticheng'],$fahuo[$a]['benyuexiaoshou'],10),2);

                    //工资部分
                    if(!empty($fahuo[$a]['daibiao'])){
                        $fahuo[$a]['daibiaojiangjin']+=bcmul(bcmul($fahuo[$a]['daibiaojiangjinticheng'],$data[$k]['benyuejinhuo'],2),0.7,2);
                        $fahuo[$a]['shangyegonghuojia']=$v['shangyegonghuojia'];
                        $fahuo[$a]['wanchengjine']+=bcmul($fahuo[$a]['benyuexiaoshou'],$fahuo[$a]['shangyegonghuojia'],2);
                        $fahuo[$a]['renwu']=$v['renwu'];
                        if(!empty($fahuo[$a]['renwu'])){
                            $fahuo[$a]['wanchenglv']=bcdiv($fahuo[$a]['wanchengjine'],$fahuo[$a]['renwu'],2);
                        }
                        if(!empty($fahuo[$a]['wanchenglv'])&&$fahuo[$a]['wanchenglv']<0.8){
                            $fahuo[$a]['shizhijine']=0;
                        }elseif($fahuo[$a]['wanchenglv']>=1 || empty($fahuo[$a]['wanchenglv'])){
                            $fahuo[$a]['shizhijine']=$fahuo[$a]['daibiaojiangjin'];
                        }else{
                            $fahuo[$a]['shizhijine']=bcmul($fahuo[$a]['daibiaojiangjin'],$fahuo[$a]['wanchenglv'],2);
                        }

                    }
                    if(!empty($fahuo[$a]['yewuyuan'])){
                        //dump($fahuo[$a]['abbiaozhunshuihou']);exit;
                        $fahuo[$a]['abjine']+=bcmul(bcmul($fahuo[$a]['abbiaozhunshuihou'],$data[$k]['benyuejinhuo'],3),0.7,2);

                        $fahuo[$a]['shangyegonghuojia']=$v['shangyegonghuojia'];
                        $fahuo[$a]['wanchengjine']+=round(bcmul($fahuo[$a]['benyuexiaoshou'],$fahuo[$a]['shangyegonghuojia'],3),2);
                        $fahuo[$a]['renwu']=$v['renwu'];
                        if(!empty($fahuo[$a]['renwu'])){
                            $fahuo[$a]['wanchenglv']=round(bcdiv($fahuo[$a]['wanchengjine'],$fahuo[$a]['renwu'],3),2);
                        }
                        $fahuo[$a]['jiangfa']=round(bcmul((bcsub($fahuo[$a]['wanchengjine'],$fahuo[$a]['renwu'],3)),0.01,3),2);
                        $fahuo[$a]['shizhijine']=round(bcadd($fahuo[$a]['abjine'],$fahuo[$a]['jiangfa'],3),2);
                    }

                }else{

                    $linshi['yuefen'] = $v['yuefen'];
                    $linshi['diqu'] = $v['diqu'];
                    $linshi['yewuyuan'] = $v['yewuyuan'];
                    $linshi['daibiao'] = $v['daibiao'];
                    $linshi['zhuguan'] = $v['zhuguan'];
                    $linshi['kehumingcheng1'] = $v['kehumingcheng1'];
                    $linshi['kehumingcheng2'] = $v['kehumingcheng2'];
                    $linshi['bumenjingli'] = $v['bumenjingli'];
                    $linshi['yiyuanjibie'] = $v['yiyuanjibie'];
                    $linshi['shangyegongsi'] = $v['shangyegongsi'];
                    $linshi['pinming'] = $v['pinming'];
                    $linshi['guige'] = $v['guige'];
                    $a++;

                    $fahuo[$a]=$v;

                    $fahuo[$a]['benyuejinhuo']=$v['med_salenum'];
                    //$fahuo[$a]['med_salenum']=$v['med_salenum'];
                    $fahuo[$a]['benyuexiaoshou']=round(bcmul((bcadd($fahuo[$a]['shangyueyushu'],$fahuo[$a]['benyuejinhuo'],2)),0.7,2));
                    $fahuo[$a]['benyueyushu']=bcsub(bcadd($fahuo[$a]['shangyueyushu'],$fahuo[$a]['benyuejinhuo'],3),$fahuo[$a]['benyuexiaoshou'],2);

                    $fahuo[$a]['shangyegonghuojia']=$v['shangyegonghuojia'];
                    $fahuo[$a]['renwu']=$v['renwu'];
                    $fahuo[$a]['lunwenfeijine']=round(bcmul($fahuo[$a]['lunwenfei'],$fahuo[$a]['benyuexiaoshou'],3),2);
                    $fahuo[$a]['zhuguanjiangjin']=round(bcmul($fahuo[$a]['zhuguanjiangjinticheng'],$fahuo[$a]['benyuexiaoshou'],3),2);
                    $fahuo[$a]['jinglijiangjin']=round(bcmul($fahuo[$a]['jinglijiangjinticheng'],$fahuo[$a]['benyuexiaoshou'],3),2);
                    $fahuo[$a]['wanchengjine']=bcmul($fahuo[$a]['benyuexiaoshou'],$fahuo[$a]['shangyegonghuojia'],2);
                    if($fahuo[$a]['renwu']>0){
                        $fahuo[$a]['wanchenglv']=bcdiv($fahuo[$a]['wanchengjine'],$fahuo[$a]['renwu'],2);
                    }

                    //dump(empty($fahuo[$a]['daibiao']));
                    //工资部分
                    if(empty($fahuo[$a]['daibiao'])&&empty($fahuo[$a]['yewuyuan'])){
                        return json(['code'=>0,'mes'=>'业务员和代表必须有一个']);
                    }elseif(!empty($fahuo[$a]['daibiao'])&&!empty($fahuo[$a]['yewuyuan'])){
                        return json(['code'=>0,'mes'=>'业务员和代表只能有一个']);
                    }
                    if(!empty($fahuo[$a]['daibiao'])){

                        $fahuo[$a]['daibiaojiangjin']=round(bcmul($fahuo[$a]['daibiaojiangjinticheng'],$fahuo[$a]['benyuexiaoshou'],3),2);
                        $fahuo[$a]['abbiaozhunshuihou']='';
                        $fahuo[$a]['abjine']='';
                        if(!empty($fahuo[$a]['wanchenglv'])&&$fahuo[$a]['wanchenglv']<0.8){
                            $fahuo[$a]['shizhijine']=0;
                        }elseif($fahuo[$a]['wanchenglv']>=1 || empty($fahuo[$a]['wanchenglv'])){
                            $fahuo[$a]['shizhijine']=$fahuo[$a]['daibiaojiangjin'];

                        }else{
                            $fahuo[$a]['shizhijine']=bcmul($fahuo[$a]['daibiaojiangjin'],$fahuo[$a]['wanchenglv'],3);
                        }
                    }
                    elseif(!empty($fahuo[$a]['yewuyuan'])){
                        $fahuo[$a]['daibiaojiangjinticheng']='';
                        $fahuo[$a]['daibiaojiangjin']='';
                        //dump(round(bcmul($fahuo[$a]['abbiaozhunshuihou'],$fahuo[$a]['benyuexiaoshou'],3),2));exit;
                        $fahuo[$a]['abjine']=round(bcmul($fahuo[$a]['abbiaozhunshuihou'],$fahuo[$a]['benyuexiaoshou'],3),2);
                        $fahuo[$a]['jiangfa']=round(bcmul((bcsub($fahuo[$a]['wanchengjine'],$fahuo[$a]['renwu'],3)),0.01,3),2);
                        $fahuo[$a]['shizhijine']=round(bcadd($fahuo[$a]['abjine'],$fahuo[$a]['jiangfa'],3),2);

                    }//


                }
            }
            //判断是否存在，存在重新计算本月支付
            //$res=array(array());
            $res=(int)'';
            $ress=(int)'';
//            dump($H);exit;
            for($h=0;$h<count($fahuo);$h++) {

                $cunzai = Db::name('zhifu')->where('yuefen', $fahuo[$h]['yuefen'])
                    ->where('guige', $fahuo[$h]['guige'])
                    ->where('pinming', $fahuo[$h]['pinming'])
                    ->where('shangyegongsi', $fahuo[$h]['shangyegongsi'])
                    ->where(function($query)use($fahuo,$h){
                        $query->whereor('kehumingcheng1',$fahuo[$h]['kehumingcheng1'])->whereor('kehumingcheng2',$fahuo[$h]['kehumingcheng2']);
                    })
                    ->where('yewuyuan','=',$fahuo[$h]['yewuyuan'])
                    ->where('daibiao','=',$fahuo[$h]['daibiao'])
                    ->field('id')
                    ->find();
                Db::startTrans();
                try{
                    if ($cunzai['id']!=null) {
                        $res=Db::name('zhifu')->where('id', $cunzai['id'])->update([
                            'shangyueyushu' => $fahuo[$h]['shangyueyushu'],
                            'benyuejinhuo' => $fahuo[$h]['benyuejinhuo'],
                            'benyuexiaoshou' => $fahuo[$h]['benyuexiaoshou'],
                            'benyueyushu' => $fahuo[$h]['benyueyushu'],
                            'abjine' => $fahuo[$h]['abjine'],
                            'daibiaojiangjin' => $fahuo[$h]['daibiaojiangjin'],
                            'diqu'=>$fahuo[$h]['diqu'],
                            'bumen'=>$fahuo[$h]['bumen'],
                            'yewuyuan'=>$fahuo[$h]['yewuyuan'],
                            'daibiao'=>$fahuo[$h]['daibiao'],
                            'zhuguan'=>$fahuo[$h]['zhuguan'],
                            'kehumingcheng1'=>$fahuo[$h]['kehumingcheng1'],
                            'kehumingcheng2'=>$fahuo[$h]['kehumingcheng2'],
                            'bumenjingli'=>$fahuo[$h]['bumenjingli'],
                            'yiyuanjibie'=>$fahuo[$h]['yiyuanjibie'],
                            'shangyegongsi'=>$fahuo[$h]['shangyegongsi'],
                            'pinming'=>$fahuo[$h]['pinming'],
                            'guige'=>$fahuo[$h]['guige'],
                            'lunwenfei'=>$fahuo[$h]['lunwenfei'],
                            'lunwenfeijine'=>$fahuo[$h]['lunwenfeijine'],
                            'daibiaojiangjinticheng'=>$fahuo[$h]['daibiaojiangjinticheng'],
                            'zhuguanjiangjinticheng'=>$fahuo[$h]['zhuguanjiangjinticheng'],
                            'zhuguanjiangjin'=>$fahuo[$h]['zhuguanjiangjin'],
                            'jinglijiangjinticheng'=>$fahuo[$h]['jinglijiangjinticheng'],
                            'jinglijiangjin'=>$fahuo[$h]['jinglijiangjin'],
                            'shangyegonghuojia'=>$fahuo[$h]['shangyegonghuojia'],
                            'wanchengjine'=>$fahuo[$h]['wanchengjine'],
                            'renwu'=>$fahuo[$h]['renwu'],
                            'wanchenglv'=>$fahuo[$h]['wanchenglv'],
                            'jiangfa'=>$fahuo[$h]['jiangfa'],
                            'shizhijine'=>$fahuo[$h]['shizhijine'],
                            'abbiaozhunshuihou'=>$fahuo[$h]['abbiaozhunshuihou'],
                            'yuefen'=>$fahuo[$h]['yuefen'],
                        ]);

                    } else {
                        $ress = Db::name('zhifu')->strict(false)->insert($fahuo[$h]);
                    }
                    Db::commit();
                }catch (\Exception $e){
                    Db::rollback();

                    return json(['mes'=>($e->getMessage())]);
                }

            }
        }else{
            return json(['code'=>0,'mes'=>'为空']);
        }
        $timenow=input('time');
        $count=Db::name('zhifu')->where('yuefen',$timenow)
            ->count();

        $currentPage=input('currentPage');
        $pagenum=input('pageCount');
        $row=ceil($count/$pagenum);

        $zhifujieguo=Db::name('zhifu')->where('yuefen',$timenow)->limit($currentPage*$pagenum-$pagenum,$pagenum)->order('yuefen')->select();
        // dump($res);
//        if(is_array($res)){
//            $res=array_filter($res);
//        }


        //dump($mm);
        if($res||$ress){

            return json(['code'=>200,'mes'=>'成功','jieguo'=>$zhifujieguo,'row'=>$row*10,'count'=>$count]);
        }else{
            return json(['code'=>200,'mes'=>'成功','jieguo'=>$zhifujieguo,'row'=>$row*10,'count'=>$count]);
        }
    }

    public function zhifusearch(){
        $time=input('time');

        $zhuguan=input('zhuguan');
        $pinming=input('pinming');
        $guige=input('guige');
        $jingli=input('jingli');
        $yewuyuan=input('yewuyuan');
        $daibiao=input('daibiao');

        $where=[];

        if(!empty($zhuguan)){
            $zhuguan=['zhuguan','=',$zhuguan];
            array_push($where,$zhuguan);
        }if(!empty($pinming)){
            $pinming=['pinming','=',$pinming];
            array_push($where,$pinming);
        }if(!empty($guige)){
            $guige=['guige','=',$guige];
            array_push($where,$guige);
        }if(!empty($jingli)){
            $jingli=['bumenjingli','=',$jingli];
            array_push($where,$jingli);
        }if(!empty($yewuyuan)){
            $yewuyuan=['yewuyuan','=',$yewuyuan];
            array_push($where,$yewuyuan);
        }if(!empty($daibiao)){
            $daibiao=['daibiao','=',$daibiao];
            array_push($where,$daibiao);
        }

        $count=db('zhifu')->where('yuefen',$time)->when(!empty($where),function ($query)use($where){
            $query->where([$where]);
        })->count();
        $currentPage=input('currentPage');  //当前几页
        $pagenum=input('pageCount');  //每页几条
        $row=ceil($count/$pagenum);
        $data=db('zhifu')->where('yuefen',$time)->order('yuefen')->when(!empty($where),function ($query)use($where){
            $query->where([$where]);
        })->limit($currentPage*$pagenum-$pagenum,$pagenum)->select();

        if((!empty($yewuyuan)||!empty($daibiao))&&!empty($count)){
            $data2=db('zhifu')->where('yuefen',$time)->order('yuefen')->when(!empty($where),function ($query)use($where){
                $query->where([$where]);
            })->field('sum(benyuexiaoshou) as benyuexiaoshou,sum(shizhijine) as shizhijine')->select();
            foreach ($data2 as $d){
            }
            $d['yuefen']='合计';
            array_push($data,$d);
            $count++;
            $row=ceil($count/$pagenum);
        }


        if(empty($data)){
            return json(['code'=>100,'mes'=>'无结果']);
        }
        return json(['code'=>200,'mes'=>'成功','data'=>$data,'row'=>$row*10,'count'=>$count]);
    }
    public function zhifudaochu(){
        require_once '../vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
        require_once '../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
        $yuefen=input('time');
        $data=db('zhifu')->where('yuefen',$yuefen)->select();

        $a=date('Y-m-d H:i');
        //dump($data);exit;
        $PHPExcel = new \PHPExcel(); //实例化类
        //创建sheet
        // $PHPExcel->createSheet();
        //获取sheet
        $PHPSheet=$PHPExcel->setActiveSheetIndex(0);//获取文件薄
        $PHPSheet->setTitle('支付表');//获取栏目的sheet修改名字
        $PHPSheet->setCellValue('A1','id')->setCellValue('B1','月份')->setCellValue('C1','地区')->setCellValue('D1','部门')->setCellValue('E1','部门经理')
            ->setCellValue('F1','主管')->setCellValue('G1','业务员')->setCellValue('H1','代表')->setCellValue('I1','医院级别')->setCellValue('J1','客户名称1')->setCellValue('K1','客户名称2')
            ->setCellValue('L1','商业公司')->setCellValue('M1','品名')->setCellValue('N1','规格')->setCellValue('O1','上月余数')->setCellValue('P1','本月进货')->setCellValue('Q1','本月销售')
            ->setCellValue('R1','本月余数')->setCellValue('S1','ab标准税后')->setCellValue('T1','ab金额')->setCellValue('U1','论文费')->setCellValue('V1','论文费金额')->setCellValue('W1','代表奖金提成')
            ->setCellValue('X1','代表奖金')->setCellValue('Y1','主管奖金提成')->setCellValue('Z1','主管奖金')->setCellValue('AA1','经理奖金提成')->setCellValue('AB1','经理奖金')->setCellValue('AC1','商业供货价')
            ->setCellValue('AD1','完成金额')->setCellValue('AJ1','主管')->setCellValue('AK1','主管实支金额')->setCellValue('AL1','经理')->setCellValue('AM1','经理实支金额')
            ->setCellValue('AE1','任务')->setCellValue('AF1','完成率')->setCellValue('AG1','奖罚')->setCellValue('AH1','实支金额')->setCellValue('AI1','生成时间')->setCellValue('AI2',$a);
        $PHPSheet->getStyle('A1')->getFont()->setBold(true);
        $PHPSheet->getStyle('B1')->getFont()->setBold(true);
        $PHPSheet->getStyle('C1')->getFont()->setBold(true);
        $PHPSheet->getStyle('D1')->getFont()->setBold(true);
        $PHPSheet->getStyle('E1')->getFont()->setBold(true);
        $PHPSheet->getStyle('F1')->getFont()->setBold(true);
        $PHPSheet->getStyle('G1')->getFont()->setBold(true);
        $PHPSheet->getStyle('H1')->getFont()->setBold(true);
        $PHPSheet->getStyle('I1')->getFont()->setBold(true);
        $PHPSheet->getStyle('J1')->getFont()->setBold(true);
        $PHPSheet->getStyle('K1')->getFont()->setBold(true);
        $PHPSheet->getStyle('L1')->getFont()->setBold(true);
        $PHPSheet->getStyle('M1')->getFont()->setBold(true);
        $PHPSheet->getStyle('N1')->getFont()->setBold(true);
        $PHPSheet->getStyle('O1')->getFont()->setBold(true);
        $PHPSheet->getStyle('P1')->getFont()->setBold(true);
        $PHPSheet->getStyle('Q1')->getFont()->setBold(true);
        $PHPSheet->getStyle('R1')->getFont()->setBold(true);
        $PHPSheet->getStyle('S1')->getFont()->setBold(true);
        $PHPSheet->getStyle('T1')->getFont()->setBold(true);
        $PHPSheet->getStyle('U1')->getFont()->setBold(true);
        $PHPSheet->getStyle('V1')->getFont()->setBold(true);
        $PHPSheet->getStyle('W1')->getFont()->setBold(true);
        $PHPSheet->getStyle('X1')->getFont()->setBold(true);
        $PHPSheet->getStyle('Y1')->getFont()->setBold(true);
        $PHPSheet->getStyle('Z1')->getFont()->setBold(true);
        $PHPSheet->getStyle('AA1')->getFont()->setBold(true);
        $PHPSheet->getStyle('AB1')->getFont()->setBold(true);
        $PHPSheet->getStyle('AC1')->getFont()->setBold(true);
        $PHPSheet->getStyle('AD1')->getFont()->setBold(true);
        $PHPSheet->getStyle('AE1')->getFont()->setBold(true);
        $PHPSheet->getStyle('AF1')->getFont()->setBold(true);
        $PHPSheet->getStyle('AG1')->getFont()->setBold(true);
        $PHPSheet->getStyle('AH1')->getFont()->setBold(true);
        $PHPSheet->getStyle('AI1')->getFont()->setBold(true);
        $PHPSheet->getStyle('AJ1')->getFont()->setBold(true);
        $PHPSheet->getStyle('AK1')->getFont()->setBold(true);
        $PHPSheet->getStyle('AL1')->getFont()->setBold(true);
        $PHPSheet->getStyle('AM1')->getFont()->setBold(true);

        $PHPSheet->getColumnDimension('A')->setWidth(5);
        $PHPSheet->getColumnDimension('B')->setWidth(9);
        $PHPSheet->getColumnDimension('C')->setWidth(30);
        $PHPSheet->getColumnDimension('D')->setWidth(9);
        $PHPSheet->getColumnDimension('E')->setWidth(13);
        $PHPSheet->getColumnDimension('F')->setWidth(13);
        $PHPSheet->getColumnDimension('G')->setWidth(13);
        $PHPSheet->getColumnDimension('H')->setWidth(13);
        $PHPSheet->getColumnDimension('I')->setWidth(13);
        $PHPSheet->getColumnDimension('J')->setWidth(37);
        $PHPSheet->getColumnDimension('K')->setWidth(37);
        $PHPSheet->getColumnDimension('L')->setWidth(37);
        $PHPSheet->getColumnDimension('M')->setWidth(35);
        $PHPSheet->getColumnDimension('N')->setWidth(20);
        $PHPSheet->getColumnDimension('O')->setWidth(20);
        $PHPSheet->getColumnDimension('P')->setWidth(20);
        $PHPSheet->getColumnDimension('Q')->setWidth(20);
        $PHPSheet->getColumnDimension('R')->setWidth(20);
        $PHPSheet->getColumnDimension('S')->setWidth(20);
        $PHPSheet->getColumnDimension('T')->setWidth(20);
        $PHPSheet->getColumnDimension('U')->setWidth(20);
        $PHPSheet->getColumnDimension('V')->setWidth(20);
        $PHPSheet->getColumnDimension('W')->setWidth(20);
        $PHPSheet->getColumnDimension('X')->setWidth(20);
        $PHPSheet->getColumnDimension('Y')->setWidth(20);
        $PHPSheet->getColumnDimension('Z')->setWidth(20);
        $PHPSheet->getColumnDimension('AA')->setWidth(20);
        $PHPSheet->getColumnDimension('AB')->setWidth(20);
        $PHPSheet->getColumnDimension('AC')->setWidth(20);
        $PHPSheet->getColumnDimension('AD')->setWidth(20);
        $PHPSheet->getColumnDimension('AE')->setWidth(20);
        $PHPSheet->getColumnDimension('AF')->setWidth(20);
        $PHPSheet->getColumnDimension('AG')->setWidth(20);
        $PHPSheet->getColumnDimension('AH')->setWidth(20);
        $PHPSheet->getColumnDimension('AI')->setWidth(20);
        $PHPSheet->getColumnDimension('AJ')->setWidth(13);
        $PHPSheet->getColumnDimension('AK')->setWidth(20);
        $PHPSheet->getColumnDimension('AL')->setWidth(13);
        $PHPSheet->getColumnDimension('AM')->setWidth(20);

        $PHPSheet->getRowDimension(1)->setRowHeight(34);
        if(empty($data)){
            goto tiaoguo;
        }
        foreach($data as $key => $value){
            $PHPSheet->setCellValue('A'.($key+2),$value['id'])->setCellValue('B'.($key+2),$value['yuefen'])->setCellValue('C'.($key+2),$value['diqu'])
                ->setCellValue('D'.($key+2),$value['bumen'])->setCellValue('E'.($key+2),$value['bumenjingli'])->setCellValue('F'.($key+2),$value['zhuguan'])
                ->setCellValue('G'.($key+2),$value['yewuyuan'])->setCellValue('H'.($key+2),$value['daibiao'])->setCellValue('I'.($key+2),$value['yiyuanjibie'])
                ->setCellValue('J'.($key+2),$value['kehumingcheng1'])->setCellValue('K'.($key+2),$value['kehumingcheng2'])->setCellValue('L'.($key+2),$value['shangyegongsi'])
                ->setCellValue('M'.($key+2),$value['pinming'])->setCellValue('N'.($key+2),$value['guige'])->setCellValue('O'.($key+2),$value['shangyueyushu'])
                ->setCellValue('P'.($key+2),$value['benyuejinhuo'])->setCellValue('Q'.($key+2),$value['benyuexiaoshou'])->setCellValue('R'.($key+2),$value['benyueyushu'])
                ->setCellValue('S'.($key+2),$value['abbiaozhunshuihou'])->setCellValue('T'.($key+2),$value['abjine'])->setCellValue('U'.($key+2),$value['lunwenfei'])
                ->setCellValue('V'.($key+2),$value['lunwenfeijine'])->setCellValue('W'.($key+2),$value['daibiaojiangjinticheng'])->setCellValue('X'.($key+2),$value['daibiaojiangjin'])
                ->setCellValue('Y'.($key+2),$value['zhuguanjiangjinticheng'])->setCellValue('Z'.($key+2),$value['zhuguanjiangjin'])->setCellValue('AA'.($key+2),$value['jinglijiangjinticheng'])
                ->setCellValue('AB'.($key+2),$value['jinglijiangjin'])->setCellValue('AC'.($key+2),$value['shangyegonghuojia'])->setCellValue('AD'.($key+2),$value['wanchengjine'])
                ->setCellValue('AE'.($key+2),$value['renwu'])->setCellValue('AF'.($key+2),$value['wanchenglv'])->setCellValue('AG'.($key+2),$value['jiangfa'])
                ->setCellValue('AH'.($key+2),$value['shizhijine']);

        }

        $zhuguan=Db::name('zhifu')->field('zhuguan,sum(lunwenfeijine) as lunwenfei,sum(zhuguanjiangjin) as zhuguanjiangjin,sum(wanchengjine)/sum(renwu) as wanchenglv')->group('zhuguan')->where('daibiao','neq','')->where('yuefen',$yuefen)->select();
        $bumenjingli=Db::name('zhifu')->field('bumenjingli,sum(jinglijiangjin) as jinglijiangjin,sum(wanchengjine)/sum(renwu) as wanchenglv')->group('bumenjingli')->where('yewuyuan','neq','')->where('yuefen',$yuefen)->select();

        for($i=0;$i<count($zhuguan);$i++){
            if($zhuguan[$i]['wanchenglv']<0.8){
                $zgsz=$zhuguan[$i]['lunwenfei'];
            }elseif($zhuguan[$i]['wanchenglv']>=1){
                $zgsz=bcadd($zhuguan[$i]['lunwenfei'],$zhuguan[$i]['zhuguanjiangjin'],2);
            }else{
                $zgsz=bcadd($zhuguan[$i]['lunwenfei'],bcmul($zhuguan[$i]['zhuguanjiangjin'],$zhuguan[$i]['wanchenglv'],2));
            }
            $PHPSheet->setCellValue('AJ'.($i+2),$zhuguan[$i]['zhuguan'])->setCellValue('AK'.($i+2),$zgsz);
        }
        for($j=0;$j<count($bumenjingli);$j++){
            if($bumenjingli[$j]['wanchenglv']<0.8){
                $jlsz=0;
            }elseif($bumenjingli[$j]['wanchenglv']>=1){
                $jlsz=$bumenjingli[$j]['jinglijiangjin'];
            }else{
                $jlsz=bcmul($bumenjingli[$j]['jinglijiangjin'],$bumenjingli[$j]['wanchenglv'],2);
            }
            $PHPSheet->setCellValue('AL'.($j+2),$bumenjingli[$j]['bumenjingli'])->setCellValue('AM'.($j+2),$jlsz);
        }

        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    //'style' => PHPExcel_Style_Border::BORDER_THICK,//边框是粗的
                    'style' => \PHPExcel_Style_Border::BORDER_THIN,//细边框
                    //'color' => array('argb' => 'FFFF0000'),
                ),
            ),
        );
        $PHPSheet->getStyle('A2:AH'.($key+2))->applyFromArray($styleArray);

        $styleArray2 = array(
            'borders' => array(
                'allborders' => array(
                    //'style' => \PHPExcel_Style_Border::BORDER_THICK,//边框是粗的
                    'style' => \PHPExcel_Style_Border::BORDER_THIN,//细边框
                    'color' => array('argb' => 'FFFF0000'),
                ),
            ),
        );
        $PHPSheet->getStyle('AJ1:AM1')->applyFromArray($styleArray2);

        $PHPSheet->getStyle('A1:AM1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPSheet->getStyle('A1:AM1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $PHPSheet->getStyle('AI2')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $PHPSheet->getStyle('AI2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // var_dump($PHPWriter);die;
        tiaoguo:
        $PHPWriter=\PHPExcel_IOFactory::createWriter($PHPExcel,'Excel2007');

        ob_end_clean();// 就是加这句
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
        header('Content-Disposition: attachment;filename="支付.xlsx"');

        header('Cache-Control:max-age=0');
        $PHPWriter->save('php://output');
    }

    public function linshidaoru(){

        ini_set('memory_limit','512M');
        require_once('../vendor/phpoffice/phpexcel/Classes/PHPExcel/Reader/Excel2007.php');
        require_once('../vendor/phpoffice/phpexcel/Classes/PHPExcel/Reader/Excel5.php');
        $data = request()->file('data');
        if($data){
            $wenjian=$data->validate(['ext'=>'xls,xlsx'])->move('../public/uploads');
            $wenjian1=str_replace("\\","/",$wenjian->getSaveName());
            $suffix = $wenjian->getExtension();
            //判断哪种类型
            if($suffix=="xlsx"){
                $reader = \PHPExcel_IOFactory::createReader('Excel2007');
            }else{
                $reader = \PHPExcel_IOFactory::createReader('Excel5');
            }
        }else{
            $this->error();
        }

        $a='../public/uploads/'.$wenjian1;      //相对路径
        if(!$reader->canRead($a)){
            $reader = \PHPExcel_IOFactory::createReader('Excel5');
        }
        $excel = $reader->load($a,$encode = 'utf-8');
        // $objPHPExcel = $objReader->load($a); //读取excel文件
        $sheetContent = $excel -> getSheet(0) -> toArray();
        unset($sheetContent[0]);
        if(empty($sheetContent)){
            return json(['code'=>0,'mes'=>'空数据']);
        }
        foreach ($sheetContent as $k => $v){
            if(empty(array_filter($v))) {
                continue;
            }
            $dataa['diqu']=str_replace(' ','',$v[0]);
            $dataa['bumen']=str_replace(' ','',$v[1]);
            $dataa['bumenjingli']=str_replace(' ','',$v[2]);
            $dataa['zhuguan']=str_replace(' ','',$v[3]);
            $dataa['yewuyuan']=str_replace(' ','',$v[4]);
            $dataa['daibiao']=str_replace(' ','',$v[5]);
            $dataa['yiyuanjibie']=str_replace(' ','',$v[6]);
            $dataa['kehumingcheng1']=str_replace(' ','',$v[7]);
            $dataa['shangyegongsi']=str_replace(' ','',$v[8]);
            $dataa['pinming']=str_replace(' ','',$v[9]);
            $dataa['guige']=str_replace(' ','',$v[10]);
            //$dataa['chandi']=str_replace(' ','',$v[11]);
            $dataa['shangyueyushu']=str_replace(' ','',$v[12]);
            $dataa['benyuejinhuo']=str_replace(' ','',$v[13]);
            $dataa['benyuexiaoshou']=str_replace(' ','',$v[14]);
            $dataa['benyueyushu']=str_replace(' ','',$v[15]);
            $dataa['abbiaozhunshuihou']=str_replace(' ','',$v[16]);
            $dataa['abjine']=str_replace(' ','',$v[17]);
            $dataa['lunwenfei']=str_replace(' ','',$v[18]);
            $dataa['lunwenfeijine']=str_replace(' ','',$v[19]);
            $dataa['jinglijiangjinticheng']=str_replace(' ','',$v[20]);
            $dataa['jinglijiangjin']=str_replace(' ','',$v[21]);
            $dataa['zhuguanjiangjinticheng']=str_replace(' ','',$v[22]);
            $dataa['zhuguanjiangjin']=str_replace(' ','',$v[23]);
            $dataa['daibiaojiangjinticheng']=str_replace(' ','',$v[24]);
            $dataa['daibiaojiangjin']=str_replace(' ','',$v[25]);
            $dataa['shangyegonghuojia']=str_replace(' ','',$v[26]);
            $dataa['wanchengjine']=str_replace(' ','',$v[27]);
            $dataa['renwu']=str_replace(' ','',$v[28]);
            $dataa['wanchenglv']=str_replace(' ','',$v[29]);
            $dataa['jiangfa']=str_replace(' ','',$v[30]);
            $dataa['shizhijine']=str_replace(' ','',$v[31]);
//            $dataa['yapiabbiaozhun']=str_replace(' ','',$v[32]);
//            $dataa['zhifufangfa']=str_replace(' ','',$v[33]);
            $dataa['kehumingcheng2']=str_replace(' ','',$v[34]);
            // $dataa['status']='正常在职';
            $dataa['yuefen']=str_replace([' ','/'],['','-'],$v[36]);
            $res[] = $dataa;

        }
        $total=count($res);

        $rel=db('zhifu')->limit(200)->insertall($res);

        if($rel > 0){

            return json(['code'=>200,'mse'=>'成功','total'=>$total]);
        }else{
            return json(['code'=>0,'mes'=>'失败','total'=>$total]);

        }
    }

    /**前端导出
     * @return void
     */

    public function weipipeidaochu(){
        require_once '../vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
        require_once '../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
        $data=input('data');
        $a=date('Y-m-d H:i');
        if(empty($data)){
            return json(['code'=>0,'mes'=>'没数据']);
        }
        $PHPExcel = new \PHPExcel(); //实例化类
        //创建sheet
        // $PHPExcel->createSheet();
        //获取sheet

        $PHPSheet=$PHPExcel->setActiveSheetIndex(0);//获取文件薄
        $PHPSheet->setTitle('未匹配，仅限当次使用');//获取栏目的sheet修改名字
        $PHPSheet->setCellValue('A1','入库数量')->setCellValue('B1','商业公司名称')->setCellValue('C1','日期')->setCellValue('D1','产品名称')
            ->setCellValue('E1','规格')->setCellValue('F1','计量单位')->setCellValue('G1','销售数量')->setCellValue('H1','批号')
            ->setCellValue('I1','单价')->setCellValue('J1','客户名称A')->setCellValue('K1','客户名称B')
            ->setCellValue('L1','供应商')->setCellValue('M1','产地')->setCellValue('N1','生成时间');
        foreach($data as $key => $value){
            $PHPSheet->setCellValue('A'.($key+2),$value['innums'])->setCellValue('B'.($key+2),$value['facname'])->setCellValue('C'.($key+2),$value['in_time'])
                ->setCellValue('D'.($key+2),$value['med_name'])->setCellValue('E'.($key+2),$value['med_specs'])->setCellValue('F'.($key+2),$value['med_unit'])
                ->setCellValue('G'.($key+2),$value['med_salenum'])->setCellValue('H'.($key+2),$value['med_batchnum'])->setCellValue('I'.($key+2),$value['med_price'])
                ->setCellValue('J'.($key+2),$value['customer_name'])->setCellValue('K'.($key+2),$value['customer_nameb'])->setCellValue('L'.($key+2),$value['buss_name'])
                ->setCellValue('M'.($key+2),$value['buss_origin'])->setCellValue('N'.($key+2),$a);
        }
        ob_end_clean();// 就是加这句
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
        header('Content-Disposition: attachment;filename="未匹配，仅限当次使用.xlsx"');
        $PHPWriter=\PHPExcel_IOFactory::createWriter($PHPExcel,'Excel2007');
        header('Cache-Control:max-age=0');
        $PHPWriter->save('php://output');
    }

}
