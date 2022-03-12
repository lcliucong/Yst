<?php
namespace app\delivery\controller;
use think\Db;
use PHPExcel;
use think\facade\Env;
use think\facade\Request;
use app\mainmenu\controller\Common;
use app\delivery\model\Delivery as De;

class Delivery extends Common{
    /**
     * createBy phpstorm
     * auth : lc
     * Date : 2021/11/25
     * Time : 15:40
     */
    protected function initialize()
    {
        parent::initialize();
    }
    public function deliveryList(){
        if(Request::isGet()){
          $res = (new De)->delist();
          return self::returns(200,'success',$res);
        }else{
            $data = $this->requests();
            $field='fac_name,fac_specs,fac_num,fac_price,total_price,batch_num,business_unit';
            $res = (new De)->deselects($data,$field);
            if($res[1]=='nosums'){
                unset($res[1]);
//                unset($res[2]);
                foreach ($res[0] as $k=>$v){
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
                if(empty($resultarr)){
                    $resultarr = [];
                }
                return $this->returns(200,'success',$resultarr);
            }else{
                foreach ($res[0] as $k=>$v){
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
                if(empty($resultarr)){
                    $resultarr = [];
                }
                $sums = $res[1];
//                $sums['fac_num'] = $res[1];
//                dump($sums);die;
                if(!empty($resultarr)){
                    array_push($resultarr,$sums);
                }
//                dump($res);die;
                return $this->returns(200,'success',$resultarr);
            }
        }
    }

    /**
     * @return mixed
     * 商业发货报表导入
     */
    public function deliveryImport(){
        ini_set('memory_limit','1024M');
        set_time_limit(0);
        //上传excel文件
        $file = $this->request->file('file');
        $name = date("Ymd") . rand(1000, 99999);
        $today= date("Ymd");
        $info = $file->validate(['fileSize'=>10485760,'fileExt'=>'xls,xlsx'])->move( './uploads/delivery'.'/'.$today . "/", $name);
        require_once '../vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
        require_once '../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
        if($info){
            //获取上传到后台的文件名
            $fileName = $info->getSaveName();
            //获取文件路径
            $filePath = Env::get('ROOT_PATH').'public'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'delivery'.DIRECTORY_SEPARATOR.$today.DIRECTORY_SEPARATOR.$fileName;
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
            if($sheet->getCell("B".$j)->getValue()==''&&$sheet->getCell("C".$j)->getValue()==''){
                continue;
            }else{
                $row_nums++;
            }
        }

        //获取总列数
        $col_num = $sheet->getHighestColumn();
        $data = []; //数组形式获取表格数据
        $time = time();
        //有标题栏  $i=2; 没有标题栏 $i=1;
        for ($i = 2; $i <= $row_nums; $i ++) {
            if(trim($sheet->getCell("A".$i)->getValue())==''&&trim($sheet->getCell("B".$i)->getValue())==''&&trim($sheet->getCell("C".$i)->getValue())==''){
            }else{
                if(strlen(trim($sheet->getCell("A".$i)->getValue()))<8){
                    $data[$i]['month_time']     =   trim(date("Y-m-d", trim(($sheet->getCell("A".$i)->getValue() - 25569) * 24 * 60 * 60)));
                }else{
                    $data[$i]['month_time']     =   trim($sheet->getCell("A".$i)->getValue());
                }
                $data[$i]['factory_name']   =   trim($sheet->getCell("B".$i)->getValue());
                $data[$i]['fac_name']       =   trim($sheet->getCell("C".$i)->getValue());
                $data[$i]['fac_specs']      =   trim($sheet->getCell("D".$i)->getValue());
                $data[$i]['fac_num']        =   trim((int)$sheet->getCell("E".$i)->getValue());
                $data[$i]['fac_price']      =   trim(round($sheet->getCell("F".$i)->getValue(),2));
                $data[$i]['total_price']    =   round(trim((int)$sheet->getCell("E".$i)->getValue())*trim(round($sheet->getCell("F".$i)->getValue(),2)),2);
                $data[$i]['batch_num']      =   trim($sheet->getCell("H".$i)->getValue());
                $data[$i]['business_unit']  =   trim($sheet->getCell("I".$i)->getValue());
                $data[$i]['create_time']    =   $time;
                $data[$i]['update_time']    =   $time;
            }
        }
//        dump($data);die;
        //将数据保存到数据库;
//        $res = Db::name('delivery')->insertAll($data);
        $de = new De;
        $res = $de->saveAll($data);
        if($res){
            return $this->returns(200,'导入成功');
        }else{
            return $this->returns(500,'error');
        }
    }

    /**
     * 报表导出
     */
    public function deliveryExport(){

    }

    /**
     * 删除
     */
    public function deliveryDel(){
        $id = $this->request->param('id');
        $de = new De;
        $res =(new De)->listdel($de,$id);
        if ($res){
            return self::returns(200,'删除成功');
        }
    }
    
    /**
     * 添加
     */
    public function deliveryAdd(){
        $data = $this->requests('add');
        if (empty($data[0]['month_time'])){
            $data[0]['month_time']='1111-11-11';
        }
        if (empty($data[0]['invoice_date'])){
            $data[0]['invoice_date']='1111-11-11';
        }
        if (empty($data[0]['collection_date'])){
            $data[0]['collection_date']='1111-11-11';
        }
        foreach ($data[0] as $k=>$v){
            $datas[$k] = trim($v);
        }

        $res = (new De)->deadd($datas);
        if($res){
            return $this->returns(200,'success');
        }
    }

    /**
     * 编辑
     */
    public function deliveryEdit(){
        $datas = $this->requests('edit');
        if (empty($datas[0]['month_time'])){
            $datas[0]['month_time']='1111-11-11';
        }
        if (empty($datas[0]['invoice_date'])){
            $datas[0]['invoice_date']='1111-11-11';
        }
        foreach ($datas[0] as $k=>$v){
            $data[$k] = trim($v);
        }
        $data['update_time']=time();
        $res = (new De)->editlist(new De,$data);
        if($res){
            return $this->returns(200,'success');
        }
    }

    /**
     * 测试添加
     */
    public function addddd(){

            $data=[
                'factory_name'=>'贵阳德昌祥药业有限公司',
            ];
            for ($i=0;$i<10;$i++){
                $s =  new De;
                $s->save($data);
            }

}
}