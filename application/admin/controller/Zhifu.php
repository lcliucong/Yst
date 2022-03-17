<?php
namespace app\admin\controller;
use think\Db;
use PHPExcel;
use think\facade\Request;
use app\mainmenu\controller\Common;
use think\Collection;
use app\admin\model\Zhifu as Zf;


class Zhifu extends Common{
    public function ceshipipei(){
        ini_set('memory_limit','1024M');
        set_time_limit(0);
        $timenow=input('time');
        $time=input('time');
        $time=explode('-',$time);
        $ces=array_pop($time);
//        dump($timenow);exit;//
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
        $start = microtime(true);
        $yaoming=Db::name('flowofmed')->where('in_time','between time',[$timenow.'-01',$timenow.'-'.$time2])->distinct(true)->field('med_name')->select();
//        dump($yaoming);exit;

        foreach($yaoming as $ym) {

            $data = Db::name('flowofmed')->field('med_name,med_specs,customer_name,customer_nameb,med_price,facname')
                ->where('med_name', $ym['med_name'])
                ->where('in_time', 'between time', [$timenow . '-01', $timenow . '-' . $time2])
                ->group('med_name,med_specs,customer_name,customer_nameb')
                ->order('med_name,med_specs')
                ->select();
            $zhifu = Db::name('zhifu')->field('kehumingcheng1,pinming,guige,kehumingcheng2')
                ->where('pinming', $ym['med_name'])
                ->where('yuefen', $timeup)
                ->order('pinming')
                ->select();

            $zhifu30 = Db::name('zhifu30')->field('kehumingcheng1,pinming,guige,kehumingcheng2')
                ->where('pinming', $ym['med_name'])
                ->where('yuefen', $timeup)
                ->order('pinming,guige')
                ->select();
            $zhifu60 = Db::name('zhifu60')->field('kehumingcheng1,pinming,guige,kehumingcheng2')
                ->where('pinming', $ym['med_name'])
                ->where('yuefen', $timeup)
                ->order('pinming,guige')
                ->select();
            $xinxibeian = array_merge($zhifu, $zhifu30, $zhifu60);

            foreach ($data as $j => $value) {
                foreach ($xinxibeian as $v) {
                    if (
                        $data[$j]['med_name'] == $v['pinming'] &&
                        $data[$j]['med_specs'] == $v['guige'] &&
                        $data[$j]['customer_name'] == $v['kehumingcheng1'] &&
                        $data[$j]['customer_nameb'] == $v['kehumingcheng2']
                    ) {
                        $result[$i]['0'] = 0;
                        unset($v);
                        break;
                    }
                    unset($v);
                }
                if (empty($result[$i])) {
                    //添加未匹配成功原因

                    $data[$j]['message'] = '上月支付不存在（相等两条及以下视为不存在）';
                    foreach ($xinxibeian as $xinxi2) {
                        if ($data[$j]['med_name'] !== $xinxi2['pinming'] && $data[$j]['med_specs'] == $xinxi2['guige'] && $data[$j]['customer_name'] == $xinxi2['kehumingcheng1'] && $data[$j]['customer_nameb'] == $xinxi2['kehumingcheng2']
                        ) {
                            $data[$j]['message'] = '品名有误（存在规格，终端名称，终端别名一致）';
                            break;
                        } elseif ($data[$j]['customer_name'] !== $xinxi2['kehumingcheng1'] && $data[$j]['med_name'] == $xinxi2['pinming'] && $data[$j]['med_specs'] == $xinxi2['guige'] && $data[$j]['customer_nameb'] == $xinxi2['kehumingcheng2']) {
                            $data[$j]['message'] = '终端名称有误或不存在（存在品名，规格，终端别名一致）';
                            break;
                        } elseif ($data[$j]['med_specs'] !== $xinxi2['guige'] && $data[$j]['med_name'] == $xinxi2['pinming'] && $data[$j]['customer_name'] == $xinxi2['kehumingcheng1'] && $data[$j]['customer_nameb'] == $xinxi2['kehumingcheng2']) {
                            $data[$j]['message'] = '规格有误（存在品名，终端名称，终端别名一致）';
                            break;
                        } elseif ($data[$j]['customer_nameb'] !== $xinxi2['kehumingcheng2'] && $data[$j]['med_name'] == $xinxi2['pinming'] && $data[$j]['med_specs'] == $xinxi2['guige'] && $data[$j]['customer_name'] == $xinxi2['kehumingcheng1']) {
                            $data[$j]['message'] = '终端别名有误（存在品名，规格，终端名称一致）';
                            break;
                        }

                    }
                    $nomate[] = $data[$j];
                }
                //
                $i++;
                unset($value);
            }
        }
        $elapsed = (microtime(true) - $start);
        if(!empty($nomate)){        $nomate=array_values($nomate);}

        if($result||$nomate){
            return json(['code'=>200,'mes'=>'成功','time'=>$elapsed,'weipipei'=>$nomate]);
        }else{
            return json(['code'=>0,'mes'=>'不存在匹配结果']);
        }

    }
    public function zhifubaocun(){

       /**
        * $data=$this->zhifuadd();
        * getContent将文件读取，由json_decode转换成数组
        * $data=json_decode($data->getContent(),true);
        */
        ini_set('memory_limit','1024M');
        set_time_limit(0);
        $timenow=input('time');
        $time=input('time');
        $time=explode('-',$time);
        $ces=array_pop($time);
//        dump($timenow);exit;//
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
        $lsje=0;
        $dbje=0;
        $lsabje=0;
        $start = microtime(true);

        $yaoming=Db::name('zhifu')->where('yuefen',$timeup)->distinct(true)->field('pinming')->select();
        if(empty($yaoming)) return json(['code'=>'0','mes'=>'没有上月份数据，无法计算当月']);
        $gongshi=db::name('gongshi')->field('daibiao')->select();
        foreach($yaoming as $ym) {
            $i=-1;
            $data = Db::name('flowofmed')->field('facname,med_name,med_specs,customer_name,customer_nameb,med_price,sum(med_salenum) as med_salenum')
               // ->where('med_name', $ym['pinming'])
                ->where('in_time', 'between time', [$timenow . '-01', $timenow . '-' . $time2])
                ->group('med_name,med_specs,customer_name,customer_nameb')
                ->order('med_name')
                ->select();
            $zhifu = Db::name('zhifu')->field('diqu,bumen,bumenjingli,zhuguan,yewuyuan,daibiao,yiyuanjibie,shangyegongsi,
            benyueyushu,abbiaozhunshuihou,lunwenfei,zhuguanjiangjinticheng,daibiaojiangjinticheng,kehumingcheng1,pinming,
            guige,kehumingcheng2,jinglijiangjinticheng,shangyegonghuojia')
                ->where('pinming', $ym['pinming'])
                ->where('yuefen', $timeup)
                ->order('pinming')
                ->select();

            foreach ($zhifu as $value) {
                $i++;
                $new["benyuejinhuo"] = 0;
                $new['shangyueyushu'] = $value['benyueyushu']; //dump($value['benyueyushu']);exit;
                if($ym['pinming']=='开喉剑喷雾剂' && $value['guige']=='儿童20ml'&& in_array($value['daibiao'],$gongshi)){
                    foreach ($data as $k=> $liushui) {
                        if ($value['guige'] == $liushui['med_specs'] &&
                            $value['kehumingcheng1'] == $liushui['customer_name'] &&
                            $value['kehumingcheng2'] == $liushui['customer_nameb']
                        ){
                            if(in_array($liushui['med_salenum'],[0,1,2,3])){
                                $new["benyuejinhuo"] = 0;
                            }else
                            $new["benyuejinhuo"] = $liushui['med_salenum'];
                                if(($new['shangyueyushu']+$new['benyuejinhuo'])<0){
                                    $new['benyuexiaoshou'] = bcadd($new['shangyueyushu'], $new['benyuejinhuo'], 2);
                                    $new['benyueyushu'] = 0;
                                }
                            unset($data[$k]);
                            break;
                        }
                    }
                }
                else{
                    foreach ($data as $k=> $liushui) {
                        if ($value['guige'] == $liushui['med_specs'] &&
                            $value['kehumingcheng1'] == $liushui['customer_name'] &&
                            $value['kehumingcheng2'] == $liushui['customer_nameb']
                        ){

                            $new["benyuejinhuo"] = $liushui['med_salenum'];
//                            dump($new['shangyueyushu']+1);exit;

                                if(($new['shangyueyushu']+$new['benyuejinhuo'])<0){
                                    $new['benyuexiaoshou'] = bcadd($new['shangyueyushu'], $new['benyuejinhuo'], 2);
                                    $new['benyueyushu'] = 0;
                                }//dump($liushui);exit;
                                if($liushui['facname']=='河北丽泰医药有限公司'||$liushui['facname']=='河北谛康医药有限公司'){
                                    $new['benyueyushu']=0;
                                    $new['shangyueyushu']=$new["benyuejinhuo"] ;
                                }
                            unset($data[$k]);
                            break;
                        }

                    }
                }
                $new['yuefen'] = $timenow;
                $new['diqu'] = $value['diqu'];
                $new['yiyuanjibie'] = $value['yiyuanjibie'];
                $new['kehumingcheng1'] = $value['kehumingcheng1'];
                $new['kehumingcheng2'] = $value['kehumingcheng2'];
                $new['shangyegongsi'] = $value['shangyegongsi'];
                $new['bumen'] = $value['bumen'];

                if($new['bumen']=='直营'&&$new['benyuejinhuo']==0){
                    $new['benyuexiaoshou'] = 0;
                    $new['benyueyushu'] = $new['shangyueyushu'];
                }
                elseif($new["benyuejinhuo"] == 0||($new['shangyueyushu']+$new['benyuejinhuo'])>0){
                    $new['benyuexiaoshou'] = round(bcmul((bcadd($new['shangyueyushu'], $new['benyuejinhuo'], 2)), 0.7, 2));
                    $new['benyueyushu'] = bcsub(bcadd($new['shangyueyushu'], $new['benyuejinhuo'], 3), $new['benyuexiaoshou'], 2);
                }
                $new['shangyegonghuojia'] = $value['shangyegonghuojia'];
                $new['guige'] = $value['guige'];
                $new['daibiao'] = $value['daibiao'];
                $new['yewuyuan'] = $value['yewuyuan'];
                $new['pinming'] = $value['pinming'];
                $new['wanchengjine'] = bcmul($new['benyuexiaoshou'], $new['shangyegonghuojia'], 2);
                $new['wanchenglv'] = '';
                $new['jiangfa'] = '';
                $new['renwu'] = '';
                $new['shizhijine'] = '';
                if ($new['bumen'] == '直营' && empty($value['daibiao']) && !empty($value['yewuyuan'])) {
                    //是直营部门的业务员，任务按照产品分
                    $new['bumenjingli'] = $value['bumenjingli'];
                    $new['abbiaozhunshuihou'] = $value['abbiaozhunshuihou'];
                    $new['abjine'] = round(bcmul($new['abbiaozhunshuihou'], $new['benyuexiaoshou'], 3), 2);
                    $new['jinglijiangjinticheng'] = $value['jinglijiangjinticheng'];
                    $new['jinglijiangjin'] = round(bcmul($new['jinglijiangjinticheng'], $new['benyuexiaoshou'], 3), 2);
                    if($i+2>count($zhifu)) goto abcd;

                    if ($value['yewuyuan'] == $zhifu[$i + 1]['yewuyuan'] && $value['guige'] == $zhifu[$i + 1]['guige']) {
                        $lsje=$new['wanchengjine']+$lsje;
                        $lsabje += $new['abjine'];
                    } else {
                        abcd:
                        $linshijine=$lsje+$new['wanchengjine'];
                        $linshiabjine=$lsabje+$new['abjine'];
                        $new['renwu'] = db::name('renwu')->where('name', $new['yewuyuan'])->where('guige', $new['guige'])->field('renwu')->find()['renwu'];
                        if(!empty($new['renwu'])){
                            $new['wanchenglv'] = bcdiv($linshijine, $new['renwu'], 2);
                            $new['jiangfa'] = round(bcmul((bcsub($new['wanchengjine'], $new['renwu'], 3)), 0.01, 3), 2);
                        }
                        $lsje=0;
                        $value['shizhijine'] = round(bcadd($linshiabjine['abjine'], $new['jiangfa'], 3), 2);
                        $lsabje=0;
                    }
                } elseif (empty($value['yewuyuan']) && !empty($value['daibiao'])) {
                    //是预算部的代表，任务每人一个
                    $new['abbiaozhunshuihou'] = '';
                    $new['abjine'] = '';

                    $new['zhuguan'] = $value['zhuguan'];
                    $new['lunwenfei'] = $value['lunwenfei'];
                    $new['lunwenfeijine'] = round(bcmul($new['lunwenfei'], $new['benyuexiaoshou'], 3), 2);
                    $new['daibiaojiangjinticheng'] = $value['daibiaojiangjinticheng'];
                    $new['daibiaojiangjin'] = round(bcmul($new['daibiaojiangjinticheng'], $new['benyuexiaoshou'], 3), 2);
                    $new['zhuguanjiangjinticheng'] = $value['zhuguanjiangjinticheng'];
                    $new['zhuguanjiangjin'] = round(bcmul($new['zhuguanjiangjinticheng'], $new['benyuexiaoshou'], 3), 2);
                    if($i+2>count($zhifu)) goto abc;
                    if ($value['daibiao'] == $zhifu[$i + 1]['daibiao']) {
                        $new['wanchenglv'] = '';
                        $dbje+= $new['wanchengjine'];
                    } else {
                        abc:
                        $new['renwu'] = db::name('renwu')->where('name', $new['daibiao'])->field('renwu')->find()['renwu'];
                        $wanchengjine=$dbje+$new['wanchengjine'];
                        if(!empty($new['renwu'])){
                            $new['wanchenglv'] = bcdiv($wanchengjine, $new['renwu'], 2);
                        }
                        $dbje=0;
                        if ($new['wanchenglv'] < 0.8) {
                            $new['shizhijine'] = 0;
                        } elseif ($new['wanchenglv'] >= 1) {
                            $new['shizhijine'] = $new['daibiaojiangjin'];
                        } else {
                            $new['shizhijine'] = bcmul($new['daibiaojiangjin'], $new['wanchenglv'], 3);
                        }
                    }
                }
//                dump($new);exit;

                //保存到数据库

                $a[]=$new;
            }


        }

        Db::startTrans();
        try {
            db('zhifu')->where('yuefen',$timenow)->delete();
            $rel=(new Zf)->saveAll($a);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return json(['mes' => ($e->getMessage())]);
        }
        $elapsed = (microtime(true) - $start);

        if(!empty($rel)){
            return json(['code'=>200,'mes'=>'成功','time'=>$elapsed,'chanchu'=>count($a).'条']);
        }else{
            return json(['code'=>200,'mes'=>'失败']);
        }
    }

    public function zhifusearch(){
        $time=input('time');

        $zhuguan=input('zhuguan');
        $pinming=input('pinming');
        $guige=input('guige');
        $jingli=input('bumenjingli');
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
            return json(['code'=>100,'mes'=>'无结果','data'=>[]]);
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
  
    public function daoru(){

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
            $dataa['yuefen']=str_replace([' ','/','.'],['','-','-'],$v[0]);
            $dataa['diqu']=str_replace(' ','',$v[1]);
            $dataa['bumen']=str_replace(' ','',$v[2]);
            $dataa['bumenjingli']=str_replace(' ','',$v[3]);
            $dataa['zhuguan']=str_replace(' ','',$v[4]);
            $dataa['yewuyuan']=str_replace(' ','',$v[5]);
            $dataa['daibiao']=str_replace(' ','',$v[6]);
            $dataa['yiyuanjibie']=str_replace(' ','',$v[7]);
            $dataa['kehumingcheng1']=str_replace(' ','',$v[8]);
            $dataa['kehumingcheng2']=str_replace(' ','',$v[9]);
            $dataa['shangyegongsi']=str_replace(' ','',$v[10]);
            $dataa['pinming']=str_replace(' ','',$v[11]);
            $dataa['guige']=str_replace(' ','',$v[12]);
            $dataa['shangyueyushu']=str_replace(' ','',$v[13]);
            $dataa['benyuejinhuo']=str_replace(' ','',$v[14]);
            $dataa['benyuexiaoshou']=str_replace(' ','',$v[15]);
            $dataa['benyueyushu']=str_replace(' ','',$v[16]);
            $dataa['abbiaozhunshuihou']=str_replace(' ','',$v[17]);
            $dataa['abjine']=str_replace(' ','',$v[18]);
            $dataa['lunwenfei']=str_replace(' ','',$v[19]);
            $dataa['lunwenfeijine']=str_replace(' ','',$v[20]);
            $dataa['daibiaojiangjinticheng']=str_replace(' ','',$v[21]);
            $dataa['daibiaojiangjin']=str_replace(' ','',$v[22]);
            $dataa['zhuguanjiangjinticheng']=str_replace(' ','',$v[23]);
            $dataa['zhuguanjiangjin']=str_replace(' ','',$v[24]);
            $dataa['jinglijiangjinticheng']=str_replace(' ','',$v[25]);
            $dataa['jinglijiangjin']=str_replace(' ','',$v[26]);
            $dataa['shangyegonghuojia']=str_replace(' ','',$v[27]);
            $dataa['wanchengjine']=str_replace(' ','',$v[28]);
            $dataa['renwu']=str_replace(' ','',$v[29]);
            $dataa['wanchenglv']=str_replace(' ','',$v[30]);
            $dataa['jiangfa']=str_replace(' ','',$v[31]);
            $dataa['shizhijine']=str_replace(' ','',$v[32]);

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
    public function xiugaixingming(){
        $zhongduanmingcheng=input('kehumingcheng1');
        $leixing=input('leixing');
        $name=input('name');
        $upname=input('upname');
        $yuefen=input('yuefen');
        $data=Db::name('zhifu')->where('yuefen',$yuefen)->where('kehumingcheng1',$zhongduanmingcheng)->where($leixing,$name)->update([$leixing=>$upname]);
//        dump($data);exit;
        //dump($data);
        if($data){
            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'未修改']);

        }
    }
    public function zhifuedit(){
        $data['id']=input('id');
        $data['yuefen']=str_replace([' ','/','.'],['','-','-'],input('yuefen'));
        $data['diqu']=input('diqu');
        $data['bumen']=input('bumen');
        $data['bumenjingli']=input('bumenjingli');
        $data['yewuyuan']=input('yewuyuan');
        $data['daibiao']=input('daibiao');
        $data['zhuguan']=input('zhuguan');
        $data['yiyuanjibie']=input('yiyuanjibie');
        $data['kehumingcheng1']=input('kehumingcheng1');
        $data['kehumingcheng2']=input('kehumingcheng2');
        $data['shangyegongsi']=input('shangyegongsi');
        $data['pinming']=input('pinming');
        $data['guige']=input('guige');
        $data['shangyueyushu']=input('shangyueyushu');
        $data['benyuejinhuo']=input('benyuejinhuo');
        $data['benyuexiaoshou']=input('benyuexiaoshou');
        $data['benyueyushu']=input('benyueyushu');
        $data['abbiaozhunshuihou']=input('abbiaozhunshuihou');
        $data['abjine']=input('abjine');
        $data['shangyegonghuojia']=input('shangyegonghuojia');
        $data['wanchengjine']=input('wanchengjine');
        $data['renwu']=input('renwu');
        $data['wanchenglv']=input('wanchenglv');
        $data['jiangfa']=input('jiangfa');
        $data['shizhijine']=input('shizhijine');
        $data['zhuguanjiangjinticheng']=input('zhuguanjiangjinticheng');
        $data['zhuguanjiangjin']=input('zhuguanjiangjin');
        $data['jinglijiangjinticheng']=input('jinglijiangjinticheng');
        $data['jinglijiangjin']=input('jinglijiangjin');
        $data['lunwenfei']=input('lunwenfei');
        $data['lunwenfeijine']=input('lunwenfeijine');
        $data['daibiaojiangjinticheng']=input('daibiaojiangjinticheng');
        $data['daibiaojiangjin']=input('daibiaojiangjin');

        $res=db('zhifu')->update($data);

        if($res){
            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'未修改']);
        }

    }
    public function zhifuadd(){
        $data['yuefen']=str_replace([' ','/','.'],['','-','-'],input('yuefen'));
        $data['diqu']=input('diqu');
        $data['bumen']=input('bumen');
        $data['bumenjingli']=input('bumenjingli');
        $data['yewuyuan']=input('yewuyuan');
        $data['daibiao']=input('daibiao');
        $data['zhuguan']=input('zhuguan');
        $data['yiyuanjibie']=input('yiyuanjibie');
        $data['kehumingcheng1']=input('kehumingcheng1');
        $data['kehumingcheng2']=input('kehumingcheng2');
        $data['shangyegongsi']=input('shangyegongsi');
        $data['pinming']=input('pinming');
        $data['guige']=input('guige');
        $data['shangyueyushu']=input('shangyueyushu');
        $data['benyuejinhuo']=input('benyuejinhuo');
        $data['benyuexiaoshou']=input('benyuexiaoshou');
        $data['benyueyushu']=input('benyueyushu');
        $data['abbiaozhunshuihou']=input('abbiaozhunshuihou');
        $data['abjine']=input('abjine');
        $data['shangyegonghuojia']=input('shangyegonghuojia');
        $data['wanchengjine']=input('wanchengjine');
        $data['renwu']=input('renwu');
        $data['wanchenglv']=input('wanchenglv');
        $data['jiangfa']=input('jiangfa');
        $data['shizhijine']=input('shizhijine');
        $data['zhuguanjiangjinticheng']=input('zhuguanjiangjinticheng');
        $data['zhuguanjiangjin']=input('zhuguanjiangjin');
        $data['jinglijiangjinticheng']=input('jinglijiangjinticheng');
        $data['jinglijiangjin']=input('jinglijiangjin');
        $data['lunwenfei']=input('lunwenfei');
        $data['lunwenfeijine']=input('lunwenfeijine');
        $data['daibiaojiangjinticheng']=input('daibiaojiangjinticheng');
        $data['daibiaojiangjin']=input('daibiaojiangjin');

        $res=db('zhifu')->insert($data);

        if($res){
            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'未添加']);
        }

    }
    public function daorushanchu(){
        $total=(int)input('total');

        if(!is_int($total)){
            return json(['code' => 0, 'mes' => '没有导入']);
        }
        if($total<=1) {
            return json(['code' => 0, 'mes' => '没有导入']);
        }
        $count=db('zhifu')->order('id','desc')->limit($total)->field('id')->select();
        $countt=array_column($count,'id');

        $delete=db('out')->delete($countt);
        if($delete){
            return json(['code'=>200,'message'=>'成功,删除了'.$delete.'条']);
        }else{
            return json(['code'=>0,'message'=>'失败']);
        }
    }
    public function zhifudel(){
        $del=input('id');
        if(is_array($del)){
            foreach ($del as $dela){
                $rel=db('zhifu')->where('id',$dela)->delete();
            }
        }else $rel=db('zhifu')->where('id',$del)->delete();

        if(($rel>=1)){

            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'失败']);
        }

    }
}
