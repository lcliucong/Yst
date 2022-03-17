<?php
namespace app\admin\controller;
use think\Db;
use PHPExcel;
use think\facade\Request;
use app\mainmenu\controller\Common;
use think\Collection;


class Zhifu30 extends Common{

    public function zhifu30baocun()
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
//        $time3 = date("t",strtotime($timeup));

        $start = microtime(true);


        $yaoming=Db::name('zhifu30')->where('yuefen',$timeup)->distinct(true)->field('pinming')->select();


        if(empty($yaoming)) return json(['code'=>'0','mes'=>'没有上月份数据，无法计算当月']);
        foreach($yaoming as $ym) {
            $i=-1;
            $benyueliuxiang = Db::name('flowofmed')->field('med_name,med_specs,customer_name,customer_nameb,sum(med_salenum) as med_salenum')
                ->where('med_name', $ym['pinming'])
                ->where('in_time', 'between time', [$timenow . '-01', $timenow . '-' . $time2])
                ->group('med_name,med_specs,customer_name,customer_nameb')
                ->order('med_name')
                ->select();
//            $start2=microtime(true);
            $zhifu = Db::name('zhifu30')->field('diqu,bumen,bumenjingli,yewuyuan,yiyuanjibie,shangyegongsi,
            shangyuejinhuo,yapiabbiaozhun,shangyuexiaoshou,benyuejinhuo,benyuekucun,kehumingcheng1,pinming,
            guige,kehumingcheng2')
                ->where('pinming', $ym['pinming'])
                ->where('yuefen', $timeup)
                ->order('pinming')
                ->select();//
//$zhifu = Db::name('zhifu30') ->where('yuefen', $timeup)->select();
//            dump(microtime(true) - $start2);
//exit;
            foreach ($zhifu as $value) {
                $new["benyuejinhuo"] = 0;
                $new["shangyuejinhuo"] = $value['benyuekucun'];
                $new['yuefen'] = $timenow;
                $new['diqu'] = $value['diqu'];
                $new['yiyuanjibie'] = $value['yiyuanjibie'];
                $new['kehumingcheng1'] = $value['kehumingcheng1'];
                $new['kehumingcheng2'] = $value['kehumingcheng2'];
                $new['shangyegongsi'] = $value['shangyegongsi'];
                $new['bumen'] = $value['bumen'];
                $new['bumenjingli'] = $value['bumenjingli'];
                $new['yewuyuan'] = $value['yewuyuan'];
                $new['pinming'] = $value['pinming'];
                $new['guige'] = $value['guige'];
                $new['yapiabbiaozhun']=$value['yapiabbiaozhun'];
                    foreach ($benyueliuxiang as $k=> $liushui) {
                        if ($value['guige'] == $liushui['med_specs'] &&
                            $value['kehumingcheng1'] == $liushui['customer_name'] &&
                            $value['kehumingcheng2'] == $liushui['customer_nameb']
                        ) {
                            $new["benyuejinhuo"] = $liushui['med_salenum'];
                            unset($benyueliuxiang[$k]);
                            break;
                        }
                    }
                $new['shangyuexiaoshou']=$new['shangyuejinhuo'];
                $new['benyuekucun']=$new['benyuejinhuo'];
                $new['abjine']=bcmul($new['yapiabbiaozhun'],$new['shangyuexiaoshou']);
                $a[]=$new;

            }
                //保存到数据库
            }

        Db::startTrans();
        try {
//            $start2 = microtime(true);

            db('zhifu30')->where('yuefen',$timenow)->delete();

            $rel=db('zhifu30')->limit(40)->insertall($a);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return json(['mes' => ($e->getMessage())]);
        }
        $elapsed = (microtime(true) - $start);

//dump(microtime(true) - $start2);

        if(!empty($rel)){
            return json(['code'=>200,'mes'=>'成功','time'=>$elapsed,'chanchu'=>count($a).'条']);
        }else{
            return json(['code'=>200,'mes'=>'失败']);
        }

    }
    public function zhifu30search(){
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
            return json(['code'=>100,'mes'=>'无结果','data'=>[]]);
        }
        return json(['code'=>200,'mes'=>'成功','data'=>$data,'row'=>$row*10,'count'=>$count]);
    }
    public function zhifu30daochu(){
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
    public function zhifu30daoru(){

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
    public function zhifu30edit(){
        $data['id']=input('id');
        $data['yuefen']=str_replace([' ','/','.'],['','-','-'],input('yuefen'));
        $data['diqu']=input('diqu');
        $data['bumen']=input('bumen');
        $data['bumenjingli']=input('bumenjingli');
        $data['yewuyuan']=input('yewuyuan');
        $data['yiyuanjibie']=input('yiyuanjibie');
        $data['kehumingcheng1']=input('kehumingcheng1');
        $data['kehumingcheng2']=input('kehumingcheng2');
        $data['shangyegongsi']=input('shangyegongsi');
        $data['pinming']=input('pinming');
        $data['guige']=input('guige');
        $data['shangyuejinhuo']=input('shangyuejinhuo');
        $data['benyuejinhuo']=input('benyuejinhuo');
        $data['shangyuexiaoshou']=input('shangyuexiaoshou');
        $data['benyuekucun']=input('benyuekucun');
        $data['yapiabbiaozhun']=input('yapiabbiaozhun');
        $data['abjine']=input('abjine');
        $data['beizhu']=input('beizhu');
        $res=db('zhifu30')->update($data);

        if($res){
            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'未修改']);
        }

    }
    public function zhifu30add(){
        $data['yuefen']=str_replace([' ','/','.'],['','-','-'],input('yuefen'));
        $data['diqu']=input('diqu');
        $data['bumen']=input('bumen');
        $data['bumenjingli']=input('bumenjingli');
        $data['yewuyuan']=input('yewuyuan');
        $data['yiyuanjibie']=input('yiyuanjibie');
        $data['kehumingcheng1']=input('kehumingcheng1');
        $data['kehumingcheng2']=input('kehumingcheng2');
        $data['shangyegongsi']=input('shangyegongsi');
        $data['pinming']=input('pinming');
        $data['guige']=input('guige');
        $data['shangyuejinhuo']=input('shangyuejinhuo');
        $data['benyuejinhuo']=input('benyuejinhuo');
        $data['shangyuexiaoshou']=input('shangyuexiaoshou');
        $data['benyuekucun']=input('benyuekucun');
        $data['yapiabbiaozhun']=input('yapiabbiaozhun');
        $data['abjine']=input('abjine');
        $data['beizhu']=input('beizhu');

        $res=db('zhifu30')->insert($data);

        if($res){
            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'未添加']);
        }

    }
    public function daoru30shanchu(){
        $total=(int)input('total');

        if(!is_int($total)){
            return json(['code' => 0, 'mes' => '没有导入']);
        }
        if($total<=1) {
            return json(['code' => 0, 'mes' => '没有导入']);
        }
        $count=db('zhifu30')->order('id','desc')->limit($total)->field('id')->select();
        $countt=array_column($count,'id');

        $delete=db('zhifu30')->delete($countt);
        if($delete){
            return json(['code'=>200,'message'=>'成功,删除了'.$delete.'条']);
        }else{
            return json(['code'=>0,'message'=>'失败']);
        }
    }
    public function zhifu30del(){
        $del=input('id');
        if(is_array($del)){
            foreach ($del as $dela){
                $rel=db('zhifu30')->where('id',$dela)->delete();
            }
        }else $rel=db('zhifu30')->where('id',$del)->delete();

        if(($rel>=1)){

            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'失败']);
        }

    }


}
