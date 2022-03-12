<?php
namespace app\delivery\controller;
use think\Db;
use think\facade\Env;
use PHPEXCEL;
use think\facade\Cache;
use app\admin\controller\Zhifu;
use think\facade\Request;
use app\delivery\model\Delivery;
use app\mainmenu\controller\Common;
use app\delivery\model\Flowofmed as Flow;
class FlowOfMed extends Common{
    /**
     * createBy phpstorm
     * auth : lclclhj
     * Date : 2021/11/27
     * Time : 09:32
     */
    protected function initialize()
    {
        parent::initialize();
    }
    public function dclist(){
        $time = $this->requests()['month_time'];
        $time1 = $time.'-01';
        $time2 = date("t",strtotime($time));
        $time2 = $time.'-'.$time2;
        $result = Db::name('flowofmed')->whereTime('in_time','between',[$time1,$time2])->order('id','desc')->select();
        if($result){
            return $this->returns(200,'success',$result);
        }else{
            return $this->returns(500,'error');
        }
    }
    public function des(){
        Db::name('flowofmed')->where('id','>',262349)->delete();
    }
    public function deldel(){
        ini_set('memory_limit','1024M');
        set_time_limit(0);
        $lastdrdata = Cache::get('one');
        $lastdrdata2 = Cache::get('two');
//        dump($lastdrdata);
//        dump($lastdrdata2);die;
        if($lastdrdata==false&&$lastdrdata2==false){
           return self::returns(500,'none');
        }elseif ($lastdrdata==false){
            foreach ($lastdrdata2 as $k=>$v){
                $flow = Flow::where('id','=',$v['id'])->delete();;
            }
            Cache::rm('two');
            return self::returns(200,'success');
        }elseif ($lastdrdata2==false){
            foreach ($lastdrdata as $k=>$v){
                $flow = Flow::where('id','=',$v['id'])->delete();;
            }
            Cache::rm('one');
            return self::returns(200,'success');
        }else{
            $r = $lastdrdata->toArray();
            $s = $lastdrdata2->toArray();
            $last = array_merge($r,$s);
            foreach ($last as $k=>$v){
                $flow = Flow::where('id','=',$v['id'])->delete();
            }
            Cache::rm('one');
            Cache::rm('two');
            return self::returns(200,'success');
        }
    }

    public function floflist(){
        // 发货流水列表数据渲染
        if(Request::isGet()){
            $page = $this->requests();
//            dump($page);die;
            $res = (new Flow)->flowlist($page);
            return json(['code'=>200,'message'=>'success','data'=>$res[0],'count'=>$res[1]]);
        }else{
            $data = $this->requests();
//            $data = Request::post();
//            $data = $this->request->post();
//            dump($data);die;
            $field='facname,customer_name,customer_nameb,med_name';
            $result = (new Flow)->selects($data,$field);
            $res = $result[0];
            $count = $result[1];

            if(count($res)>=1){
                foreach ($res as $k=>$v){
                    if($v['in_time']=='1111-11-11'){
                        $v['in_time']='';
                    }
                    $resultarr[]=$v;
                }
            }else{
                $resultarr = [];
                $count=0;
            }
            return json(['code'=>200,'message'=>'success','data'=>$resultarr,'count'=>$count]);
        }
    }
    public function missingdata(){
        $page = $this->requests()['page_num'];
        $whereOr = [
            'facname'=>'',
            'in_time'=>'1111-11-11',
            'in_time'=>'0000-00-00',
            'med_name'=>'',
            'med_specs'=>'',
            'med_unit'=>'',
            'med_batchnum'=>'',
            'med_price'=>'',
            'buss_name'=>'',
            'buss_origin'=>''
        ];
        $where = [
            ['customer_name','=',''],
            ['customer_nameb','=','']
        ];
        $result = Db::name('flowofmed')
            ->where($where)
            ->whereOr($whereOr)
            ->order('id','desc')->page($page?:1,10)->select();
//        dump($result);die;
        $count = Db::name('flowofmed')
            ->where($where)
            ->whereOr($whereOr)
            ->field('id')->count();
        if (!empty($result)){
            foreach ($result as $v){
                if($v['in_time']=='1111-11-11'){
                    $v['in_time']='';
                }
               $resultarr[]=$v;
            }
            return self::returnplus(200,'success',$resultarr,$count);
        }else{
            return $this->returns(202,'data empty');
        }

    }
    public function flowdr(){
        ini_set('memory_limit','1024M');
        set_time_limit(0);
        //上传excel文件
        $file = $this->request->file('file');
        $name = date("Ymd") . rand(1000, 99999);
        $today= date("Ymd");
        $info = $file->validate(['fileSize'=>10485760,'fileExt'=>'xls,xlsx'])->move('./uploads/flowOfMed/flowOfOne'.'/'.$today . "/", $name);
        require_once '../vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
        require_once '../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
        if($info){
            //获取上传到后台的文件名
            $fileName = $info->getSaveName();
//            var_dump($fileName);die;
            //获取文件路径
            $filePath = Env::get('ROOT_PATH').'public'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'flowOfMed'.DIRECTORY_SEPARATOR.'flowOfOne'.DIRECTORY_SEPARATOR.$today.DIRECTORY_SEPARATOR.$fileName;
            //获取文件后缀
            $suffix = $info->getExtension();
            //判断哪种类型
            if($suffix=="xlsx"){
                $reader =  \PHPExcel_IOFactory::createReader('Excel2007');
            }else{
                $reader = \PHPExcel_IOFactory::createReader('Excel5');
            }
            if(!$reader->canRead($filePath)){
                $reader = \PHPExcel_IOFactory::createReader('Excel5');
            }
        }else{
            $this->error('文件过大或格式不正确导致上传失败');
        }
        //载入excel文件
        $excel = $reader->load("$filePath",$encode = 'utf-8');
        //读取第一张表
        $sheet = $excel->getSheet(0);
        //获取总行数
        $row_num = $sheet->getHighestRow();
        $row_nums = 0 ;
        $nullcount=0;

        for($j=0;$j<=$row_num;$j++){
            if($sheet->getCell("B".$j)->getValue()==''&&$sheet->getCell("C".$j)->getValue()==''){
                $nullcount+=1;
                if ($nullcount>=4){
                    break;
                }
            }else{
                $row_nums++;
            }
        }
        //获取总列数
        $col_num = $sheet->getHighestColumn();
        $data = []; //数组形式获取表格数据
        $time = time();
        $de = new Delivery;
        //有标题栏  $i=2; 没有标题栏 $i=1;
//        for ($nums = 0;$nums<$row_nums;$nums+=ceil($row_nums/10)){

//        }
        for ($i = 2; $i <= $row_nums; $i++) {
            //1.商业单位=2.商业公司名称  1.药品名称=2.药品名称  1.规格=2.规格  1.价格=2.价格   1.批号=2.批号 1.名称=2.供货商
            $innum = $de->where('business_unit',str_replace(PHP_EOL, '',trim($sheet->getCell("B".$i)->getValue())))
                ->where('fac_name',str_replace(PHP_EOL, '',trim($sheet->getCell("D".$i)->getValue())))
                ->where('factory_name',str_replace(PHP_EOL,'',trim($sheet->getCell("L".$i)->getValue())))
                ->where('fac_specs',str_replace(PHP_EOL, '',trim($sheet->getCell("E".$i)->getValue())))
                ->where('batch_num',str_replace(PHP_EOL, '',trim($sheet->getCell("H".$i)->getValue())))
                ->select()->toArray();
            #2-2  3-4  4-6  5-8  6-10
            if($innum){
                foreach ($innum as $k=>$v){
                    $innum[$k]['business_unit'] == str_replace(PHP_EOL, '',trim($sheet->getCell("B".$i)->getValue()))&&
                    $innum[$k]['fac_name']      == str_replace(PHP_EOL, '',trim($sheet->getCell("D".$i)->getValue()))&&
                    $innum[$k]['fac_specs']     == str_replace(PHP_EOL, '',trim($sheet->getCell("E".$i)->getValue()))&&
                    $innum[$k]['factory_name']  == str_replace(PHP_EOL, '',trim($sheet->getCell("L".$i)->getValue()))&&
                    $innum[$k]['batch_num']     == str_replace(PHP_EOL, '',trim($sheet->getCell("H".$i)->getValue()))
                    ?($data[$i]['innums']  = str_replace(PHP_EOL, '', $innum[$k]['fac_num'])).
                    ($data[$i]['de_id']    = str_replace(PHP_EOL,'', $innum[$k]['id']))
                    :$data[$i]['innums']   = 0;

                }
            }
            if(trim($sheet->getCell("B".$i)->getValue())==''&&trim($sheet->getCell("C".$i)->getValue())==''&&trim($sheet->getCell("D".$i)->getValue())==''){
            }else{
                try {
                    $data[$i]['facname'] = str_replace(PHP_EOL, '',trim($sheet->getCell("B".$i)->getValue()));
                    if(strlen(trim($sheet->getCell("C".$i)->getValue()))!=10||strstr(trim($sheet->getCell("C".$i)),'.')==true){
                        $data[$i]['in_time'] = str_replace(PHP_EOL, '', trim(date("Y-m-d", trim(($sheet->getCell("C".$i)->getValue() - 25569) * 24 * 60 * 60))));
                    }else{
                        $data[$i]['in_time'] = str_replace(PHP_EOL, '',trim($sheet->getCell("C".$i)->getValue()));
                    }
                    $data[$i]['med_name']       =  str_replace(PHP_EOL, '',trim($sheet->getCell("D".$i)->getValue()));
                    $data[$i]['med_specs']      =  str_replace(PHP_EOL, '',trim($sheet->getCell("E".$i)->getValue()));
                    $data[$i]['med_unit']       =  str_replace(PHP_EOL, '',trim($sheet->getCell("F".$i)->getValue()));
                    $data[$i]['med_salenum']    =  str_replace(PHP_EOL, '',trim($sheet->getCell("G".$i)->getValue()));
                    $data[$i]['med_batchnum']   =  str_replace(PHP_EOL, '',trim($sheet->getCell("H".$i)->getValue()));
                    $data[$i]['med_price']      =  number_format(str_replace(PHP_EOL, '',trim($sheet->getCell("I".$i)->getValue())),2,'.','');
                    $data[$i]['customer_name']  =  str_replace(PHP_EOL, '',trim($sheet->getCell("J".$i)->getValue()));
                    $data[$i]['customer_nameb'] =  str_replace(PHP_EOL, '',trim($sheet->getCell("K".$i)->getValue()));
                    $data[$i]['buss_name']      =  str_replace(PHP_EOL, '',trim($sheet->getCell("L".$i)->getValue()));
                    $data[$i]['buss_origin']    =  str_replace(PHP_EOL, '',trim($sheet->getCell("M".$i)->getValue()));
                    $data[$i]['create_time']    =  $time;
                    $data[$i]['update_time']    =  $time;
                    $data[$i]['ssid']   = 1;
                } catch (\Exception $e){
                    return $this->returns(505,$e->getMessage());
                }
            }
        }
        foreach($data as $dk=>$dv){
           if(!isset($data[$dk]['innums'])){
               $data[$dk]['innums']=0;
           }
           if(empty($data[$dk]['in_time'])){
               $data[$dk]['in_time']='1111-11-11';
            }
        }
        //将数据保存到数据库
        $flow = new Flow;
        $res = $flow->saveAll($data);
        if($res){
            Cache::set('one',$res);
            return $this->returns(200,'导入成功');
        }else{
            return $this->returns(500,'导入失败');
        }
    }
    public function flowtwodr(){
        ini_set('memory_limit','1024M');
        set_time_limit(0);
        $file = $this->request->file('file');
        $name = date("Ymd") . rand(1000, 99999);
        $today= date("Ymd");
        $info = $file->validate(['fileSize'=>104857600,'fileExt'=>'xls,xlsx'])->move('./uploads/flowOfMed/flowOfTwo'.'/'.$today . "/", $name);
        require_once '../vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
        require_once '../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
        if($info){
            //获取上传到后台的文件名
            $fileName = $info->getSaveName();
            //获取文件路径
            $filePath = Env::get('ROOT_PATH').'public'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'flowOfMed'.DIRECTORY_SEPARATOR.'flowOfTwo'.DIRECTORY_SEPARATOR.$today.DIRECTORY_SEPARATOR.$fileName;
            //获取文件后缀
            $suffix = $info->getExtension();
            //判断哪种类型
            if($suffix=="xlsx"){
                $reader =  \PHPExcel_IOFactory::createReader('Excel2007');
            }else{
                $reader = \PHPExcel_IOFactory::createReader('Excel5');
            }
            if(!$reader->canRead($filePath)){
                $reader = \PHPExcel_IOFactory::createReader('Excel5');
            }
        }else{
            $this->error('文件过大或格式不正确导致上传失败');
        }
        //载入excel文件
        $excel = $reader->load("$filePath",$encode = 'utf-8');
        //读取第一张表
        $sheet = $excel->getSheet(0);
        //获取总行数
        $row_num = $sheet->getHighestRow();
        $row_nums = 0 ;
        for($j=0;$j<=$row_num;$j++){
//            if(!trim($sheet->getCell("B".$j)->getValue())==''&&!trim($sheet->getCell("C".$j)->getValue())==''&&!trim($sheet->getCell("D".$j)->getValue())==''){
//                $row_nums+=1;
//            }else{
//                break;
//            }
            $nullcount = 0;
            if($sheet->getCell("B".$j)->getValue()==''&&$sheet->getCell("C".$j)->getValue()==''){
                $nullcount+=1;
                if ($nullcount>=4){
                    break;
                }
            }else{
                $row_nums++;
            }
        }
        //获取总列数
        $col_num = $sheet->getHighestColumn();
        $data = []; //数组形式获取表格数据
        $time = time();
        $flow = new Flow;
        //有标题栏  $i=2; 没有标题栏 $i=1;
        for ($i = 2; $i <= $row_nums; $i++) {
            //+trim
            //1.客户名称a,b=2.商业公司名称 1.商业公司名称=2.供应商   1.药品名称=2药品名称   1.规格=2.规格   1.计量单位=2.计量单位   1.批号=2.批号   产地？？？
            $innum2 = $flow->where('customer_name',str_replace(PHP_EOL,'',trim($sheet->getCell("B".$i)->getValue())))
                ->whereOr('customer_nameb',str_replace(PHP_EOL,'',trim($sheet->getCell("B".$i)->getValue())))
//                ->where('facname',str_replace(PHP_EOL, '',trim($sheet->getCell("L".$i)->getValue())))
                ->where('med_name',str_replace(PHP_EOL, '',trim($sheet->getCell("D".$i)->getValue())))
                ->where('med_specs',str_replace(PHP_EOL, '',trim($sheet->getCell("E".$i)->getValue())))
                ->where('med_unit',str_replace(PHP_EOL, '',trim($sheet->getCell("F".$i)->getValue())))
                ->where('med_batchnum',str_replace(PHP_EOL, '',trim($sheet->getCell("H".$i)->getValue())))
//                ->where('buss_origin',str_replace(PHP_EOL,'',trim($sheet->getCell("M".$i)->getValue())))
                ->select()->toArray();
//        dump($innum2);die;
            if($innum2){
                if(count($innum2)>1){
                    $data[$i]['innums'] = 0;
                    $data[$i]['buss_name']='';
                    foreach ($innum2 as $k=>$v){
                        if($innum2[$k]['customer_name']  ==  str_replace(PHP_EOL,'',trim($sheet->getCell("B".$i)->getValue()))&&
                            $innum2[$k]['med_name']       ==  str_replace(PHP_EOL, '',trim($sheet->getCell("D".$i)->getValue()))&&
                            $innum2[$k]['med_specs']      ==  str_replace(PHP_EOL, '',trim($sheet->getCell("E".$i)->getValue()))&&
                            $innum2[$k]['med_unit']       ==  str_replace(PHP_EOL, '',trim($sheet->getCell("F".$i)->getValue()))&&
                            $innum2[$k]['med_batchnum']   ==  str_replace(PHP_EOL, '',trim($sheet->getCell("H".$i)->getValue())))
                        {
                            $data[$i]['innums']  =  str_replace(PHP_EOL, '', $innum2[$k]['med_salenum']);
                            $data[$i]['buss_name'] .= str_replace(PHP_EOL,'',$innum2[$k]['facname'].'/');
                            $data[$i]['one_id']   =  $innum2[$k]['id'];
                        }else{
                            $data[$i]['innums']   =  0;
                            $data[$i]['buss_name'] = '';
                        }
                    }
                }else{
                    foreach ($innum2 as $k=>$v){
                        if($innum2[$k]['customer_name']  ==  str_replace(PHP_EOL,'',trim($sheet->getCell("B".$i)->getValue()))&&
                            $innum2[$k]['med_name']       ==  str_replace(PHP_EOL, '',trim($sheet->getCell("D".$i)->getValue()))&&
                            $innum2[$k]['med_specs']      ==  str_replace(PHP_EOL, '',trim($sheet->getCell("E".$i)->getValue()))&&
                            $innum2[$k]['med_unit']       ==  str_replace(PHP_EOL, '',trim($sheet->getCell("F".$i)->getValue()))&&
                            $innum2[$k]['med_batchnum']   ==  str_replace(PHP_EOL, '',trim($sheet->getCell("H".$i)->getValue())))
                        {
                            $data[$i]['innums']  =  str_replace(PHP_EOL, '', $innum2[$k]['med_salenum']);
                            $data[$i]['buss_name'] = str_replace(PHP_EOL,'',$innum2[$k]['facname']);
                            $data[$i]['one_id']   =  $innum2[$k]['id'];
                        }else{
                            $data[$i]['innums']   =  0;
                            $data[$i]['buss_name'] = '';
                        }
                    }
                }

            }
            if(trim($sheet->getCell("B".$i)->getValue())==''&&trim($sheet->getCell("C".$i)->getValue())==''&&trim($sheet->getCell("D".$i)->getValue())==''){
            }else{
                try {
//                    dump(date('Y-m-d',(44562.378472222-25569)*24*60*60));die;
                    $data[$i]['facname'] = str_replace(PHP_EOL, '',trim($sheet->getCell("B".$i)->getValue()));
//                    dump(str_replace(PHP_EOL, '',trim($sheet->getCell("C".$i)->getValue())));die;
//                    dump(str_replace(PHP_EOL, '', trim(date("Y-m-d", trim(($sheet->getCell("C".$i)->getValue() - 25569) * 24 * 60 * 60)))));die;
                    if(strlen(trim($sheet->getCell("C".$i)->getValue()))!=10||strstr(trim($sheet->getCell("C".$i)),'.')==true){
                        $data[$i]['in_time'] =str_replace(PHP_EOL, '', trim(date("Y-m-d", trim(($sheet->getCell("C".$i)->getValue() - 25569) * 24 * 60 * 60))));
                    }else{
//                        dump(str_replace(PHP_EOL, '',trim($sheet->getCell("C".$i)->getValue())));die;
                        $data[$i]['in_time'] = str_replace(PHP_EOL, '',trim($sheet->getCell("C".$i)->getValue()));
                    }
                    $data[$i]['med_name']        =  str_replace(PHP_EOL, '',trim($sheet->getCell("D".$i)->getValue()));
                    $data[$i]['med_specs']       =  str_replace(PHP_EOL, '',trim($sheet->getCell("E".$i)->getValue()));
                    $data[$i]['med_unit']        =  str_replace(PHP_EOL, '',trim($sheet->getCell("F".$i)->getValue()));
                    $data[$i]['med_salenum']     =  str_replace(PHP_EOL, '',trim($sheet->getCell("G".$i)->getValue()));
                    $data[$i]['med_batchnum']    =  str_replace(PHP_EOL, '',trim($sheet->getCell("H".$i)->getValue()));
                    $data[$i]['med_price']       =  number_format(str_replace(PHP_EOL, '',trim($sheet->getCell("I".$i)->getValue())),2,'.','');
                    $data[$i]['customer_name']   =  str_replace(PHP_EOL, '',trim($sheet->getCell("J".$i)->getValue()));
                    $data[$i]['customer_nameb']  =  str_replace(PHP_EOL, '',trim($sheet->getCell("K".$i)->getValue()));
//                    $data[$i]['buss_name']       =  str_replace(PHP_EOL, '',trim($sheet->getCell("L".$i)->getValue()));
                    $data[$i]['buss_origin']     =  str_replace(PHP_EOL, '',trim($sheet->getCell("M".$i)->getValue()));
                    $data[$i]['create_time']     =  $time;
                    $data[$i]['update_time']     =  $time;
                    $data[$i]['ssid']            =  2;

                }catch (\Exception $e){
                    return $this->returns(500,$e->getMessage());
                }

            }
        }
        foreach($data as $dk=>$dv){
            if(!isset($data[$dk]['innums'])){
                $data[$dk]['innums']=0;
            }
            if(empty($data[$dk]['in_time'])){
                $data[$dk]['in_time']='1111-11-11';
            }
//            if(strstr($data[$dk]['in_time'],'-')==false || strstr($data[$dk]['in_time'],'/')==false){
//               $data[$dk]['in_time']=date('Y-m-d',strtotime(($data[$dk]['in_time']-25569)*24*60*60));
//               dump($data[$dk]['in_time']);
//            }
        }

        //将数据保存到数据库
//        dump($data);die;
        $res = $flow->saveAll($data);
        if($res){
            Cache::set('two',$res);
            return $this->returns(200,'导入成功');
        }else{
            return $this->returns(500,'导入失败');
        }
    }
    public function flowEdit(){
        $datas = $this->requests('edit');
        if (empty($datas[0]['in_time'])){
            $datas[0]['in_time']='1111-11-11';
        }
        foreach ($datas[0] as $k=>$v){
            $data[$k] = trim($v);
        }
        $data['update_time']=time();
        $res = (new Flow)->editlist(new Flow,$data);
        if($res){
            return $this->returns(200,'success');
        }
    }
    public function flowDel(){
        $id = $this->request->param('id');
        $flow = new Flow;
        $res =(new Flow)->listdel($flow,$id);
        if ($res){
            return self::returns(200,'删除成功');
        }
    }
    public function flowAdd(){
        $data = $this->requests('add');
        if (empty($data[0]['in_time'])){
            $data[0]['in_time']='1111-11-11';
        }
        foreach ($data[0] as $k=>$v){
            $datas[$k] = trim($v);
        }
        $res = (new Flow)->fladd($datas);
        if($res){
            return $this->returns(200,'success');
        }
    }
}