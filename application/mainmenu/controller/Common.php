<?php
namespace app\mainmenu\controller;

use think\Controller;
use app\mainmenu\model\Mainmenu as mM;
use think\Db;
use think\facade\Hook;
use think\Request;
use think\Route;
use app\common\Common as ControllerBase;

class Common extends ControllerBase
{


    protected $resultSetType = 'collection';
    protected function initialize()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods:POST,GET,OPTIONS,DELETE,PUT'); // 允许请求的类型
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Headers:x-requested-with,Content-Type,X-CSRF-Token');
        header('Access-Control-Allow-Headers: *');
    }
    public function test(){
        var_dump('test success!');
    }
    public function requests($field=''){
        $rr = $this->request->param($field);
        return $rr;
    }
    /**
     * @param $data
     *
     * dump vdump p_r
     *
     */
    public function p($data){
        // 定义样式
        $str=   '<pre style="display: block;
                padding: 9.5px;margin: 50px auto 0 auto;
                font-size: 15px;width:800px;
                line-height: 1.42857;color: #F2F2F2;
                word-break: break-all;word-wrap: break-word;
                background-color: #336699;font-family: 幼圆;
                border: 1px solid #CCC;border-radius: 5px;">';
        // 如果是boolean或者null直接显示文字；否则print
        if (is_bool($data)) {
            $show_data=$data
            ?
            'true'
            :
            'false';

        }elseif (is_null($data)) {

            $show_data='null';

        }else{
            $show_data=print_r($data,true);
        }
        $str.=$show_data;
        $str.='</pre>';
        echo $str;
    }

    /**
     * @param int $code
     * @param string $msg
     * @param array $data
     * @return false|string
     *
     * return
     */
    public function returnres($code=0,$msg="",$data){
        return json_encode(array(
            "code"=>$code,
            "message"=>$msg,
            "data"=>$data
        ));
    }
    public function returns($code=0,$msg="",$data=null){
        return json(array(
            "code"=>$code,
            "message"=>$msg,
            "data"=>$data
        ));
    }
    public function returnplus($code=0,$msg="",$data=null,$count=0){
        return json(array(
            "code"=>$code,
            "message"=>$msg,
            "data"=>$data,
            "count"=>$count
        ));
    }
    /**
     *
     * toArray()
     *
     */
    public function toarr($ar){
        $isarr = is_object($ar) ? get_object_vars($ar) : $ar;
        $toarr = array();
        foreach ($isarr as $k=>$v){
            $v = (is_array($v) || is_object($v)) ? $this->toarr($v) : $v;
            $toarr[$k] = $v;
        }
        return $toarr;
    }
    /**
     * 库存导入报表
     *
     */
    public function excdr($name){
        //上传excel文件
        $file = $this->request->file('file');
        $info = $file->validate(['fileSize'=>10485760,'fileExt'=>'xls,xlsx'])->move( './uploads/stock');
        require_once '../vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
        require_once '../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
        if($info){
            //获取上传到后台的文件名
            $fileName = $info->getSaveName();
            //获取文件路径
            $filePath = Env::get('ROOT_PATH').'public'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'stock'.DIRECTORY_SEPARATOR.$fileName;
            //获取文件后缀
            $suffix = $info->getExtension();
            //判断哪种类型
            if($suffix=="xlsx"){
                $reader =  \PHPExcel_IOFactory::createReader('Excel2007');
            }else{
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
        //a-z
        $az = [                           // 所有原生名
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N',
            'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
        ];
        $data = []; //数组形式获取表格数据
        for ($i = 1; $i <= $row_num; $i ++) {
            $data[$i]['lotnumber']  = $sheet->getCell("A".$i)->getValue();
            $data[$i]['number']  = $sheet->getCell("B".$i)->getValue();
            $data[$i]['name'] = $sheet->getCell("C".$i)->getValue();
            $data[$i]['brand'] = $sheet->getCell("D".$i)->getValue();
            $data[$i]['specs'] = $sheet->getCell("E".$i)->getValue();
            $data[$i]['prescription'] = $sheet->getCell("F".$i)->getValue();
            $data[$i]['class'] = $sheet->getCell("G".$i)->getValue();
            $data[$i]['composition'] = $sheet->getCell("H".$i)->getValue();
            $data[$i]['color'] = $sheet->getCell("I".$i)->getValue();
            $data[$i]['library_number'] = $sheet->getCell("J".$i)->getValue();
            $data[$i]['sales'] = $sheet->getCell("K".$i)->getValue();
//            $time = date('Y-m-d H:i',\PHPExcel_Shared_Date::ExcelToPHP($sheet->getCell("B".$i)->getValue()));//将excel时间改成可读时间
//            $data   [$i]['time'] = strtotime($time);
            //将数据保存到数据库
        }
        $res = Db::name("$name")->insertAll($data);
        if($res){
            Cache::rm('data');
            Cache::rm('dire_data');
            return $this->returns(200,'导入成功');
        }
    }
}


?>