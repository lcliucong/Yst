<?php
namespace app\psi\controller;
use app\admin\validate\shopro\Goods;
use think\facade\Cache;
use think\Db;
use PHPExcel;
use think\facade\Env;
use app\psi\model\Psi;
use think\facade\Request;
use app\psi\model\PsiOne;
use app\psi\model\PsiTwo;
use app\mainmenu\controller\Common;

class Reportpsi extends Common
{
    /**
     * createBy phpstorm
     * auth : lc
     * Date : 2021/11/22
     * Time : 14:27
     */
    protected function initialize()
    {
        parent::initialize();
    }

    /**
     * $name
     * $b
     * $where
     * $data
     * $field
     */
    public function psilist(){
        $psi = new Psi;
        if(Request::isGet()){
            $data = (new Psi)->lists('',$psi,'goods');
            return $this->returns(200,'success',$data);
        }else{
            //产品，商业单位，代理商
            $data = $this->requests(); //$data['name']
//            $field['company|agent'] = array('like', '%' . $data   . '%');
//            var_dump($map);die;
            $res = (new Psi)->selpsi($psi,'goods',$where=[],$data['name'],$field='');
            return $this->returns(200,'success',$res);
        }
    }
    public function psidel($del ='删除成功'){
            $id = $this->request->param('id');
            $psi = new Psi;
            if($id){
                $res =(new Psi)->listdel($psi,$id);
                if($res){
                    return self::returns(200,$del,null);
                }else{
                    $del='删除失败';
                    return self::returns(500,$del,null);
                }
            }else{
                return $this->error('请求失败');
            }
    }
    public function psiadd(){
        $data = $this->requests('add');
        foreach ($data[0] as $k=>$v){
            $datas[$k] = trim($v);
        }
        if($datas['goods_id']==0||$datas['goods_id']==''){
            return $this->returns(500,'请选择药品后在尝试添加');
        }
        $datas['thismonth'] = $datas['goods_num'] + $datas['goods_num2'];
        $res = (new Psi)->addlist($datas);
        if($res){
            return $this->returns(200,'success');
        }
    }
    public function psiedit(){
        $datas = $this->requests('edit');
        $data =$datas[0];
        $data['thismonth'] = $data['goods_num']+$data['goods_num2'];
        $res = (new Psi)->editlist(new Psi,$data);
        if($res){
            return $this->returns(200,'success');
        }
    }

    public function psidr(){
        //上传excel文件
        $file = $this->request->file('file');
        $name = date("Ymd") . rand(1000, 99999);
        $today= date("Ymd");
        $info = $file->validate(['fileSize'=>10485760,'fileExt'=>'xls,xlsx'])->move( './uploads/psi'.'/'.$today . "/", $name);
        require_once '../vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
        require_once '../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
        if($info){
            //获取上传到后台的文件名
            $fileName = $info->getSaveName();
//            var_dump($fileName);die;
            //获取文件路径
            $filePath = Env::get('ROOT_PATH').'public'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'psi'.DIRECTORY_SEPARATOR.$today.DIRECTORY_SEPARATOR.$fileName;
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
        $data1 = [];
        $time = time();
        //有标题栏  $i=2; 没有标题栏 $i=1;
        for ($i = 2; $i <= $row_num; $i ++) {
            $data1[$i]['name']= $sheet->getCell("A".$i)->getValue();
            $data1[$i]['specs'] = $sheet->getCell("B".$i)->getValue();
            $data1[$i]['create_time'] = $time;
            $data[$i]['name']= $sheet->getCell("A".$i)->getValue();
            $data[$i]['specs'] = $sheet->getCell("B".$i)->getValue();
            $data[$i]['thismonth']  = $sheet->getCell("C".$i)->getValue();
            $data[$i]['company']  = $sheet->getCell("D".$i)->getValue();
            $data[$i]['goods_num'] = $sheet->getCell("E".$i)->getValue();
            $data[$i]['agent'] = $sheet->getCell("F".$i)->getValue();
            $data[$i]['goods_num2'] = $sheet->getCell("G".$i)->getValue();
            $data[$i]['remarks'] = $sheet->getCell("H".$i)->getValue();
            $data[$i]['create_time'] = $time;
        }
        //将数据保存到数据库
        $counts = Db::name('goods')->count('id');
        $psicounts = Db::name('psi')->count('id');
        $res = Db::name('goods')->insertAll($data1);
        $res1 = Db::name('psi')->insertAll($data);
        for($i=0;$i<=$res;$i++){
            $goodsid = Db::name('goods')->field('id')->limit($counts ,$res)->select();
            $psiid = Db::name('psi')->field('id')->limit($psicounts,$res)->select();
        }
        foreach ($goodsid as $k=>$v){
            $ids[] = $v['id'];
        }
        foreach ($psiid as $k1=>$v1){
            $psiids[]=$v1['id'];
       }
        for($j=0;$j<$res;$j++){
            (new Psi)::where('id',$psiids[$j])->update(['goods_id' =>$ids[$j]]);
        }
        if($res){
            Cache::rm('data');
            return $this->returns(200,'导入成功');
        }
    }
    public function psionelist(){
        $psione = new PsiOne;
        if(Request::isGet()){
            //可直接调用ModelBase方法
//            $data = (new PsiOne)->getlist('',$psione,'goods');
            //调用model中方法
            $data = (new PsiOne)->listone('',$psione,'goods');
            return $this->returns(200,'success',$data);
        }else{
            $data = $this->requests();
            //或者直接调用ModelBase方法
//            $field='a.class_one,a.terminal_name,a.person,a.company';
//            $res = (new PsiOne)->sellist($psione,'goods',$where=[],$data['name'],$field='');
            $res = (new PsiOne)->selpsione($psione,'goods',$where=[],$data['name'],$field='');
            return $this->returns(200,'success',$res);
        }
    }

    /**
     * @param string $del
     * @return mixed
     */
    public function psionedel($del='删除成功'){
        $id = $this->request->param('id');
        $psione = new PsiOne;
        if($id){
            $res =(new PsiOne)->listdel($psione,$id);
            if($res){
                return self::returns(200,$del,null);
            }else{
                $del='删除失败';
                return self::returns(500,$del,null);
            }
        }else{
            return $this->error('请求失败');
        }
    }
    public function psioneadd(){
        $data = $this->requests('add');
        foreach ($data[0] as $k=>$v){
            $datas[$k] = trim($v);

        }
        if($datas['goods_id']==0||$datas['goods_id']==''){
            return $this->returns(500,'请选择药品后在尝试添加');
        }
        $res = (new PsiOne)->addlist($datas);
        if($res){
            return $this->returns(200,'success');
        }
    }
    public function psioneedit(){
        $datas = $this->requests('edit');
        $data =$datas[0];
        $res = (new PsiOne)->editlist(new PsiOne,$data);
        if($res){
            return $this->returns(200,'success');
        }
    }
    public function psitwolist(){
        $psitwo = new PsiTwo;
        if(Request::isGet()){
            $data = (new PsiTwo)->listtwo('',$psitwo,'goods');
            return self::returns(200,'success',$data);
        }else{
            $data = $this->requests();
              //或者直接调用ModelBase方法
//            $field='a.distrib_one,a.terminal_name,a.responsibler,a.business_unit';
//            $res = (new PsiTwo)->sellist($psitwo,'goods',$where=[],$data['name'],$field='');
            $res = (new PsiTwo)->selpsitwo($psitwo,'goods',$where='',$data['name'],$field='');
            return self::returns(200,'success',$res);
        }
    }
    /**
     * @param string $del
     * @return mixed
     */
    public function psitwodel($del='删除成功'){
        $id = $this->request->param('id');
        $psitwo = new PsiTwo;
        if($id){
            $res =(new PsiTwo)->listdel($psitwo,$id);
            if($res){
                return self::returns(200,$del,null);
            }else{
                $del='删除失败';
                return self::returns(500,$del,null);
            }
        }else{
            return $this->error('请求失败');
        }
    }
    public function psitwoadd(){
        $data = $this->requests('add');
        foreach ($data[0] as $k=>$v){
            $datas[$k] = trim($v);
        }
        if($datas['goods_id']==0||$datas['goods_id']==''){
            return $this->returns(500,'请选择药品后在尝试添加');
        }
        $res = (new PsiTwo)->addlist($datas);
        if($res){
            return $this->returns(200,'success');
        }
    }
    public function psitwoedit(){
        $datas = $this->requests('edit');
        $data =$datas[0];
        $res = (new PsiTwo)->editlist(new PsiTwo,$data);
        if($res){
            return $this->returns(200,'success');
        }
    }
}