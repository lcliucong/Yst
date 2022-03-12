<?php
namespace app\mainmenu\model;
use app\mainmenu\model\ModelBase;
use PHPExcel;
class Excexport extends ModelBase
{

    protected function initialize()
    {
        parent::initialize();
        
    }
    public function downloadExcel($Title, $CellNameList, $TableData){
        $xlsTitle    = iconv('utf-8', 'gb2312', $Title);  // excel标题
        $fileName    = $Title;                  // 文件名称
        $cellNum     = count($CellNameList);    // 单元格名 个数
        $dataNum     = count($TableData);       // 数据 条数
        require_once '../vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
        require_once '../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
        $obj = new PHPExcel();
        $originCell = [                           // 所有原生名
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N',
            'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
        ];

        //getActiveSheet(0) 获取第一张表
        $obj->getActiveSheet(0)
            ->mergeCells('A1:' . $originCell[$cellNum - 1] . '1');       //合并单元格A1-F1 变成新的A1

        $obj->getActiveSheet(0)->setCellValue('A1', $fileName);      // 设置第一张表中 A1的内容

        for ($i = 0; $i < $cellNum; $i++) {                                     // 设置第二行 ,值为字段名
            $obj->getActiveSheet(0)
                ->setCellValue($originCell[$i] . '2', $CellNameList[$i][1]);      //设置 A2-F2 的值
        }

        // Miscellaneous glyphs, UTF-8  循环写入数据
        for ($i = 0; $i < $dataNum; $i++) {
            for ($j = 0; $j < $cellNum; $j++) {                         // 设置第三行 ,每一行为 数据库一条数据
                $obj->getActiveSheet(0)                                 // 设 A3 值, 值为$TableData[0]['id']
                ->setCellValue($originCell[$j] . ($i + 3), $TableData[$i][$CellNameList[$j][0]]);
            }
        }
        //居中
        $obj->getActiveSheet(0)->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        ob_end_clean();//这一步非常关键，用来清除缓冲区防止导出的excel乱码
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $xlsTitle . '.xlsx"');
        header("Content-Disposition:attachment;filename=$fileName.xlsx");   //"xls"参考下一条备注
        $objWriter = \PHPExcel_IOFactory::createWriter($obj, 'Excel2007');

        //"Excel2007"生成2007版本的xlsx，"Excel5"生成2003版本的xls 调用工厂类
        return $objWriter->save('php://output');
    }

    
}