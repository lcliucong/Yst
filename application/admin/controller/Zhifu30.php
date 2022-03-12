<?php
namespace app\admin\controller;
use think\Db;
use PHPExcel;
use think\facade\Request;
use app\mainmenu\controller\Common;
use think\Collection;


class Zhifu30 extends Common{
    public function zhifuadd(){
        $timenow=input('time');
        $time=input('time');
        $time=explode('-',$time);
        $ces=array_pop($time);
        $time=$time['0'];

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

        $xinxibeian = db('out')->field('zhongduanmingcheng,yiyuanjibie,yewuyuan,diqu,pinming,guige,bumen,yapiabbiaozhun,zhifufangfa,zhongduanmingcheng2')
            //->where('zhifufangfa',2)
            ->select();
        $xinxibeian2 = db('out')->field('zhongduanmingcheng,pinming,guige,zhongduanmingcheng2,zhifufangfa')->select();

        $data=db('flowofmed')->field('innums,med_salenum,facname,med_name,med_specs,customer_name,customer_nameb,in_time,med_price,buss_origin,buss_name,med_batchnum,med_unit')->where('in_time','like',$timenow.'%')->select();
        $i=0;

        $result=array();
        $nomate=array();
        //dump($timenow);exit;
//dump(db('flowofmed')->where('in_time','like',$timenow.'%')->select());
//dump($xinxibeian);exit;
        foreach($data as $j =>$value){

            $zhifu['med_name'] = $value['med_name'];
            $zhifu['med_specs'] = $value['med_specs'];
            $zhifu['customer_name'] = $value['customer_name'];
            $zhifu['customer_nameb'] = $value['customer_nameb'];

            foreach ($xinxibeian as $k => $v) {

                    if (
                        $zhifu['med_name']== $v['pinming'] &&
                        $zhifu['med_specs']==$v['guige'] &&
                        $zhifu['customer_name']==$v['zhongduanmingcheng'] &&
                        $zhifu['customer_nameb']==$v['zhongduanmingcheng2']
                    ) {
                        $shangyuejinhuo=db('zhifu30')->where(function($query)use($zhifu){
                            $query->where('kehumingcheng1',$zhifu['customer_name'])->whereor('kehumingcheng2',$zhifu['customer_nameb']);
                        })
                            ->where('pinming',$zhifu['med_name'])->where('guige',$zhifu['med_specs'])
                            ->where('shangyegongsi',$value['facname'])
                            ->where('yuefen',$timeup)
                            ->sum('benyuekucun');
                        if(empty($shangyueyushu)){
                            $shangyueyushu=0;
                        }

                        $result[$i]['yuefen']=$timenow;
                        $result[$i]['diqu']=$v['diqu'];
                        $result[$i]['bumen']=$v['bumen'];
                        $result[$i]['yewuyuan']=$v['yewuyuan'];
                        $result[$i]['kehumingcheng1']=$value['customer_name'];
                        $result[$i]['kehumingcheng2']=$value['customer_nameb'];
                        //$result[$i]['med_salenum']=$value['med_salenum'];
                        $result[$i]['yiyuanjibie']=$v['yiyuanjibie'];
                        $result[$i]['shangyegongsi']=$value['facname'];
                        $result[$i]['pinming']=$v['pinming'];
                        $result[$i]['guige']=$v['guige'];
                        $result[$i]['shangyuejinhuo']=$shangyuejinhuo;
                        $result[$i]['benyuejinhuo']=$value['med_salenum'];
                        $result[$i]['shangyuexiaoshou']=$result[$i]['shangyuejinhuo'];
                        $result[$i]['benyuekucun']=$result[$i]['benyuejinhuo'];
                        $result[$i]['yapiabbiaozhun']=$v['yapiabbiaozhun'];
                        $result[$i]['abjine']='';
                        $result[$i]['zhifufangfa']=$v['zhifufangfa'];
                        if(empty($result[$i]['kehumingcheng1'])||
                            empty($result[$i]['pinming'])||empty($result[$i]['guige'])||empty($result[$i]['shangyegongsi'])){
                            return json(['code'=>0,'message'=>'数据为空或格式不对']);
                        }
                        break;

                    }

            }


            if(empty($result[$i])){

                $data[$j]['message']='信息备案不存在（相等两条及以下视为不存在）';
                foreach ($xinxibeian2 as $xinxi2) {
                    if($zhifu['med_specs']==$xinxi2['guige']&&$zhifu['customer_name']==$xinxi2['zhongduanmingcheng']&&$zhifu['customer_nameb']==$xinxi2['zhongduanmingcheng2']
                    ) {
                        $data[$j]['message'] = '品名有误（存在规格，终端名称，终端别名一致）';break;
                    } elseif ($zhifu['med_name']==$xinxi2['pinming']&&$zhifu['med_specs']==$xinxi2['guige']&&$zhifu['customer_nameb']==$xinxi2['zhongduanmingcheng2']) {
                        $data[$j]['message'] = '终端名称有误（存在品名，规格，终端别名一致）';break;
                    } elseif ($zhifu['med_name']==$xinxi2['pinming']&&$zhifu['customer_name']==$xinxi2['zhongduanmingcheng']&&$zhifu['customer_nameb']==$xinxi2['zhongduanmingcheng2']) {
                        $data[$j]['message'] = '规格有误（存在品名，终端名称，终端别名一致）';break;
                    } elseif ($zhifu['med_name']==$xinxi2['pinming']&&$zhifu['med_specs']==$xinxi2['guige']&&$zhifu['customer_name']==$xinxi2['zhongduanmingcheng']) {
                        $data[$j]['message'] = '终端别名有误（存在品名，规格，终端名称一致）';break;
                    }

                }
                $nomate[] = $data[$j];
            }$i++;

        }
        $result2=array_filter($result,function($result){
                return $result['zhifufangfa']==2;
        });


        if(!empty($result2)){        $result2=array_values($result2);}
        if(!empty($nomate)){        $nomate=array_values($nomate);}

 //       dump($result);
//        dump($nomate);

        if($result2||$nomate){
            return json(['code'=>200,'mes'=>'成功','pipei'=>$result2,'weipipei'=>$nomate]);
        }else{
            return json(['code'=>0,'mes'=>'不存在匹配结果','pipei'=>[],'weipipei'=>[]]);
        }

    }
    public function zhifubaocun()
    {
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

        $yaoming=Db::name('zhifu30')->where('yuefen',$timeup)->distinct(true)->field('pinming')->select();
        if(empty($yaoming)) return '没有上月份数据，无法计算当月';
        foreach($yaoming as $ym) {
            $i=-1;
            $data = Db::name('flowofmed')->field('med_name,med_specs,customer_name,customer_nameb,med_price,sum(med_salenum) as med_salenum')
                ->where('med_name', $ym['pinming'])
                ->where('in_time', 'between time', [$timenow . '-01', $timenow . '-' . $time2])
                ->group('med_name,med_specs,customer_name,customer_nameb')
                ->order('med_name')
                ->select();
            $zhifu = Db::name('zhifu30')->field('diqu,bumen,bumenjingli,zhuguan,yewuyuan,daibiao,yiyuanjibie,shangyegongsi,
            benyueyushu,abbiaozhunshuihou,lunwenfei,zhuguanjiangjinticheng,daibiaojiangjinticheng,kehumingcheng1,pinming,
            guige,kehumingcheng2,jinglijiangjinticheng,shangyegonghuojia')
                ->where('pinming', $ym['pinming'])
                ->where('yuefen', $timeup)
                ->order('pinming')
                ->select();

            foreach ($zhifu as $value) {
                $i++;
                $new["benyuejinhuo"] = 0;
                $new['shangyueyushu'] = $value['benyueyushu'];
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
                }else{
                    foreach ($data as $k=> $liushui) {
                        if ($value['guige'] == $liushui['med_specs'] &&
                            $value['kehumingcheng1'] == $liushui['customer_name'] &&
                            $value['kehumingcheng2'] == $liushui['customer_nameb']
                        ) {
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


        $count=Db::name('zhifu')->where('yuefen',$timenow)
            ->count();
        $currentPage=input('currentPage');
        $pagenum=input('pageCount');
        $row=ceil($count/$pagenum);
        $zhifujieguo=Db::name('zhifu')->where('yuefen',$timenow)->limit($currentPage*$pagenum-$pagenum,$pagenum)->order('yuefen')->select();
        if(!empty($rel)){
            return json(['code'=>200,'mes'=>'成功','jieguo'=>$zhifujieguo,'row'=>$row*10,'count'=>$count,'time'=>$elapsed,'chanchu'=>count($a).'条']);
        }else{
            return json(['code'=>200,'mes'=>'失败','jieguo'=>$zhifujieguo,'row'=>$row*10,'count'=>$count]);
        }

    }
    public function zhifusearch(){
        $time=input('time');

        $yewuyuan=input('yewuyuan');
        $pinming=input('pinming');
        $guige=input('guige');


        $where=[];

        if(!empty($yewuyuan)){
            $yewuyuan=['yewuyuan','=',$yewuyuan];
            array_push($where,$yewuyuan);
        }if(!empty($pinming)){
            $pinming=['pinming','=',$pinming];
            array_push($where,$pinming);
        }if(!empty($guige)){
            $guige=['guige','=',$guige];
            array_push($where,$guige);
        }
        $count=db('zhifu30')->where('yuefen',$time)->when(!empty($where),function ($query)use($where){
            $query->where([$where]);
        })->count();
        $currentPage=input('currentPage');  //当前几页
        $pagenum=input('pageCount');  //每页几条
        $row=ceil($count/$pagenum);
        $data=db('zhifu30')->where('yuefen',$time)->order('yuefen')->when(!empty($where),function ($query)use($where){
            $query->where([$where]);
        })->limit($currentPage*$pagenum-$pagenum,$pagenum)->select();
            if(!empty($yewuyuan)&&!empty($count)){
                $data2=db('zhifu30')->where('yuefen',$time)->order('yuefen')->when(!empty($where),function ($query)use($where){
                    $query->where([$where]);
                })->field('sum(abjine) as abjine')->select();
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
        $time=input('time');
        $time=explode('-',$time);
        $ces=array_pop($time);
        if($ces>1){
            $shangyue=$ces-1;
        }elseif($ces==01){
            $shangyue=12;
        }
        if($ces<9){
            $ces=str_replace('0','',$ces);
        }

        $data=db('zhifu30')->where('yuefen',$yuefen)->select();
        if(empty($data)){
            return json(['code'=>0,'mes'=>'没数据']);
        }
        $a=date('Y-m-d H:i');

        $PHPExcel = new \PHPExcel(); //实例化类
        //创建sheet
        // $PHPExcel->createSheet();
        //获取sheet
        $PHPSheet=$PHPExcel->setActiveSheetIndex(0);//获取文件薄
        $PHPSheet->setTitle('支付表压30天');//获取栏目的sheet修改名字
        $PHPSheet->setCellValue('A1','月份')->setCellValue('B1','部门')->setCellValue('D1','业务员')->setCellValue('E1','地区')->setCellValue('F1','客户名称A')
            ->setCellValue('G1','客户名称B')->setCellValue('H1','医院级别')->setCellValue('I1','商业公司')->setCellValue('J1','品名')->setCellValue('K1','规格')->setCellValue('L2',$shangyue.'月进货(流向数)')
            ->setCellValue('L1','上月库存')->setCellValue('N1','本月销售')->setCellValue('O1','本月库存')->setCellValue('C1','部门经理')
            ->setCellValue('M1',$ces.'月进货')->setCellValue('N2',$shangyue.'月销售')->setCellValue('O2',$ces.'月库存')->setCellValue('P2','ab标准')->setCellValue('Q2','ab金额')->setCellValue('R2','备注');
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

        $PHPSheet->getColumnDimension('A')->setWidth(10);
        $PHPSheet->getColumnDimension('B')->setWidth(10);
        $PHPSheet->getColumnDimension('C')->setWidth(15);
        $PHPSheet->getColumnDimension('D')->setWidth(12);
        $PHPSheet->getColumnDimension('E')->setWidth(30);
        $PHPSheet->getColumnDimension('F')->setWidth(30);
        $PHPSheet->getColumnDimension('G')->setWidth(15);
        $PHPSheet->getColumnDimension('H')->setWidth(30);
        $PHPSheet->getColumnDimension('I')->setWidth(15);
        $PHPSheet->getColumnDimension('J')->setWidth(15);
        $PHPSheet->getColumnDimension('K')->setWidth(15);
        $PHPSheet->getColumnDimension('L')->setWidth(22);
        $PHPSheet->getColumnDimension('M')->setWidth(20);
        $PHPSheet->getColumnDimension('N')->setWidth(20);
        $PHPSheet->getColumnDimension('O')->setWidth(12);
        $PHPSheet->getColumnDimension('P')->setWidth(12);
        $PHPSheet->getColumnDimension('Q')->setWidth(15);
        $PHPSheet->getColumnDimension('R')->setWidth(15);

        foreach($data as $key => $value){
            $PHPSheet->setCellValue('A'.($key+3),$value['yuefen'])->setCellValue('B'.($key+3),$value['bumen'])->setCellValue('D'.($key+3),$value['yewuyuan'])
                ->setCellValue('E'.($key+3),$value['diqu'])->setCellValue('F'.($key+3),$value['kehumingcheng1'])->setCellValue('G'.($key+3),$value['kehumingcheng1'])
                ->setCellValue('H'.($key+3),$value['yiyuanjibie'])->setCellValue('I'.($key+3),$value['shangyegongsi'])->setCellValue('J'.($key+3),$value['pinming'])
                ->setCellValue('K'.($key+3),$value['guige'])->setCellValue('L'.($key+3),$value['shangyuejinhuo'])->setCellValue('M'.($key+3),$value['benyuejinhuo'])
                ->setCellValue('N'.($key+3),$value['shangyuexiaoshou'])->setCellValue('O'.($key+3),$value['benyuekucun'])->setCellValue('P'.($key+3),$value['yapiabbiaozhun'])
                ->setCellValue('Q'.($key+3),$value['abjine'])->setCellValue('R'.($key+3),$value['beizhu'])->setCellValue('C'.($key+3),$value['bumenjingli']);
        }
        $PHPSheet->mergeCells('A1:A2');
        $PHPSheet->mergeCells('B1:B2');
        $PHPSheet->mergeCells('C1:C2');
        $PHPSheet->mergeCells('D1:D2');
        $PHPSheet->mergeCells('E1:E2');
        $PHPSheet->mergeCells('F1:F2');
        $PHPSheet->mergeCells('G1:G2');
        $PHPSheet->mergeCells('H1:H2');
        $PHPSheet->mergeCells('I1:I2');
        $PHPSheet->mergeCells('J1:J2');
        $PHPSheet->mergeCells('J1:J2');
        $PHPSheet->mergeCells('K1:K2');
        $PHPSheet->mergeCells('M1:M2');
        $PHPSheet->getStyle('A1:Q1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPSheet->getStyle('K2:O2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPSheet->getStyle('A1:Q1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $PHPSheet->getStyle('K2:O2')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    //'style' => PHPExcel_Style_Border::BORDER_THICK,//边框是粗的
                    'style' => \PHPExcel_Style_Border::BORDER_THIN,//细边框
                    //'color' => array('argb' => 'FFFF0000'),
                ),
            ),
        );
        $PHPSheet->getStyle('A3:R'.($key+3))->applyFromArray($styleArray);
        // var_dump($PHPWriter);die;
        $PHPWriter=\PHPExcel_IOFactory::createWriter($PHPExcel,'Excel2007');

        ob_end_clean();// 就是加这句
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
        header('Content-Disposition: attachment;filename="压批支付30天.xlsx"');

        header('Cache-Control:max-age=0');
        $PHPWriter->save('php://output');
    }
    public function zhifudaoru(){

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
        unset($sheetContent[1]);
        unset($sheetContent[2]);
        if(empty($sheetContent)){
            return json(['message'=>'空数据']);
        }
        foreach ($sheetContent as $k => $v){
            if(empty(array_filter($v))) {
                continue;
            }
            $dataa['yuefen']=str_replace([' ','/'],['','-'],$v[0]);
            $dataa['bumen']=str_replace(' ','',$v[1]);
            $dataa['bumenjingli']=str_replace(' ','',$v[2]);
            $dataa['yewuyuan']=str_replace(' ','',$v[3]);
            $dataa['diqu']=str_replace(' ','',$v[4]);
            $dataa['kehumingcheng1']=str_replace(' ','',$v[5]);
            $dataa['yiyuanjibie']=str_replace(' ','',$v[6]);
            $dataa['shangyegongsi']=str_replace(' ','',$v[7]);
            $dataa['pinming']=str_replace(' ','',$v[8]);
            $dataa['guige']=str_replace(' ','',$v[9]);
            $dataa['shangyuejinhuo']=str_replace(' ','',$v[10]);
            $dataa['benyuejinhuo']=str_replace(' ','',$v[11]);
            $dataa['shangyuexiaoshou']=str_replace(' ','',$v[12]);
            $dataa['benyuekucun']=str_replace(' ','',$v[13]);
            $dataa['yapiabbiaozhun']=str_replace(' ','',$v[14]);
            $dataa['abjine']=str_replace(' ','',$v[15]);
            $dataa['beizhu']=str_replace(' ','',$v[16]);

            $res[] = $dataa;

        }
        $total=count($res);

        $rel=db('zhifu30')->limit(100)->insertall($res);

        if($rel > 0){

            return json(['code'=>200,'message'=>'成功','total'=>$total]);
        }else{
            return json(['code'=>0,'message'=>'失败','total'=>$total]);

        }
    }


}
