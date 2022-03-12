<?php
namespace app\mainmenu\controller;
use app\mainmenu\controller\Common;
use PHPExcel;
use app\mainmenu\model\Excexport as eX;
use think\Db;
use think\facade\Env;

class ExcOperation extends Common
{
    protected function initialize()
    {
        parent::initialize();
    }
    public function dc(){
        $pro = new eX();
        $list = $pro->field('id,ttitle,tcontent,tnum')->selectOrFail();
        $xlsName = "用户表"; // 文件名
        $xlsCell = [        // 列名
            ['id', '序号'],
            ['ttitle', '标题'],
            ['tcontent', '分类id'],
            ['tnum', '图片路径']

        ];// 表头信息
        $pro->downloadExcel($xlsName, $xlsCell, $list);// 传递参数
    }
    public function dr(){
        //上传excel文件
        $file = $this->request->file('file');

        $info = $file->validate(['fileSize'=>10485760,'fileExt'=>'xls,xlsx'])->move( './uploads');
        require_once '../vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
        require_once '../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
        if($info){
            //获取上传到后台的文件名
            $fileName = $info->getSaveName();
            //获取文件路径
            $filePath = Env::get('ROOT_PATH').'public'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.$fileName;
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
        $data = []; //数组形式获取表格数据
        for ($i = 1; $i <= $row_num; $i ++) {
            $data[$i]['ttitle']  = $sheet->getCell("A".$i)->getValue();
            $data[$i]['tcontent']  = substr($sheet->getCell("B".$i)->getValue(),-6);
            $data[$i]['tnum'] = $sheet->getCell("C".$i)->getValue();
//            $time = date('Y-m-d H:i',\PHPExcel_Shared_Date::ExcelToPHP($sheet->getCell("B".$i)->getValue()));//将excel时间改成可读时间
//            $data   [$i]['time'] = strtotime($time);
            //将数据保存到数据库
        }

        $res = Db::name('excexport')->insertAll($data);
    }

}