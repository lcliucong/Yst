<?php
namespace app\reserve\controller;

use think\facade\Request;
use think\facade\Cache;
use think\facade\Env;
use app\reserve\model\MedReserve as Medst;
use app\mainmenu\controller\Common;
use app\delivery\controller\FlowOfMed;
use app\delivery\model\Flowofmed as Mflow;
use think\Db;

class Reserve extends Common{
    /**
     * createBy phpstorm
     * auth : lc
     * Date : 2021/11/30
     * Time : 15:15
     */
    protected function initialize()
    {
        parent::initialize();
    }
    public function upd(){
        for ($i=0;$i<2;$i++){
            $var = [
                'innums'=>1000,
                'facname'=>'阿萨德炮',
                'in_time'=>'2022-03-15',
                'med_name'=>'恩恩额',
                'med_specs'=>'恍恍惚惚',
                'med_unit'=>'恩恩额',
                'med_salenum'=>1000,
                'med_batchnum'=>20221212,
                'med_price'=>666,
                'customer_name'=>'行行行',
                'customer_nameb'=>'',
                'buss_name'=>'行行行',
                'buss_origin'=>'行行行',
                'ssid'=>2
            ];
            Db::name('flowofmed')->insert($var);
        }
    }
    public function upd2(){
        $rse = Db::name('med_reserve')->where('operation_time','>=','2022-01-01')->delete();
        if ($rse){
            dump('ok');
        }
    }
    public function setnul(){
        $res = (new Medst)->where('operation_time','between time',["2022-01-01","2022-01-31"])->delete();
        if ($res){
            dump('ok');
        }else{
            dump('no');
        }
    }
    public function medLists(){
        if(Request::isGet()){
            $res = (new Medst)->medstlist();
            return self::returns(200,'success',$res);
        }else{
            $data = $this->requests();
            //商业公司名称,产品名称,规格,计量单位,产地,库存数量,批号,操作时间
            $field='company_name,names,fac_specs,measuring_unit,origin,stock_num,batch_num';
            $res = (new Medst)->meselects($data,$field);

            if($res[1]=='nocounts'){
                unset($res[1]);
//                dump($res);die;
                return $this->returns(200,'success',$res[0]);
            }else{
                $result = $res[0]->toArray();
                $sums['stock_num'] = $res[1];
                if (!empty($result)){
                    array_push($result,$sums);

                }
                return $this->returns(200,'success',$result);
            }
        }
    }
    public function FetchRepeatMemberInArray($array) {
        $len = count($array);
        for($i = 0; $i < $len; $i ++) {
            for($j = $i+1; $j < $len; $j ++) {
                if ($array [$i] == $array [$j]) {
                    $repeat_arr [] = $array [$i];
                    break;
                }

            }
        }
        if(!isset($repeat_arr)){
            $repeat_arr = $array;
        }else{

        }

        return $repeat_arr;
    }
    public function array_unique_fb($array)
    {
        $temp=[];
        $temp2=[];
        foreach ($array as $k=>$v)
        {
            //数组降维
            $v = join(",",$v);
            $temp[$k] = $v;
        }
        /**
         * 加上入库数量一起去重，剩下的可能出现一条数据因为有不同的入库数量发生重复的情况   然后sum 入库数量，  在去重？
         */

        //字符串去重(一维数组去重)
        $temp = array_unique($temp);
        foreach ($temp as $k => $v)
        {
            //数组重新组装
            $array=explode(",",$v);
            //保留键名，再命名
            $temp2[$k]["facname"]      = $array[0];
            $temp2[$k]["med_name"]     = $array[1];
            $temp2[$k]["med_specs"]    = $array[2];
            $temp2[$k]["med_unit"]     = $array[3];
            $temp2[$k]["buss_origin"]  = $array[4];
            $temp2[$k]["med_batchnum"] = $array[5];
        }
        return $temp2;
    }
    public function array_unique_fb1($array)
    {
        $temp=[];
        $temp2=[];
        foreach ($array as $k=>$v)
        {
            //数组降维
            $v = join(",",$v);
            $temp[$k] = $v;
        }
        /**
         * 加上入库数量一起去重，剩下的可能出现一条数据因为有不同的入库数量发生重复的情况   然后sum 入库数量，  在去重？
         */
        //字符串去重(一维数组去重)
        $temp = array_unique($temp);
        foreach ($temp as $k => $v)
        {
            //数组重新组装
            $array=explode(",",$v);
            //保留键名，再命名
            $temp2[$k]['innums']       = $array[0];
            $temp2[$k]["facname"]      = $array[1];
            $temp2[$k]["med_name"]     = $array[2];
            $temp2[$k]["med_specs"]    = $array[3];
            $temp2[$k]["med_unit"]     = $array[4];
            $temp2[$k]["buss_origin"]  = $array[5];
            $temp2[$k]["med_batchnum"] = $array[6];
        }
        return $temp2;
    }
    public function mstlist(){
        // 库存列表数据生成
        if(Request::isPost()){
            //获取生成库存的月份
            ini_set('memory_limit','2048M');
            set_time_limit(0);
            $timed = $this->requests()['my_date'];
            $timeopen = $timed.'-01';
            $timedd = date("t",strtotime($timed));
//            本月末
            $timeend = $timed.'-'.$timedd;
            //判断是否已经生成过
            $lastdata = Medst::field('id,operation_time')->where('operation_time','between time',[$timeopen,$timeend])->limit(1)->find();

            $datetime = substr($lastdata['operation_time'],0,-3);
//            if($timed==$datetime){
//                return $this->returns(500,'Already exists');
//            }
            //判断是否已经生成过结束

            #查询商业公司名称、药品名称、规格、计量单位、产地、批号一样的数据
//            本月初
            $time1 = $timed.'-01';
            $time2 = date("t",strtotime($timed));
//            本月末
            $time2 = $timed.'-'.$time2;
            $res = Db::name('flowofmed')
                ->field('facname,med_name,med_specs,med_unit,buss_origin,med_batchnum')
                ->where('in_time','between time',[$time1,$time2])
                ->select();
            $res1 = Db::name('flowofmed')
                ->field('innums,facname,med_name,med_specs,med_unit,buss_origin,med_batchnum')
                ->where('in_time','between time',[$time1,$time2])
                ->select();

//            dump($res);die;
//            if (empty($res)){
//                return self::returns(504,'data empty');
//            }
            # $repeat_arr 是以上条件相同的数据的数组集合
//            $repeat_arr = $this->FetchRepeatMemberInArray($res);
            #去重 最后要留下的，展示的数据
            $repeat_arr=$this->array_unique_fb($res);
            #去重 包含入库数量的重复
            $repeat_arr1 = $this->array_unique_fb1($res1);
            $repeat_arr1 = array_values($repeat_arr1);
            Db::name('temporary_transfer')->insertAll($repeat_arr1); // distinct  x.facname,x.med_name,x.med_specs,x.med_unit,x.buss_origin,x.med_batchnum,x.innums
//            $a=Db::query("
//                select  sum(x.innums)
//                from tp51_flowofmed as x,tp51_flowofmed as y
//                where x.innums!=y.innums and x.facname=y.facname and x.med_name=y.med_name and x.med_specs=y.med_specs and x.med_unit = y.med_unit and x.buss_origin = y.buss_origin and x.med_batchnum = y.med_batchnum
//                group by x.facname,x.med_name,x.med_specs,x.med_unit,x.buss_origin,x.med_batchnum
//            ");
////            var_dump($a);die;
//            foreach ($repeat_arr as $rk=>$rv) {
//                $rsda[$rk] = Db::name('temporary_transfer')//->field('id,innums,facname,med_name,med_specs,med_unit,buss_origin,med_batchnum')
//                    ->where([
//                        ['facname','=',$rv['facname']],
//                        ['med_name','=',$rv['med_name']],
//                        ['med_specs','=',$rv['med_specs']],
//                        ['med_unit','=',$rv['med_unit']],
//                        ['buss_origin','=',$rv['buss_origin']],
//                        ['med_batchnum','=',$rv['med_batchnum']],
//                    ])
//                    ->group('facname,med_name,med_specs,med_unit,buss_origin,med_batchnum')
//                    ->sum('innums');
//                $repeat_arrs = [];
//                for($i = 0; $i < count($repeat_arr1); $i ++) {
//                    for($j = $i+1; $j < count($repeat_arr1); $j ++) {
//                        if ($repeat_arr1 [$i]['facname'] == $repeat_arr1 [$j]['facname']&&
//                            $repeat_arr1 [$i]['med_name'] == $repeat_arr1 [$j]['med_name']&&
//                            $repeat_arr1 [$i]['med_specs'] == $repeat_arr1 [$j]['med_specs']&&
//                            $repeat_arr1 [$i]['med_unit'] == $repeat_arr1 [$j]['med_unit']&&
//                            $repeat_arr1 [$i]['buss_origin'] == $repeat_arr1 [$j]['buss_origin']&&
////                            $repeat_arr1 [$i]['innums'] != $repeat_arr1 [$j]['innums']&&
//                            $repeat_arr1 [$i]['med_batchnum'] == $repeat_arr1 [$j]['med_batchnum']
//                        ) {
//
//                            $repeat_arr1 [$i]['innums'] +=$repeat_arr1 [$j]['innums'];
////                            $repeat_arrs [] = $repeat_arr1 [$j];
//
////        unset($repeat_arr1[$j]);
//                            break;
//                        }
//                    }
//                }
//            }// foreach结束
//            var_dump($rsda);die;
//            foreach ($repeat_arr as $sk=> $ss){
//                $repeat_arr[$sk]['innums'] = $rsda[$sk];
//                dump($ss);
//            }
//            die;
//            var_dump($repeat_arr);die;

//            $keys = [];
//            for($xx=0;$xx<count($repeat_arr);$xx++){
//                $keys[] = $xx;
//            }
            $repeat_arr = array_values($repeat_arr);
//        var_dump($repeat_arr);die;
            $keyarr =['company_name','names','fac_specs','measuring_unit','origin','batch_num','operation_time','stock_num'];
            foreach ($repeat_arr as $kk=>$vv){
                //查询操作时间
                $fields[] = Db::name('flowofmed')->field('in_time')
                    ->where([
                        ['facname','=',$vv['facname']],
                        ['med_name','=',$vv['med_name']],
                        ['med_specs','=',$vv['med_specs']],
                        ['med_unit','=',$vv['med_unit']],
                        ['buss_origin','=',$vv['buss_origin']],
                        ['med_batchnum','=',$vv['med_batchnum']],
                    ])
                    ->where('in_time','between time',[$time1,$time2])
                    ->find();
                $fields = array_values($fields);
//                var_dump($fields);
                //求销售总量
                $rsdata[] = Db::name('flowofmed')->field('id,innums,facname,med_name,med_specs,med_unit,buss_origin,med_batchnum,in_time,ssid,med_salenum')
                    ->where([
                        ['facname','=',$vv['facname']],
                        ['med_name','=',$vv['med_name']],
                        ['med_specs','=',$vv['med_specs']],
                        ['med_unit','=',$vv['med_unit']],
                        ['buss_origin','=',$vv['buss_origin']],
                        ['med_batchnum','=',$vv['med_batchnum']],
                        ['in_time','between time',[$time1,$time2]]
                    ])
                    ->group('facname,med_name,med_specs,med_unit,buss_origin,med_batchnum')
//                    ->find();
                    ->sum('med_salenum');

                //求入库总量
                $rsda[] = Db::name('temporary_transfer')//->field('id,innums,facname,med_name,med_specs,med_unit,buss_origin,med_batchnum')
                ->where([
                    ['facname','=',$vv['facname']],
                    ['med_name','=',$vv['med_name']],
                    ['med_specs','=',$vv['med_specs']],
                    ['med_unit','=',$vv['med_unit']],
                    ['buss_origin','=',$vv['buss_origin']],
                    ['med_batchnum','=',$vv['med_batchnum']],
                ])
                    ->group('facname,med_name,med_specs,med_unit,buss_origin,med_batchnum')
                    ->sum('innums');
//                dump($rsda[$kk]);
                $repeat_arr[$kk]['operation_time'] = $fields[$kk]['in_time'];

//                $repeat_arr[$kk]['operation_time'] =substr($repeat_arr[$kk]['operation_time'],0,-3);
                #上月时间
                $times[] = date("Y-m-d",strtotime("last month",strtotime($repeat_arr[$kk]['operation_time'])));
                $times2[] = substr($times[$kk],0,-3);
                $times21[] = $times2[$kk].-01;
                $times23[] = date("t",strtotime($times[$kk]));
                $times22[] = $times2[$kk].-$times23[$kk];
//                #求库存上月数据   会出现为空数据?
                $last[] = Db::name('med_reserve')->field('id,operation_time,stock_num')
                    ->where('company_name',$vv['facname'])
                    ->where('names',$vv['med_name'])
                    ->where('fac_specs',$vv['med_specs'])
                    ->where('measuring_unit',$vv['med_unit'])
                    ->where('origin',$vv['buss_origin'])
                    ->where('batch_num',$vv['med_batchnum'])
                    ->where('operation_time','between time',[$times21[$kk],$times22[$kk]])
                    ->find();
                if($last[$kk]==''||$last[$kk]==null){
                    $last[$kk]['stock_num']=0;
                }
                #上月库存加本月库存
                $repeat_arr[$kk]['total']=(int)$last[$kk]['stock_num']+((int)$rsda[$kk]-(int)$rsdata[$kk]);
                $new[] = array_combine($keyarr,$repeat_arr[$kk]);
//                dump(123);
            }
//            var_dump($new);
//            die;
//            dump($repeat_arr);
//            die;
//            var_dump($repeat_arr);
//            var_dump($rsda);
//            var_dump($rsdata);
//            var_dump($rsda);
//            var_dump($fields);
//            die;
//
//            if(!empty($new)){
//                foreach ($new as $kkk=>$vvv){
//                    #求除去重复元素外的其他数据
////                    dump($vvv);//die;
//                    $else1=Db::name('flowofmed')
//                        ->field('facname,med_name,med_specs,med_unit,buss_origin,med_batchnum')
//                        ->where([
//                            ['in_time','between time',[$time1,$time2]],
//                            ['facname','<>',$vvv['company_name']],
//                            ['med_name','<>',$vvv['names']],
//                            ['med_specs','<>',$vvv['fac_specs']],
//                            ['med_unit','<>',$vvv['measuring_unit']],
//                            ['med_batchnum','<>',$vvv['batch_num']],
//                            ['buss_origin','<>',$vvv['origin']]
//                        ])
////                        ->fetchSql(true)
////                        ->where('in_time','between time',[$time1,$time2])
////                        ->where('facname',"<>",$vvv['company_name'])
////                        ->where('med_name','<>',$vvv['names'])
////                        ->where('med_specs','<>',$vvv['fac_specs'])
////                        ->where('med_unit','<>',$vvv['measuring_unit'])
////                        ->where('med_batchnum','<>',$vvv['batch_num'])
////                        ->where('buss_origin','<>',$vvv['origin'])
////                        ->group('facname,med_name,med_specs,med_unit,buss_origin,med_batchnum')
////                        ->having('count(facname)<2','count(med_name)<2')
////                        ->sum('med_salenum');
//                        ->select();
//                }
//            } //die;
//            dump($else1);die;
//            $else1 = $this->array_unique_fb($else1);
////            dump(array_diff($new,$else1));die;
//            if(!empty($else1)){
//                for ($ct=0;$ct<count($else1);$ct++){
//                    $key2[] = $ct;
//                }
//
//                $else1 = array_combine($key2,$else1);
//                foreach ($else1 as $kv => $vk) {
//                    $elsenew[]=Db::name('flowofmed')
//                        ->field('facname,med_name,med_specs,med_unit,buss_origin,med_batchnum,in_time,innums,med_salenum')
//                        ->where('facname',$vk['facname'])
//                        ->where('med_name',$vk['med_name'])
//                        ->where('med_specs',$vk['med_specs'])
//                        ->where('med_unit',$vk['med_unit'])
//                        ->where('buss_origin',$vk['buss_origin'])
//                        ->where('med_batchnum',$vk['med_batchnum'])
//                        ->find();
//
//                    #上月时间
////                $lastelse[] =substr($elsenew[$kv]['in_time'],0,-3);
//                    $lasttimes[] = date("Y-m-d",strtotime("last month",strtotime($elsenew[$kv]['in_time'])));
//                    $lasttimes2[] = substr($lasttimes[$kv],0,-3);
//                    $lasttimes21[] = $lasttimes2[$kv].-01;
//                    $lasttimes23[] = date("t",strtotime($lasttimes[$kv]));
//                    $lasttimes22[] = $lasttimes2[$kv].-$lasttimes23[$kv];
//                    //求非重复数据上月库存
//                    $elsenew2[]=Db::name('med_reserve')
//                        ->where('company_name',$vk['facname'])
//                        ->where('names',$vk['med_name'])
//                        ->where('fac_specs',$vk['med_specs'])
//                        ->where('measuring_unit',$vk['med_unit'])
//                        ->where('origin',$vk['buss_origin'])
//                        ->where('batch_num',$vk['med_batchnum'])
//                        ->where('operation_time','between time',[$lasttimes21[$kv],$lasttimes22[$kv]])
//                        ->find();
//                    $elsenew[$kv]['total'] = $elsenew2[$kv]['stock_num']+($elsenew[$kv]['innums']-$elsenew[$kv]['med_salenum']);
//
//                    unset($elsenew[$kv]['innums']);
//                    unset($elsenew[$kv]['med_salenum']);
//                }
//                foreach ($elsenew as $ek=>$ev){
//                    $new2[] = array_combine($keyarr,$elsenew[$ek]);
//                }
//                $resultarr=array_merge($new,$new2);
//            }else{
//                $resultarr = $new;
//            }
//            dump($resultarr);die;
//var_dump($new);die;
            $medlist = new Medst;
            $res = $medlist->saveAll($new);
            if ($res){
                Db::name('temporary_transfer')->where('id','>',0)->delete();
                return $this->returns(200,'success');
            }else{
                return $this->returns(500,'error');
            }
        }else{
            //暂无
        }
    }

    public function mstEdit(){
        $datas = $this->requests('edit');
//        var_dump($datas);die;
        foreach ($datas[0] as $k=>$v){
            $data[$k] = trim($v);
        }
        $data['update_time']=time();
        $res = (new Medst)->editlist(new Medst,$data);
        if($res){
            return $this->returns(200,'success');
        }
    }
    public function mstAdd(){
        $data = $this->requests('add');
        foreach ($data[0] as $k=>$v){
            $datas[$k] = trim($v);
        }
//        var_dump($datas);die;
        $res = (new Medst)->medstadd($datas);
        if($res){
            return $this->returns(200,'success');
        }
    }
    public function mstDel(){
        $id = $this->request->param('id');
        $flow = new Medst;
        $res =(new Medst)->listdel($flow,$id);
        if ($res){
            return self::returns(200,'删除成功');
        }
    }
    public function serdr(){
        //上传excel文件
        $file = $this->request->file('file');
        $name = date("Ymd") . rand(1000, 99999);
        $today= date("Ymd");
        $info = $file->validate(['fileSize'=>10485760,'fileExt'=>'xls,xlsx'])->move( './uploads/reserve'.'/'.$today . "/", $name);
        require_once '../vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
        require_once '../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
        if($info){
            //获取上传到后台的文件名
            $fileName = $info->getSaveName();
//            var_dump($fileName);die;
            //获取文件路径
            $filePath = Env::get('ROOT_PATH').'public'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'reserve'.DIRECTORY_SEPARATOR.$today.DIRECTORY_SEPARATOR.$fileName;
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

        //获取总列数
        $col_num = $sheet->getHighestColumn();
        $data = []; //数组形式获取表格数据
        $time = time();
        //有标题栏  $i=2; 没有标题栏 $i=1;
        for ($i = 2; $i <= $row_num; $i ++) {
            if($sheet->getCell("A".$i)==''&&$sheet->getCell("B".$i)==''&&$sheet->getCell("C".$i)==''){

            }else {
                if(strlen(trim($sheet->getCell("C".$i)->getValue()))!=10||strstr(trim($sheet->getCell("C".$i)),'.')==true){
                    $data[$i]['operation_time']     =   trim(date("Y-m-d", trim(($sheet->getCell("A".$i)->getValue() - 25569) * 24 * 60 * 60)));
                }else{
                    $data[$i]['operation_time']     =   trim($sheet->getCell("A".$i)->getValue());
                }
                $data[$i]['company_name'] = trim($sheet->getCell("B" . $i)->getValue());
                $data[$i]['names'] = trim($sheet->getCell("C" . $i)->getValue());
                $data[$i]['fac_specs'] = trim($sheet->getCell("D" . $i)->getValue());
                $data[$i]['measuring_unit'] = trim($sheet->getCell("E" . $i)->getValue());
                $data[$i]['origin'] = trim($sheet->getCell("F" . $i)->getValue());
                $data[$i]['stock_num'] = trim((int)$sheet->getCell("G" . $i)->getValue());
                $data[$i]['batch_num'] = trim($sheet->getCell("H" . $i)->getValue());
                $data[$i]['create_time'] = $time;
                $data[$i]['update_time'] = $time;
            }
        }
        //将数据保存到数据库
        $res = Db::name('med_reserve')->insertAll($data);

        if($res){
            return $this->returns(200,'导入成功');
        }
    }


}