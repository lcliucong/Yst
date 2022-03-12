<?php
namespace app\admin\controller;
use think\Controller;
use app\common\Common;
use think\facade\Cache;
use think\Db;
class Yusuanbu extends Common{
    public function yusuanbu(){
        $userid=input('userid');
        

        $data=db('yusuanbu')->select();
        if($data){

            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';

            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'查看了预算部';
            if(!cache($useridcaozuo)==$caozuo['data']){
                $this->caozuojilu($caozuo);
                cache($useridcaozuo,$caozuo['data']);
            }else{
                //相同不添加;
            }
            return json(['code'=>200,'message'=>'成功','data'=>$data]);
        }else{
            return json(['code'=>0,'message'=>'未查询到']);
        }
    }
    public function yusuanbuadd(){
        $data["jine"] =input('jine');
        $data["yuefen"] =input('yuefen');
        $data["diqumingcheng"] =input('diqumingcheng');
        $data["bumenjingli"] =input('bumenjingli');
        $data["yewuyuan"] =input("yewuyuan");
        $data["yiyuanmingcheng"] =input('yiyuanmingcheng');
        $data["zhongduanjibie"] =input('zhongduanjibie');
        $data["gonghuodanwei"]=input('gonghuodanwei');
        $data["pinming"] =input('pinming');
        $data["guige"] =input('guige');
        $data["shangyueyushu"]=input('shangyueyushu');
        $data["benyuejinhuo"] =input('benyuejinhuo');
        $data["benyuexiaoshou"] =input('benyuexiaoshou');
        $data["benyueyushu"] =input('benyueyushu');
        $data["shangyegonghuojia"] =input('shangyegonghuojia');
        $data["jinglijiangjin"] =input('jinglijiangjin');
        $data["daibiaojiangjinticheng"] =input('daibiaojiangjinticheng');
        $data["daibiaojiangjin"] =input('daibiaojiangjin');
        $data["lunwenfei"] =input('lunwenfei');
        $data["wanchengjine"] =input('wanchengjine');
        $data["renwu"] =input('renwu');
        $data["wanchenglv"] =input('wanchenglv');
        $data["beizhu"] =input('beizhu');
        $data["jinglijiangjindanjia"] =input('jinglijiangjindanjia');
        $rel=db('yusuanbu')->insert($data);
        if($rel){
            $userid=input('userid');
            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'添加了预算部数据';
            $this->caozuojilu($caozuo);
            cache($useridcaozuo,$caozuo['data']);
            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'未添加']);
        }
    }
    public function yusuanbuedit(){
    $data["id"] =input('id');
    $data["jine"] =input('jine');
    $data["yuefen"] =input('yuefen');
    $data["diqumingcheng"] =input('diqumingcheng');
    $data["bumenjingli"] =input('bumenjingli');
    $data["yewuyuan"] =input("yewuyuan");
    $data["yiyuanmingcheng"] =input('yiyuanmingcheng');
    $data["zhongduanjibie"] =input('zhongduanjibie');
    $data["gonghuodanwei"]=input('gonghuodanwei');
    $data["pinming"] =input('pinming');
    $data["guige"] =input('guige');
    $data["shangyueyushu"]=input('shangyueyushu');
    $data["benyuejinhuo"] =input('benyuejinhuo');
    $data["benyuexiaoshou"] =input('benyuexiaoshou');
    $data["benyueyushu"] =input('benyueyushu');
    $data["shangyegonghuojia"] =input('shangyegonghuojia');
    $data["jinglijiangjin"] =input('jinglijiangjin');
    $data["daibiaojiangjinticheng"] =input('daibiaojiangjinticheng');
    $data["daibiaojiangjin"] =input('daibiaojiangjin');
    $data["lunwenfei"] =input('lunwenfei');
    $data["wanchengjine"] =input('wanchengjine');
    $data["renwu"] =input('renwu');
    $data["wanchenglv"] =input('wanchenglv');
    $data["beizhu"] =input('beizhu');
    $data["jinglijiangjindanjia"] =input('jinglijiangjindanjia');
    $rel=db('yusuanbu')->update($data);
    if($rel){
        $userid=input('userid');
        $useridname=$userid.'name';
        $useridcaozuo=$userid.'caozuo';
        $caozuo['time']=date('Y-m-d H:i:s',time());
        $caozuo['data']='用户'.cache($useridname).'修改了预算部数据';
        $this->caozuojilu($caozuo);
        cache($useridcaozuo,$caozuo['data']);
        return json(['code'=>200,'message'=>'成功']);
    }else{
        return json(['code'=>0,'message'=>'未添加']);
    }
}
    public function yusuanbudel(){
        $id=input('id');
        $rel=db('yusuanbu')->delete($id);
        if($rel){
            $userid=input('userid');
            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'删除了预算部数据';
            $this->caozuojilu($caozuo);
            cache($useridcaozuo,$caozuo['data']);
            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'未删除']);
        }
    }
    public function yusuanbusearch(){
        $yewuyuana=input('yewuyuan');
        $bumenjinglia=input('bumenjingli');
        $bumenjingli[]=['bumenjingli','like','%'.$bumenjinglia.'%'];
        $yewuyuan[]=['yewuyuan','like','%'.$yewuyuana.'%'];
        $rel=db('yusuanbu')->whereor($yewuyuan)->whereor($bumenjingli)->select();
        if($rel){
            return json(['code'=>200,'message'=>'成功','data'=>$rel]);
        }else{
            return json(['code'=>0,'message'=>'未搜索到结果']);
        }
    }
    public function yusuanbudaoru(){

        require_once('../vendor/phpoffice/phpexcel/Classes/PHPExcel/Reader/Excel2007.php');
        require_once('../vendor/phpoffice/phpexcel/Classes/PHPExcel/Reader/Excel5.php');


        //$objReader = new \PHPExcel_Reader_Excel2007; //实例化类
        $data = request()->file('data');

        if($data){
            $wenjian=$data->validate(['ext'=>'xls,xlsx'])->move('../public/uploads');
            //dump($wenjian);exit;
            $wenjian1=str_replace("\\","/",$wenjian->getSaveName());
            //dump($wenjian1);
            //$wenjian2=str_replace("\\","/",ROOT_PATH);
            $suffix = $wenjian->getExtension();
            //dump($suffix);
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
        unset($sheetContent[1]);
        //dump($sheetContent);
        if(empty($sheetContent)){
            return json(['message'=>'空数据']);
        }
        $time=time();
        foreach ($sheetContent as $k => $v){
            $dataa["yuefen"] =date('Y-m-d H:i:s',$time);
            $dataa["jine"] =$v[0];
            $dataa["yuefen"] =$v[1];
            $dataa["diqumingcheng"] =$v[2];
            $dataa["bumenjingli"] =$v[3];
            $dataa["yewuyuan"] =$v[4];
            $dataa["yiyuanmingcheng"] =$v[5];
            $dataa["zhongduanjibie"] =$v[6];
            $dataa["gonghuodanwei"]=$v[7];
            $dataa["pinming"] =$v[8];
            $dataa["guige"] =$v[9];
            $dataa["shangyueyushu"]=$v[10];
            $dataa["benyuejinhuo"] =$v[11];
            $dataa["benyuexiaoshou"] =$v[12];
            $dataa["benyueyushu"] =$v[13];
            $dataa["shangyegonghuojia"] =$v[14];
            $dataa["jinglijiangjin"]=$v[15];
            $dataa["daibiaojiangjinticheng"] =$v[16];
            $dataa["daibiaojiangjin"] =$v[17];
            $dataa["lunwenfei"] =$v[18];
            $dataa["wanchengjine"] =$v[19];
            $dataa["renwu"] =$v[20];
            $dataa["wanchenglv"] =$v[21];
            $dataa["beizhu"] =$v[22];
            $dataa["jinglijiangjindanjia"] =$v[23];
            $res[] = $dataa;
            //dump($res);
        }
        $chongfu=array_column($res,'yiyuanmingcheng');
        $yiyou=db('hospital')->field('name')->select();
        $yy=array_column($yiyou,'name');
        $a=array_diff(array_unique($chongfu),$yy);
        $jieguo=array_values($a);
        for($i=0;$i<count($jieguo);$i++){
            $b[$i]['name']=$jieguo[$i];
        }
        if(empty($b)){
            $rel=db('yusuanbu')->insertall($res);
        }else{
            $res=db('hospital')->insertall($b);
            $rel=db('yusuanbu')->insertall($res);
        }
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
//
//
//
//            };
//            $res=db('hospital')->insertAll($data);
        if($rel > 0){
            $userid=input('userid');
            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'导入了文件';
            $this->caozuojilu($caozuo);
            cache($useridcaozuo,$caozuo['data']);
            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'失败']);

        }

    }
}