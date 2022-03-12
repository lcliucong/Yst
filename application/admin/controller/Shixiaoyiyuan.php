<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use app\common\Common;
use think\facade\Cache;


class Shixiaoyiyuan extends Common{
    public function shixiaoyiyuan(){
        $data=db('shixiaoyiyuan')->select();
        if($data){
            $userid=input('userid');
            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';

            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'查看实销医院';
            if(!cache($useridcaozuo)==$caozuo['data']){
                $this->caozuojilu($caozuo);
                cache($useridcaozuo,$caozuo['data']);
            }else{
                //相同不添加;
            }
            return json(['code'=>200,'message'=>'成功','data'=>$data]);
        }else{
            return json(['code'=>0,'message'=>'失败']);
        }
    }
    public function shixiaoyiyuanadd(){
        $data['yuefen']=input('yuefen');
        $data['diqumingcheng']=input('diqumingcheng');
        $data['xiaoshoumoshi']=input('xiaoshoumoshi');
        $data['bumenjingli']=input('bumenjingli');
        $data['yewuyuan']=input('yewuyuan');
        $data['yiyuanmingcheng']=input('yiyuanmingcheng');
        $data['gonghuodanwei']=input('gonghuodanwei');
        $data['pinming']=input('pinming');
        $data['guige']=input('guige');
        $data['shangyueyushu']=input('shangyueyushu');
        $data['benyuejinhuo']=input('benyuejinhuo');
        $data['benyueyushu']=input('benyueyushu');
        $data['benyuexiaoshou']=input('benyuexiaoshou');
        $data['abbiaozhunshuihou']=input('abbiaozhunshuihou');
        $data['abjine']=input('abjine');
        $data['lunwenfei']=input('lunwenfei');
        $data['jine']=input('jine');
        $data['jinglijiangjindanjia']=input('jinglijiangjindanjia');
        $data['jinglijiangjin']=input('jinglijiangjin');
        $data['daibiaojiangjinticheng']=input('daibiaojiangjinticheng');
        $data['daibiaojiangjin']=input('daibiaojiangjin');
        $data['baozhengjin']=input('baozhengjin');
        $data['shifujine']=input('shifujine');
        $data['shangyegonghuojia']=input('shangyegonghuojia');
        $data['wanchengjine']=input('wanchengjine');
        $data['wanchenglv']=input('wanchenglv');
        $data['renwu']=input('renwu');
        $data['chaochajine']=input('chaochajine');
        $data['jiangfa']=input('jiangfa');
        $data['shizhijine']=input('shizhijine');
        $data['beizhu']=input('beizhu');

        $data=db('shixiaoyiyuan')->insert($data);
        if($data){
            $userid=input('userid');
            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'增加了实销医院';
            $this->caozuojilu($caozuo);
            cache($useridcaozuo,$caozuo['data']);
            return json(['code'=>200,'message'=>'成功','data'=>$data]);
        }else{
            return json(['code'=>0,'message'=>'失败']);
        }
    }
    public function shixiaoyiyuanedit(){
        $data['id']=input('id');
        $data['yuefen']=input('yuefen');
        $data['diqumingcheng']=input('diqumingcheng');
        $data['xiaoshoumoshi']=input('xiaoshoumoshi');
        $data['bumenjingli']=input('bumenjingli');
        $data['yewuyuan']=input('yewuyuan');
        $data['yiyuanmingcheng']=input('yiyuanmingcheng');
        $data['gonghuodanwei']=input('gonghuodanwei');
        $data['pinming']=input('pinming');
        $data['guige']=input('guige');
        $data['shangyueyushu']=input('shangyueyushu');
        $data['benyuejinhuo']=input('benyuejinhuo');
        $data['benyueyushu']=input('benyueyushu');
        $data['benyuexiaoshou']=input('benyuexiaoshou');
        $data['abbiaozhunshuihou']=input('abbiaozhunshuihou');
        $data['abjine']=input('abjine');
        $data['lunwenfei']=input('lunwenfei');
        $data['jine']=input('jine');
        $data['jinglijiangjindanjia']=input('jinglijiangjindanjia');
        $data['jinglijiangjin']=input('jinglijiangjin');
        $data['daibiaojiangjinticheng']=input('daibiaojiangjinticheng');
        $data['daibiaojiangjin']=input('daibiaojiangjin');
        $data['baozhengjin']=input('baozhengjin');
        $data['shifujine']=input('shifujine');
        $data['shangyegonghuojia']=input('shangyegonghuojia');
        $data['wanchengjine']=input('wanchengjine');
        $data['wanchenglv']=input('wanchenglv');
        $data['renwu']=input('renwu');
        $data['chaochajine']=input('chaochajine');
        $data['jiangfa']=input('jiangfa');
        $data['shizhijine']=input('shizhijine');
        $data['beizhu']=input('beizhu');

        $rel=db('shixiaoyiyuan')->update($data);
        if($rel){
            $userid=input('userid');
            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'修改了实销医院';
            $this->caozuojilu($caozuo);
            cache($useridcaozuo,$caozuo['data']);
            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'失败']);
        }
    }
    public function shixiaoyiyuandel(){
        $id=input('id');
        $rel=db('shixiaoyiyuan')->delete($id);
        if($rel){
            $userid=input('userid');
            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'删除了实销医院';
            $this->caozuojilu($caozuo);
            cache($useridcaozuo,$caozuo['data']);
            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'失败']);
        }

    }
    public function shixiaoyiyuansearch(){
        $yiyuanmingcheng=input('yiyuanmingcheng');
//        $pinminga=input('pinming');
//        $pinming[]=['pinming','like','%'.$pinminga.'%'];
        $yiyuanmingcheng[]=['yiyuanmingcheng','like','%'.$yiyuanmingcheng.'%'];
        $rel=db('shixiaoyiyuan')->where($yiyuanmingcheng)->select();
        if($rel){
            return json(['code'=>200,'message'=>'成功','data'=>$rel]);
        }else{
            return json(['code'=>0,'message'=>'未搜索到结果']);
        }
    }

    public function fenxiaozhongduandaoru(){

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
        unset($sheetContent[2]);
        //dump($sheetContent);
        if(empty($sheetContent)){
            return json(['message'=>'空数据']);
        }
        $time=time();
        foreach ($sheetContent as $k => $v){
            $dataa["yuefen"] =date('Y-m',$time);

            $dataa['diqumingcheng']=$v[0];
            $dataa['xiaoshoumoshi']=$v[1];
            $dataa['bumenjingli']=$v[2];
            $dataa['yewuyuan']=$v[3];
            $dataa['yiyuanmingcheng']=$v[4];
            $dataa['gonghuodanwei']=$v[5];
            $dataa['pinming']=$v[6];
            $dataa['guige']=$v[7];
            $dataa['shangyueyushu']=$v[8];
            $dataa['benyuejinhuo']=$v[9];
            $dataa['benyueyushu']=$v[10];
            $dataa['benyuexiaoshou']=$v[11];
            $dataa['abbiaozhunshuihou']=$v[12];
            $dataa['abjine']=$v[13];
            $dataa['lunwenfei']=$v[14];
            $dataa['jine']=$v[15];
            $dataa['jinglijiangjindanjia']=$v[16];
            $dataa['jinglijiangjin']=$v[17];
            $dataa['daibiaojiangjinticheng']=$v[18];
            $dataa['daibiaojiangjin']=$v[19];
            $dataa['baozhengjin']=$v[20];
            $dataa['shifujine']=$v[21];
            $dataa['shangyegonghuojia']=$v[22];
            $dataa['wanchengjine']=$v[23];
            $dataa['renwu']=$v[24];
            $dataa['wanchenglv']=$v[25];
            $dataa['chaochajine']=$v[26];
            $dataa['jiangfa']=$v[27];
            $dataa['shizhijine']=$v[28];
            $dataa['beizhu']=$v[29];
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
            $rel=db('shixiaoyiyuan')->insertall($res);
        }else{
            $res=db('hospital')->insertall($b);
            $rel=db('shixiaoyiyuan')->insertall($res);
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