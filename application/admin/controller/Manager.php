<?php
namespace app\admin\controller;
//use think\Controller;
use think\Db;
use think\Request;
use app\common\Common;
use think\facade\Cache;
use think\facade\Session;

class Manager extends Common
{

    public function manager(){

        $userid=input('userid');
        $useridcaozuo=$userid.'caozuo';
        $count=db('out o')
            ->where('deletetime','')
            //->fetchsql()
            ->count();

        $currentPage=input('currentPage');
        $pagenum=input('pageCount');
        $row=ceil($count/$pagenum);

         $manager=db('out o')->order('o.id')
             ->limit($currentPage*$pagenum-$pagenum,$pagenum)
             ->where('deletetime','')
             ->select();

        if($manager){

            $useridname=$userid.'name';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'查看了基础信息备案';
            if(!cache($useridcaozuo)==$caozuo['data']){
                $this->caozuojilu($caozuo);
                cache($useridcaozuo,$caozuo['data']);
            }else{
                //相同不添加;
            }

            return json(['code'=>200,'message'=>'访问','data'=>$manager,'row'=>$row*10,'count'=>$count]);
        }else{
            if(empty($manager)){
                return json(['code'=>100,'message'=>'未查询到','data'=>[]]);
            }
                return json(['code'=>0,'message'=>'失败']);
        }

    }
    public function manageradd(){

        $data['zhongduanmingcheng']=input('zhongduanmingcheng');
        $data['zhongduanmingcheng2']='';
        $data['yiyuanjibie']=input('yiyuanjibie');
        $data['yewuyuan']=input('yewuyuan');
        $data['daibiao']=input('daibiao');
        $data['zhuguan']=input('zhuguan');
        $data['bumenjingli']=input('bumenjingli');
        $data['diqu']=input('diqu');
        $data['bumen']=input('bumen');
        $data['pinming']=input('pinming');
        $data['guige']=input('guige');
        $data['chandi']=input('chandi');
        $data['renwu']=input('renwu');
        $data['status']='正常在职';
        $data['zhuguanjiangjinticheng']=input('zhuguanjiangjinticheng');
        $data['jinglijiangjinticheng']=input('jinglijiangjinticheng');
        $data['abbiaozhunshuihou']=input('abbiaozhunshuihou');
        $data['lunwenfei']=input('lunwenfei');
        $data['daibiaojiangjinticheng']=input('daibiaojiangjinticheng');
        $data['yapiabbiaozhun']=input('yapiabbiaozhun');
        $data['zhifufangfa']=input('zhifufangfa');
        $data['deletetime']='';
        $abc=db('out')->insert($data);

        if($abc){
            $userid=input('userid');
            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'添加了业务员';
            $this->caozuojilu($caozuo);
            cache($useridcaozuo,$caozuo['data']);
            return json(['code'=>200,'message'=>'成功']);
            }
        else{
            return json(['code'=>0,'message'=>'未提交表单']);
            //未提交表单
        }
    }

    public function manageredit(){
            $data['id']=input('id');
            $data['zhongduanmingcheng']=input('zhongduanmingcheng');
            $data['zhongduanmingcheng2']=input('zhongduanmingcheng2');
            $data['yiyuanjibie']=input('yiyuanjibie');
            $data['yewuyuan']=input('yewuyuan');
            $data['daibiao']=input('daibiao');
            $data['zhuguan']=input('zhuguan');
            $data['bumenjingli']=input('bumenjingli');
            $data['diqu']=input('diqu');
            $data['bumen']=input('bumen');
            $data['pinming']=input('pinming');
            $data['guige']=input('guige');
            $data['chandi']=input('chandi');
            $data['renwu']=input('renwu');
            $data['zhuguanjiangjinticheng']=input('zhuguanjiangjinticheng');
            $data['jinglijiangjinticheng']=input('jinglijiangjinticheng');
            $data['abbiaozhunshuihou']=input('abbiaozhunshuihou');
            $data['lunwenfei']=input('lunwenfei');
            $data['daibiaojiangjinticheng']=input('daibiaojiangjinticheng');
            $data['yapiabbiaozhun']=input('yapiabbiaozhun');
            $data['zhifufangfa']=input('zhifufangfa');
            //$data['status']=input('status');

            $res=db('out')->update($data);

            if($res){
                $userid=input('userid');
                $useridname=$userid.'name';
                $useridcaozuo=$userid.'caozuo';
                $caozuo['time']=date('Y-m-d H:i:s',time());
                $caozuo['data']='用户'.cache($useridname).'修改了业务员';
                $this->caozuojilu($caozuo);
                cache($useridcaozuo,$caozuo['data']);
                return json(['code'=>200,'message'=>'成功']);
            }else{
                return json(['code'=>0,'message'=>'未修改']);
            }

    }

    public function managerdel(){
        $del=input('id');
        $userid=input('userid');

            if(is_array($del)){
            foreach ($del as $dela){
                $rel=db('out')->where('id',$dela)->update(['deletetime'=>date('Y-m-d H:i:s ',time())]);

            }

        }else{
            //var_dump($data);
                $rel=db('out')->where('id',$del)->update(['deletetime'=>date('Y-m-d H:i:s ',time())]);

        }
        if(($rel>=1)){

            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'删除了业务员';
            $this->caozuojilu($caozuo);
            cache($useridcaozuo,$caozuo['data']);
            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'失败']);
        }

    }
    public function managermove(){

            $ida = input('ida');
            $yewuyuanb = input('yewuyuanb');
            //转移给业务员B
                $bren = db('out')->where('id',$ida)->find();
                $bren['yewuyuan']=$yewuyuanb;
                $bren['id']='';
                $ins = db('out')->strict(false)->insert($bren);
            //更新状态
                $move=db('out')->where('id',$ida)->update(['status'=>'已被转移业务']);

            if($ins){
                $userid=input('userid');
                $useridname=$userid.'name';
                $useridcaozuo=$userid.'caozuo';
                $caozuo['time']=date('Y-m-d H:i:s',time());
                $caozuo['data']='用户'.cache($useridname).'移动了业务员业务';
                $this->caozuojilu($caozuo);
                cache($useridcaozuo,$caozuo['data']);
                return json(['code'=>200,'message'=>'成功']);
            }else{
                return  json(['code'=>0,'message'=>'失败']);
            }


    }
    public function managermoveb(){
        $yewuyuan = input('yewuyuan');
        $managerb=db('out o')
            ->order('o.yewuyuan')
            ->field('o.yewuyuan,o.id')
            ->where('o.yewuyuan','neq',$yewuyuan)
            ->select();
        $name=array_column($managerb,'yewuyuan');
        $id=array_column($managerb,'id');

        for ($i=0;$i<count($id);$i++){
            $c[$i]['id']=$id[$i];
            $c[$i]['yewuyuan']=$name[$i];
        }
        $tmp_arr['yewuyuan']='';
            foreach ($c as $k => $v) {
                if(in_array($tmp_arr['yewuyuan'], $v)){//搜索$v[$key]是否在$tmp_arr数组中存在，若存在返回true
                    unset($c[$k]);
                }else{
                    $tmp_arr['yewuyuan'] = $v['yewuyuan'];
                }
            }

            sort($c); //sort函数对数组进行排序
            
       if($c){
            return json(['code'=>200,'message'=>'访问','data'=>$c]);
       }else{
            return json(['code'=>0,'message'=>'失败']);
        }
    }
    public function managersearch(){
        $leixing=input('leixing');
        $jieguo=input('jieguo');

        $count=db('out o')
            ->where($leixing,'like','%'.$jieguo.'%' )
            ->count();
        $currentPage=input('currentPage');
        $pagenum=input('pageCount');
        $row=ceil($count/$pagenum);
        $manager=db('out o')->order('o.zhongduanmingcheng')
            ->limit($currentPage*$pagenum-$pagenum,$pagenum)
            ->where($leixing,'like','%'.$jieguo.'%' )
            ->select();
        //var_dump($manager);

        if($manager){
            return json(['code'=>200,'message'=>'成功','search'=>1,'data'=>$manager,'row'=>$row*10,'count'=>$count]);
        }else{
            if(empty($manager)){
                return json(['code'=>100,'message'=>'未查询到','data'=>[]]);
            }
            return  json(['code'=>0,'message'=>'未搜索到相关内容']);
        }
    }
    public function managerdaochu(){
        ini_set('memory_limit','1024M');
        set_time_limit(0);
        require_once '../vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
        require_once '../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
        $data=db('out')->select();

        $PHPExcel = new \PHPExcel(); //实例化类
        //创建sheet
        // $PHPExcel->createSheet();
        //获取sheet

        $PHPSheet=$PHPExcel->setActiveSheetIndex(0);//获取文件薄
        $PHPSheet->setTitle('备案');//获取栏目的sheet修改名字
        $PHPSheet->setCellValue('A1','终端名称')->setCellValue('B1','部门经理')->setCellValue('C1','主管')->setCellValue('D1','业务员')
            ->setCellValue('E1','代表')->setCellValue('F1','医院级别')->setCellValue('G1','地区')->setCellValue('H1','部门')
            ->setCellValue('I1','品名')->setCellValue('J1','规格')->setCellValue('K1','产地')
            ->setCellValue('L1','任务')->setCellValue('M1','主管奖金提成')->setCellValue('N1','经理奖金提成')->setCellValue('O1','ab标准税后')
            ->setCellValue('P1','论文费')->setCellValue('Q1','代表奖金提成')->setCellValue('R1','压批ab标准')
            ->setCellValue('S1','支付方法')->setCellValue('T1','终端别名')
        ;
        if(empty($data)){
            goto tiaoguo;
        }
        foreach($data as $key => $value){
            $PHPSheet->setCellValue('A'.($key+2),$value['zhongduanmingcheng'])->setCellValue('B'.($key+2),$value['bumenjingli'])
                ->setCellValue('C'.($key+2),$value['zhuguan'])->setCellValue('D'.($key+2),$value['yewuyuan'])
                ->setCellValue('E'.($key+2),$value['daibiao'])->setCellValue('F'.($key+2),$value['yiyuanjibie'])
                ->setCellValue('G'.($key+2),$value['diqu'])->setCellValue('H'.($key+2),$value['bumen'])
                ->setCellValue('I'.($key+2),$value['pinming'])->setCellValue('J'.($key+2),$value['guige'])
                ->setCellValue('K'.($key+2),$value['chandi'])->setCellValue('L'.($key+2),$value['renwu'])
                ->setCellValue('M'.($key+2),$value['zhuguanjiangjinticheng'])->setCellValue('N'.($key+2),$value['jinglijiangjinticheng'])->setCellValue('Q'.($key+2),$value['abbiaozhunshuihou'])
                ->setCellValue('P'.($key+2),$value['lunwenfei'])->setCellValue('Q'.($key+2),$value['daibiaojiangjinticheng'])->setCellValue('R'.($key+2),$value['yapiabbiaozhun'])
                ->setCellValue('S'.($key+2),$value['zhifufangfa'])->setCellValue('T'.($key+2),$value['zhongduanmingcheng2']);
        }
        tiaoguo:
        ob_end_clean();// 就是加这句
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
        header('Content-Disposition: attachment;filename="备案.xlsx"');
        $PHPWriter=\PHPExcel_IOFactory::createWriter($PHPExcel,'Excel2007');
        header('Cache-Control:max-age=0');
        $PHPWriter->save('php://output');
    }
    public function managerdaoru(){
        ini_set('memory_limit','512M');
        require_once('../vendor/phpoffice/phpexcel/Classes/PHPExcel/Reader/Excel2007.php');
        require_once('../vendor/phpoffice/phpexcel/Classes/PHPExcel/Reader/Excel5.php');

        //$objReader = new \PHPExcel_Reader_Excel2007; //实例化类
        $data = request()->file('data');

        if($data){
            $wenjian=$data->validate(['ext'=>'xls,xlsx'])->move('../public/uploads');
            $wenjian1=str_replace("\\","/",$wenjian->getSaveName());

            $suffix = $wenjian->getExtension();
            //判断哪种类型
            if($suffix=="xlsx"){
                $reader = \PHPExcel_IOFactory::createReader('Excel2007');
                // echo '1';
            }else{
                $reader = \PHPExcel_IOFactory::createReader('Excel5');
                //echo '2';
            }

        }else{
            $this->error();
        }

        //$a['pic']=ROOT_PATH.'public/static/uploads/'.$wenjian1;//绝对路径
        $a='../public/uploads/'.$wenjian1;      //相对路径
        if(!$reader->canRead($a)){
            $reader = \PHPExcel_IOFactory::createReader('Excel5');
        }

        $excel = $reader->load($a,$encode = 'utf-8');

        // $objPHPExcel = $objReader->load($a); //读取excel文件

        $sheetContent = $excel -> getSheet(0) -> toArray();

        unset($sheetContent[0]);

        if(empty($sheetContent)){
            return json(['message'=>'空数据']);
        }
        foreach ($sheetContent as $k => $v){
            if(empty(array_filter($v))) {
                continue;
            }
            $dataa['zhongduanmingcheng']=str_replace(' ','',$v[0]);
            $dataa['bumenjingli']=str_replace(' ','',$v[1]);
            $dataa['zhuguan']=str_replace(' ','',$v[2]);
            $dataa['yewuyuan']=str_replace(' ','',$v[3]);
            $dataa['daibiao']=str_replace(' ','',$v[4]);
            $dataa['yiyuanjibie']=str_replace(' ','',$v[5]);
            $dataa['diqu']=str_replace(' ','',$v[6]);
            $dataa['bumen']=str_replace(' ','',$v[7]);
            $dataa['pinming']=str_replace(' ','',$v[8]);
            $dataa['guige']=str_replace(' ','',$v[9]);
            $dataa['chandi']=str_replace(' ','',$v[10]);
            $dataa['renwu']=str_replace(' ','',$v[11]);
            $dataa['status']='正常在职';
            $dataa['zhuguanjiangjinticheng']=str_replace(' ','',$v[12]);
            $dataa['jinglijiangjinticheng']=str_replace(' ','',$v[13]);
            $dataa['abbiaozhunshuihou']=str_replace(' ','',$v[14]);
            $dataa['lunwenfei']=str_replace(' ','',$v[15]);
            $dataa['daibiaojiangjinticheng']=str_replace(' ','',$v[16]);
            $dataa['yapiabbiaozhun']=str_replace(' ','',$v[17]);
            $dataa['zhifufangfa']=str_replace(' ','',$v[18]);
            $dataa['zhongduanmingcheng2']=str_replace(' ','',$v[19]);;
            $dataa['deletetime']='';
            $res[] = $dataa;

        }
        $total=count($res);
//        $chongfu=array_column($res,'pinming');
//        $chongfu=array_filter($chongfu);
//        $yiyou=db('chanpin')->field('chanpin')->select();
//        $yy=array_column($yiyou,'chanpin');
//        $a=array_diff(array_unique($chongfu),$yy);
//        $jieguo=array_values($a);
//        for($i=0;$i<count($jieguo);$i++){
//            $b[$i]['chanpin']=$jieguo[$i];
//        }
        Db::startTrans();
        try{//注意不要用助手函数
            $rel=Db::name('out')->limit(200)->insertall($res);
            // 提交事务
            Db::commit();

        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();

            return json(['code'=>0,'message'=>$e->getMessage()]);
        }


//        if(!empty($b)){
//            $res=db('chanpin')->insertall($b);
//        }
//            $sheet=$objPHPExcel->getSheet(0); //读取第一张表
//            $row_num=$sheet->getHighestRow();//获取行
//            //var_dump($row_num);die;
//            $col_num=$sheet->getHighestColumn();//获取列
//            //var_dump($col_num);die;
//            $data=[];
//            for($h=2;$h<=$row_num;$h++){
//
//
//                $data[$h-2]['hospitalid']=$sheet->getCell("A".$h)->getValue();
//                $data[$h-2]['name']=$sheet->getCell("B".$h)->getValue();
//                $data[$h-2]['anothername']=$sheet->getCell("C".$h)->getValue();
//                $data[$h-2]['place']=$sheet->getCell("D".$h)->getValue();
//
//            };
//            $res=db('hospital')->insertAll($data);
        if($rel > 0){
            $userid=input('userid');
            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';

//            $useriddaoru=$userid.'daoru';
//            cache($useriddaoru,$res['id'])

            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'导入了文件';
            $this->caozuojilu($caozuo);
            cache($useridcaozuo,$caozuo['data']);

            $manager=db('out o')->order('o.zhongduanmingcheng')->select();

            return json(['code'=>200,'message'=>'成功','data'=>$manager,'total'=>$total]);
        }else{
            return json(['code'=>0,'message'=>'失败','total'=>$total]);

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
        $count=db('out')->order('id','desc')->limit($total)->field('id')->select();
        $countt=array_column($count,'id');

        $delete=db('out')->delete($countt);
        if($delete){
        return json(['code'=>200,'message'=>'成功,删除了'.$delete.'条']);
            }else{
        return json(['code'=>0,'message'=>'失败']);
        }
    }
    public function qingkong(){
        $res=db('out')->delete(true);
        if($res){
            return json(['code'=>200,'message'=>'成功删除']);
        }else{
            return json(['code'=>0,'message'=>'失败']);
        }
    }
    //生成器的练习
    public function scq(){
        $fun=function (){
        for($i=0;$i<10;$i++){
            $data=yield $i;
            if($data=='aaa'){
                return;
            }
        }};
        $ceshi=$fun();
        foreach ($ceshi as $b=>$a){
            if($a==4){
                $ceshi->send('aaa');
            }
            echo $a.'<br>';
        }
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
            return json(['message'=>'空数据']);
        }
        foreach ($sheetContent as $k => $v){
            if(empty(array_filter($v))) {
                continue;
            }
            $dataa['zhongduanmingcheng']=str_replace(' ','',$v[7]);
            $dataa['bumenjingli']=str_replace(' ','',$v[2]);
            $dataa['zhuguan']=str_replace(' ','',$v[3]);
            $dataa['yewuyuan']=str_replace(' ','',$v[4]);
            $dataa['daibiao']=str_replace(' ','',$v[5]);
            $dataa['yiyuanjibie']=str_replace(' ','',$v[6]);
            $dataa['diqu']=str_replace(' ','',$v[0]);
            $dataa['bumen']=str_replace(' ','',$v[1]);
            $dataa['pinming']=str_replace(' ','',$v[9]);
            $dataa['guige']=str_replace(' ','',$v[10]);
            $dataa['chandi']=str_replace(' ','',$v[11]);
            $dataa['renwu']=str_replace(' ','',$v[28]);
            $dataa['status']='正常在职';
            $dataa['zhuguanjiangjinticheng']=str_replace(' ','',$v[22]);
            $dataa['jinglijiangjinticheng']=str_replace(' ','',$v[20]);
            $dataa['abbiaozhunshuihou']=str_replace(' ','',$v[16]);
            $dataa['lunwenfei']=str_replace(' ','',$v[18]);
            $dataa['daibiaojiangjinticheng']=str_replace(' ','',$v[24]);
            $dataa['yapiabbiaozhun']=str_replace(' ','',$v[32]);
            $dataa['zhifufangfa']=str_replace(' ','',$v[33]);
            $dataa['zhongduanmingcheng2']=str_replace(' ','',$v[34]);
            $dataa['deletetime']='';
            $res[] = $dataa;

        }
        $total=count($res);

        $rel=db('out')->limit(200)->insertall($res);

        if($rel > 0){


            return json(['code'=>200,'message'=>'成功','total'=>$total]);
        }else{
            return json(['code'=>0,'message'=>'失败','total'=>$total]);

        }
    }
    public function xiugaixingming(){
        $zhongduanmingcheng=input('zhongduanmingcheng');
        $leixing=input('leixing');
        $name=input('name');
        $upname=input('upname');

        $data=Db::name('out')->where('zhongduanmingcheng',$zhongduanmingcheng)->where($leixing,$name)->update([$leixing=>$upname]);
      //dump($data);
        if($data){
            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'未修改']);

        }
    }

}
