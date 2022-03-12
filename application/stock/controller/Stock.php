<?php
namespace app\stock\controller;

use app\mainmenu\controller\Common;
use app\stock\model\Goods;
use app\stock\model\StockDirection;
use think\Db;
use think\facade\Cache;
use think\facade\Request;
use think\facade\Env;
use PHPExcel;

/**
 * createBy: phpstorm
 * date: 2021-11-17
 * time: 09:42
 */
class Stock extends Common
{
    protected function initialize()
    {
        parent::initialize();
    }
    public function goodslist($where=[]){
        $goods = new Goods;
        if($this->request->isget()){
//            Cache::rm('data');
            if(Cache::get('data')){
                $data = Cache::get('data');
            }else{
                $data = $goods->getlistgoods($where);
                Cache::set('data',$data,60 * 60);
            }
//            var_dump($data);die;
            return json(['code'=>1,'message'=>'success','data'=>$data]);
        }else{
            $datas = $this->request->param('name');
            $data = Goods::where('name','like','%'.$datas.'%')->select()->toArray();
            if($data){
                return json(['code'=>1,'message'=>'success','data'=>$data]);
            }else{
                return json(['code'=>2,'message'=>'抱歉，暂无该药品','data'=>null]);
            }
        }
    }

    /**
     * @param string $del
     */
   public function goodsdel($del='删除成功'){
        $id = $this->request->param('id');
        if($id){
            $res =(new Goods)->listdelone($id);
            if($res){
                Cache::rm('data');
                Cache::rm('dire_data');
                return self::returns(1,$del,null);
            }else{
                $del='删除失败';
                return self::returns(2,$del,null);
            }
        }else{
            return $this->error('请求失败');
        }
   }

    /**
     * 库存导入报表
     * 
     */
    public function dr(){
        //上传excel文件
        $file = $this->request->file('file');
        $name = date("Ymd") . rand(1000, 9999);
        $today= date("Ymd");
        $info = $file->validate(['fileSize'=>10485760,'fileExt'=>'xls,xlsx'])->move( './uploads/stock'.'/'.$today . "/", $name);
        require_once '../vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
        require_once '../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
        if($info){
            //获取上传到后台的文件名
            $fileName = $info->getSaveName();
            //获取文件路径
            $filePath = Env::get('ROOT_PATH').'public'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'stock'.DIRECTORY_SEPARATOR.$today.DIRECTORY_SEPARATOR.$fileName;
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
        $res = Db::name('goods')->insertAll($data);
        if($res){
            Cache::rm('data');
            Cache::rm('dire_data');
            return $this->returns(200,'导入成功');
        }
    }
    /**
     * 库存列表编辑
     */
   public function goodsedit(){
        $datas = $this->requests('edit');

       $fieldarr =['id','lotnumber','number','name','brand','prescription','specs','library_number'];
       $goods =new Goods;
        if(count($datas)>8){
            $rs = array_slice($datas,0,8);
            $data = array_combine($fieldarr,$rs);
            switch ($data['prescription']){
                case '非处方药' : $data['prescription']=2;
                    break;
                case "处方药" : $data['prescription']=1;
                    break;
            }
            $res = $goods->listedit($data);
            exit;
        }else{
            $data = array_combine($fieldarr,$datas);
            switch ($data['prescription']){
                case '非处方药' : $data['prescription']='2';
                    break;
                case "处方药" : $data['prescription']='1';
                    break;
            }
            $data['update_time']=time();
            $res = $goods->listedit($data);
        }
        if($res){
            Cache::rm('data');
            Cache::rm('dire_data');
            return $this->returns(1,'success',$data=null);
        }
   }
   public function goodsadd(){
        $datas = $this->requests('add');
        //判断提交字段非空
//        foreach ($datas as $k=>$v){
//            if($v==' '||$v==''||$v==null){
//               return $this->returns(500,'请填写内容后再试!');
//               die;
//            }
//        }
        $fieldarr =['lotnumber','number','name','brand','prescription','specs','composition','library_number'];
        $data = array_combine($fieldarr,$datas);
        $data['create_time']=time();
            $goods = new Goods;
            $stock = new StockDirection;
            $res = (new Goods)->listadd($data);
            $res1 = $stock->save(['goods_id'=>$res,'name'=>$data['name'],'specs'=>$data['specs']]);
            if($res){
                Cache::rm('data');
                Cache::rm('dire_data');
                return $this->returns(200,'success');
            }
   }
   //库存流水列表 
   public function direction(){
        if(Request::isget()){
//            Cache::clear();
            if(Cache::get('dire_data')){
                $data = Cache::get('dire_data');
            }else{
                $data = (new StockDirection)->getstocklist();
                Cache::set('dire_data',$data,60 * 60 * 24);
            }
            return json(['code'=>200,'message'=>'success','data'=>$data]);
        }else{
            $data = $this->requests();
            $res = (new StockDirection)->alias('a')->leftjoin('goods b','a.goods_id=b.id')->
            where('a.goods_id','=',$data['where'])->whereOr('a.name','like','%'.$data['where'].'%')
            ->whereOr('a.com_company','like','%'.$data['where'].'%')->whereOr('a.customera','like','%'.$data['where'].'%')
            ->whereOr('a.customerb','like','%'.$data['where'].'%')->whereOr('supplier','like','%'.$data['where'].'%')
            ->whereOr('a.origin_place','like','%'.$data['where'].'%')->
            field('a.*')->order('a.id','desc')->select()->toArray();
            return $this->returns(200,'success',$res);
        }
   }
   //库存流水表编辑
   public function direedit(){
       $data = $this->requests('edit');
//       var_dump($data);die;
       $stock_dire =new StockDirection;
//       $goods = new Goods;
       $direarr['id'] = $data[0]['id'];
       $direarr['com_company'] = $data[0]['com_company'];
       $direarr['in_num'] = $data[0]['in_num'];
       $direarr['goods_price'] = $data[0]['goods_price'];
       $direarr['customera'] = $data[0]['customera'];
       $direarr['customerb'] = $data[0]['customerb'];
       $direarr['supplier'] = $data[0]['supplier'];
       $direarr['local_company'] = $data[0]['local_company'];
       $direarr['origin_place'] = $data[0]['origin_place'];
       $direarr['name'] = $data[0]['name'];
       $direarr['specs'] = $data[0]['specs'];
       $direarr['unit'] = $data[0]['unit'];
       $direarr['lotnumber'] = $data[0]['lotnumber'];
       $direres = $stock_dire->where('id',$direarr['id'])->update($direarr);
       Cache::rm('data');
       Cache::rm('dire_data');
       if($direres){
           return $this->returns(200,'success');
       }
   }
    /**
     * 库存流水删除
     * @param string $del
     */
    public function diredel($del='删除成功'){
        $id = $this->request->param('id');
//        var_dump($id);die;
        if($id){
            $res =(new StockDirection)->stdel($id);
            if($res){
                Cache::rm('dire_data');
                return self::returns(200,$del,null);
            }else{
                $del='删除失败';
                return self::returns(500,$del,null);
            }
        }else{
            return $this->error('请求失败');
        }
    }
    /**
     * 库存流水导入报表
     * 目前还没写相关逻辑 
     */
    public function stockdr(){
        //上传excel文件
        $file = $this->request->file('file');
        $info = $file->validate(['fileSize'=>10485760,'fileExt'=>'xls,xlsx'])->move( './uploads/stockdire');
        require_once '../vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
        require_once '../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
        if($info){
            //获取上传到后台的文件名
            $fileName = $info->getSaveName();
            //获取文件路径
            $filePath = Env::get('ROOT_PATH').'public'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'stockdire'.DIRECTORY_SEPARATOR.$fileName;
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
        for ($i = 2; $i <= $row_num; $i ++) {
            $data[$i]['com_company']  = $sheet->getCell("A".$i)->getValue();
            $data[$i]['create_time']  = time();
            $data[$i]['name'] = $sheet->getCell("C".$i)->getValue();
            $data[$i]['specs'] = $sheet->getCell("D".$i)->getValue();
            $data[$i]['unit'] = $sheet->getCell("E".$i)->getValue();
            $data[$i]['in_num'] = $sheet->getCell("F".$i)->getValue();
            $data[$i]['lotnumber'] = $sheet->getCell("G".$i)->getValue();
            $data[$i]['goods_price'] = $sheet->getCell("H".$i)->getValue();
            $data[$i]['customera'] = $sheet->getCell("I".$i)->getValue();
            $data[$i]['customerb'] = $sheet->getCell("J".$i)->getValue();
            $data[$i]['supplier'] = $sheet->getCell("K".$i)->getValue();
            $data[$i]['local_company'] = $sheet->getCell("L".$i)->getValue();
            $data[$i]['origin_place'] = $sheet->getCell("M".$i)->getValue();
//            $time = date('Y-m-d H:i',\PHPExcel_Shared_Date::ExcelToPHP($sheet->getCell("B".$i)->getValue()));//将excel时间改成可读时间
//            $data   [$i]['time'] = strtotime($time);
            //将数据保存到数据库
        }

        $res = Db::name('stock_direction')->insertAll($data);
        if($res){
            Cache::rm('data');
            Cache::rm('dire_data');
            return $this->returns(200,'导入成功');
        }
    }
    
    public function direadd(){
        $data = $this->requests();
        var_dump($data);die;
    }
   public function acc(){
       $data = $this->requests();
       var_dump($data);die;
   }
    public function add(){
       $data=[
           'com_pany'=>'测试',
           'goods_id'=>159,
           'in_num'=>50,
           'goods_price'=>565,
           'customera'=>'客户a',
           'customerb'=>'客户b',
           'supplier'=>'供应商',
           'local_conpamy'=>'公司',
           'origin_place'=>'产地'
       ];
       for ($i=0;$i<10;$i++){
          $s =  new StockDirection;
          $s->save($data);
       }
    }
    public function goodsadds(){
        $data = [
            'lotnumber'=>1,
            'number'=>1001,
            'name'=>'三九胃泰',
            'brand'=>999,
            'specs'=>'6 * 2',
            'prescription'=>1,
            'class'=>2,
            'composition'=>'成分',
            'color'=>'白色'
        ];
        for ($i=0;$i<20;$i++){
            $s =  new Goods;
            $s->save($data);
        }
    }
    public function dels(){
        (new StockDirection)::where('id','>',40)->delete();;
    }
}