<?php
namespace app\admin\controller;
use think\Db;
use PHPExcel;
use think\facade\Request;
use app\mainmenu\controller\Common;
use think\Collection;


class Zhifu60 extends Common{
    
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
            //->where('zhifufangfa',3)
            ->select();
        $xinxibeian2 = db('out')->field('zhongduanmingcheng,pinming,guige,zhongduanmingcheng2,zhifufangfa')->select();

        $data=db('flowofmed')->field('innums,med_salenum,facname,med_name,med_specs,customer_name,customer_nameb,in_time,med_price,buss_origin,buss_name,med_batchnum,med_unit')->where('in_time','like',$timenow.'%')->select();
        $i=0;

        $result=array();
        $nomate=array();
//dump($data);
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

                    $shangshangyuejinhuo=db('zhifu60')->where(function($query)use($zhifu){
                        $query->where('kehumingcheng1',$zhifu['customer_name'])->whereor('kehumingcheng2',$zhifu['customer_nameb']);
                    })
                        ->where('pinming',$zhifu['med_name'])
                        ->where('guige',$zhifu['med_specs'])
                        ->where('yuefen',$timeup)
                        ->where('shangyegongsi',$value['facname'])
                        ->sum('shangyuekucun');
                    $shangyuejinhuo=db('zhifu60')->where(function($query)use($zhifu){
                        $query->where('kehumingcheng1',$zhifu['customer_name'])->whereor('kehumingcheng2',$zhifu['customer_nameb']);
                    })
                        ->where('pinming',$zhifu['med_name'])
                        ->where('guige',$zhifu['med_specs'])
                        ->where('yuefen',$timeup)
                        ->where('shangyegongsi',$value['facname'])
                        ->sum('benyuekucun');
                    if(empty($shangyuejinhuo)){
                        $shangyuejinhuo=0;
                    }
                    if(empty($shangshangyuejinhuo)){
                        $shangshangyuejinhuo=0;
                    }
//                    dump($shangyuejinhuo);
//                    dump($shangshangyuejinhuo);exit;
//dump($timeup);exit;
                    $result[$i]['yuefen']=$timenow;
                    $result[$i]['diqu']=$v['diqu'];
                    $result[$i]['bumen']=$v['bumen'];
                    $result[$i]['yewuyuan']=$v['yewuyuan'];
                    $result[$i]['kehumingcheng1']=$value['customer_name'];
                    $result[$i]['kehumingcheng2']=$value['customer_nameb'];
                    $result[$i]['yiyuanjibie']=$v['yiyuanjibie'];
                    $result[$i]['shangyegongsi']=$value['facname'];
                    $result[$i]['pinming']=$v['pinming'];
                    $result[$i]['guige']=$v['guige'];
                    $result[$i]['shangshangyuejinhuo']=$shangshangyuejinhuo;
                    $result[$i]['shangyuejinhuo']=$shangyuejinhuo;
                    $result[$i]['benyuejinhuo']=$value['med_salenum'];
                    $result[$i]['shangshangyuexiaoshou']=$result[$i]['shangshangyuejinhuo'];
                    $result[$i]['shangyuekucun']=$result[$i]['shangyuejinhuo'];
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
                return $result['zhifufangfa']==3;
        });
//        dump($result2);
//        dump($nomate);
//exit;
        if(!empty($result2)){        $result2=array_values($result2);}
        if(!empty($nomate)){        $nomate=array_values($nomate);}

        if($result2||$nomate){
            return json(['code'=>200,'mes'=>'成功','pipei'=>$result2,'weipipei'=>$nomate]);
        }else{
            return json(['code'=>0,'mes'=>'不存在匹配结果','pipei'=>[],'weipipei'=>[]]);
        }

    }
    public function zhifubaocun(){

        $data=$this->zhifuadd();
        //getContent将文件读取，由json_decode转换成数组
        $data=  json_decode($data->getContent(),true);

        //$weipipei=$data['weipipei'];

//        if(!empty($weipipei)){
//            return json(['code'=>0,'mes'=>'存在未匹配流水，修改后才能保存']);
//        }
        $data=$data['pipei'];

        $extends=array_filter($data);

        if(!$extends){
            return json(['code'=>0,'mes'=>'当前没有匹配数据']);
        }
        $fasum=0;
        $maisum=0;
//dump($data);exit;//dump($weipipei);

        foreach ($data as $key => $row) {
            $aa[$key]=$row['yuefen'];
            $bb[$key]=$row['diqu'];
            $cc[$key]=$row['yewuyuan'];
            $ff[$key]=$row['pinming'];
            $gg[$key]=$row['guige'];
            $ii[$key]=$row['shangyegongsi'];
            $jj[$key]=$row['kehumingcheng2'];
            $kk[$key]=$row['kehumingcheng1'];
        }
        array_multisort($aa, $bb,$cc,$ff,$gg,$ii,$kk,$data);


        $linshi['yuefen']='';
        $linshi['diqu']='';
        $linshi['yewuyuan']='';
        $linshi['kehumingcheng1']='';
        $linshi['kehumingcheng2']='';
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
                $linshi['kehumingcheng1']== $v['kehumingcheng1'] &&
                $linshi['kehumingcheng2']== $v['kehumingcheng2']&&
                $linshi['shangyegongsi']== $v['shangyegongsi']&&
                $linshi['pinming']==$v['pinming']&&
                $linshi['guige']== $v['guige']&&
                $linshi['yiyuanjibie']== $v['yiyuanjibie']
            ){
                $fahuo[$a]['benyuejinhuo']+=$v['benyuejinhuo'];
                $fahuo[$a]['benyuekucun']=$fahuo[$a]['benyuejinhuo'];
            }else{
                $linshi['yuefen'] = $v['yuefen'];
                $linshi['diqu'] = $v['diqu'];
                $linshi['yewuyuan'] = $v['yewuyuan'];
                $linshi['kehumingcheng1'] = $v['kehumingcheng1'];
                $linshi['kehumingcheng2'] = $v['kehumingcheng2'];
                $linshi['yiyuanjibie'] = $v['yiyuanjibie'];
                $linshi['shangyegongsi'] = $v['shangyegongsi'];
                $linshi['pinming'] = $v['pinming'];
                $linshi['guige'] = $v['guige'];
                $a++;

                $fahuo[$a]=$v;
                $fahuo[$a]['abjine']=round(bcmul($fahuo[$a]['shangshangyuexiaoshou'],$fahuo[$a]['yapiabbiaozhun'],10),2);

            }
        }

//dump($fahuo);exit;
        //判断是否存在，存在重新计算本月支付
        //$res=array(array());
        $res=(int)'';
        $ress=(int)'';
        for($h=0;$h<count($fahuo);$h++) {

            $cunzai = db('zhifu60')->where('yuefen', $fahuo[$h]['yuefen'])
                ->where('guige', $fahuo[$h]['guige'])
                ->where('pinming', $fahuo[$h]['pinming'])
                ->where('shangyegongsi', $fahuo[$h]['shangyegongsi'])
                ->where(function($query)use($fahuo,$h){
                    $query->whereor('kehumingcheng1',$fahuo[$h]['kehumingcheng1'])->whereor('kehumingcheng2',$fahuo[$h]['kehumingcheng2']);
                })
                ->where('yewuyuan','=',$fahuo[$h]['yewuyuan'])
                ->field('id')
                ->find();


            if ($cunzai['id']!=null) {
                $res=db('zhifu60')->where('id', $cunzai['id'])->update([
                    'shangshangyuejinhuo'=>$fahuo[$h]['shangshangyuejinhuo'],
                    'shangyuejinhuo'=>$fahuo[$h]['shangyuejinhuo'],
                    'benyuejinhuo' => $fahuo[$h]['benyuejinhuo'],
                    'benyuekucun' => $fahuo[$h]['benyuekucun'],
                    'shangyuekucun' => $fahuo[$h]['shangyuekucun'],
                    'shangshangyuexiaoshou' => $fahuo[$h]['shangshangyuexiaoshou'],
                    'abjine' => $fahuo[$h]['abjine'],
                    'diqu'=>$fahuo[$h]['diqu'],
                    'bumen'=>$fahuo[$h]['bumen'],
                    'yewuyuan'=>$fahuo[$h]['yewuyuan'],
                    'kehumingcheng1'=>$fahuo[$h]['kehumingcheng1'],
                    'kehumingcheng2'=>$fahuo[$h]['kehumingcheng2'],
                    'yiyuanjibie'=>$fahuo[$h]['yiyuanjibie'],
                    'shangyegongsi'=>$fahuo[$h]['shangyegongsi'],
                    'pinming'=>$fahuo[$h]['pinming'],
                    'guige'=>$fahuo[$h]['guige'],
                    'yapiabbiaozhun'=>$fahuo[$h]['yapiabbiaozhun'],
                    'yuefen'=>$fahuo[$h]['yuefen'],
                ]);

            } else {
                $ress = db('zhifu60')->strict(false)->insert($fahuo[$h]);
            }

        }

        $timenow=input('time');
        $count=db('zhifu60')->where('yuefen',$timenow)
            ->count();

        $currentPage=input('currentPage');
        $pagenum=input('pageCount');
        $row=ceil($count/$pagenum);

        $zhifujieguo=db('zhifu60')->where('yuefen',$timenow)->limit($currentPage*$pagenum-$pagenum,$pagenum)->select();
        //dump($res);
//        if(is_array($res)){
//            $res=array_filter($res);
//        }


        //dump($mm);
        if($res||$ress){

            return json(['code'=>200,'mes'=>'成功','jieguo'=>$zhifujieguo,'row'=>$row*10,'count'=>$count]);
        }else{
            return json(['code'=>175,'mes'=>'未修改','jieguo'=>$zhifujieguo,'row'=>$row*10,'count'=>$count]);
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
        $count=db('zhifu60')->where('yuefen',$time)->order('yuefen')->when(!empty($where),function ($query)use($where){
            $query->where([$where]);
        })->count();
        $currentPage=input('currentPage');  //当前几页
        $pagenum=input('pageCount');  //每页几条
        $row=ceil($count/$pagenum);
        $data=db('zhifu60')->where('yuefen',$time)->order('yuefen')->when(!empty($where),function ($query)use($where){
            $query->where([$where]);
        })->limit($currentPage*$pagenum-$pagenum,$pagenum)->select();

            if(!empty($yewuyuan)&&!empty($count)){
                $data2=db('zhifu60')->where('yuefen',$time)->order('yuefen')->when(!empty($where),function ($query)use($where){
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

        $timenow=Request::get('time');
        $time=input('time');
        $time=explode('-',$time);
        $ces=array_pop($time);
        if($ces>2){
            $shangshangyue=$ces-2;
            $shangyue=$ces-1;
        }elseif($ces==02){
            $shangshangyue=12;
            $shangyue=1;
        }elseif($ces==01){
            $shangyue=12;
            $shangshangyue=11;
        }
        if($ces<9){
            $ces=str_replace('0','',$ces);
        }
        $data=db('zhifu60')->where('yuefen',$timenow)->select();
        if(empty($data)){
            return json(['code'=>0,'mes'=>'没数据']);
        }
        $a=date('Y-m-d H:i');
//        dump($shangshangyue);
//        dump($ces);exit;
        $PHPExcel = new \PHPExcel(); //实例化类
        //创建sheet
        // $PHPExcel->createSheet();
        //获取sheet
        $PHPSheet=$PHPExcel->setActiveSheetIndex(0);//获取文件薄
        $PHPSheet->setTitle('支付表压60天');//获取栏目的sheet修改名字

        $PHPSheet->setCellValue('A1','月份')->setCellValue('B1','部门')->setCellValue('D1','业务员')->setCellValue('E1','地区')->setCellValue('F1','客户名称A')
            ->setCellValue('G1','客户名称B')->setCellValue('H1','医院级别')->setCellValue('I1','商业公司')->setCellValue('J1','品名')->setCellValue('K1','规格')
            ->setCellValue('L1','上月库存')->setCellValue('L2',$shangshangyue.'月进货')->setCellValue('M2',$shangyue.'月进货')->setCellValue('N1',$ces.'月进货')
            ->setCellValue('O2',$shangshangyue.'月销售') ->setCellValue('O1','本月销售')->setCellValue('P1','本月库存')->setCellValue('C1','部门经理')
            ->setCellValue('P2',$shangyue.'月库存')->setCellValue('Q2',$ces.'月库存')->setCellValue('R2','ab标准')->setCellValue('S2','ab金额')->setCellValue('T2','备注');

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

        $PHPSheet->getColumnDimension('A')->setWidth(10);
        $PHPSheet->getColumnDimension('B')->setWidth(9);
        $PHPSheet->getColumnDimension('C')->setWidth(15);
        $PHPSheet->getColumnDimension('D')->setWidth(15);
        $PHPSheet->getColumnDimension('E')->setWidth(12);
        $PHPSheet->getColumnDimension('F')->setWidth(30);
        $PHPSheet->getColumnDimension('G')->setWidth(30);
        $PHPSheet->getColumnDimension('H')->setWidth(15);
        $PHPSheet->getColumnDimension('I')->setWidth(30);
        $PHPSheet->getColumnDimension('J')->setWidth(15);
        $PHPSheet->getColumnDimension('K')->setWidth(15);
        $PHPSheet->getColumnDimension('L')->setWidth(12);
        $PHPSheet->getColumnDimension('M')->setWidth(12);
        $PHPSheet->getColumnDimension('N')->setWidth(12);
        $PHPSheet->getColumnDimension('O')->setWidth(15);
        $PHPSheet->getColumnDimension('P')->setWidth(12);
        $PHPSheet->getColumnDimension('Q')->setWidth(12);
        $PHPSheet->getColumnDimension('R')->setWidth(10);
        $PHPSheet->getColumnDimension('S')->setWidth(10);
        $PHPSheet->getColumnDimension('T')->setWidth(15);

        foreach($data as $key => $value){
            $PHPSheet->setCellValue('A'.($key+3),$value['yuefen'])->setCellValue('B'.($key+3),$value['bumen'])->setCellValue('D'.($key+3),$value['yewuyuan'])
                ->setCellValue('E'.($key+3),$value['diqu'])->setCellValue('F'.($key+3),$value['kehumingcheng1'])->setCellValue('G'.($key+3),$value['kehumingcheng2'])
                ->setCellValue('H'.($key+3),$value['yiyuanjibie'])->setCellValue('I'.($key+3),$value['shangyegongsi'])->setCellValue('J'.($key+3),$value['pinming'])
                ->setCellValue('K'.($key+3),$value['guige'])->setCellValue('L'.($key+3),$value['shangshangyuejinhuo'])->setCellValue('M'.($key+3),$value['shangyuejinhuo'])
                ->setCellValue('N'.($key+3),$value['benyuejinhuo'])->setCellValue('O'.($key+3),$value['shangshangyuexiaoshou'])->setCellValue('P'.($key+3),$value['shangyuekucun'])
                ->setCellValue('Q'.($key+3),$value['benyuekucun'])->setCellValue('R'.($key+3),$value['yapiabbiaozhun'])->setCellValue('S'.($key+3),$value['abjine'])
                ->setCellValue('T'.($key+3),$value['beizhu'])->setCellValue('C'.($key+3),$value['bumenjingli']);

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
        $PHPSheet->mergeCells('L1:M1');
        $PHPSheet->mergeCells('P1:Q1');
        $PHPSheet->mergeCells('K1:K2');
        $PHPSheet->mergeCells('N1:N2');
        $PHPSheet->getStyle('A1:Q1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPSheet->getStyle('A1:Q1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $PHPSheet->getStyle('L2:Q2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPSheet->getStyle('L2:Q2')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    //'style' => PHPExcel_Style_Border::BORDER_THICK,//边框是粗的
                    'style' => \PHPExcel_Style_Border::BORDER_THIN,//细边框
                    //'color' => array('argb' => 'FFFF0000'),
                ),
            ),
        );
        $PHPSheet->getStyle('A3:S'.($key+3))->applyFromArray($styleArray);
        // var_dump($PHPWriter);die;
        $PHPWriter=\PHPExcel_IOFactory::createWriter($PHPExcel,'Excel2007');

        ob_end_clean();// 就是加这句
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
        header('Content-Disposition: attachment;filename="压批支付60天.xlsx"');

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
            $dataa['shangshangyuejinhuo']=str_replace(' ','',$v[10]);
            $dataa['shangyuejinhuo']=str_replace(' ','',$v[11]);
            $dataa['benyuejinhuo']=str_replace(' ','',$v[12]);
            $dataa['shangshangyuexiaoshou']=str_replace(' ','',$v[13]);
            $dataa['shangyuekucun']=str_replace(' ','',$v[14]);
            $dataa['benyuekucun']=str_replace(' ','',$v[15]);
            $dataa['yapiabbiaozhun']=str_replace(' ','',$v[16]);
            $dataa['abjine']=str_replace(' ','',$v[17]);
            $dataa['beizhu']=str_replace(' ','',$v[18]);
            $res[] = $dataa;

        }
        $total=count($res);

        $rel=db('zhifu60')->limit(100)->insertall($res);

        if($rel > 0){

            return json(['code'=>200,'message'=>'成功','total'=>$total]);
        }else{
            return json(['code'=>0,'message'=>'失败','total'=>$total]);

        }
    }



}
