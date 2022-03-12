<?php
namespace app\admin\controller;
use think\Controller;
use app\common\Common;
use think\facade\Cache;
class Fenxiaozhongduan extends Common{
    public function fenxiaozhongduan(){
        $userid=input('userid');
     

        $data=db('fenxiaozhongduan')->select();
        if($data){

            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';

            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'查看分销终端';
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
    public function fenxiaozhongduanadd(){
        $time=time();
        $dataa['yuefen']=date('Y-m',$time);
        $dataa['xiaoshoumoshi']=input('xiaoshoumoshi');
        $dataa['yewuyuan']=input('yewuyuan');
        $dataa['diqu']=input('diqu');
        $dataa['yiyuanmingcheng']=input('yiyuanmingcheng');
        $dataa['zhongduanjibie']=input('zhongduanjibie');
        $dataa['gonghuodanwei']=input('gonghuodanwei');
        $dataa['pinming']=input('pinming');
        $dataa['guige']=input('guige');
        $dataa['jinhuo']=input('jinhuo');
        $dataa['xiaoshou']=input('xiaoshou');
        $dataa['kucun']=input('kucun');
        $dataa['abbiaozhunshuihou']=input('abbiaozhunshuihou');
        $dataa['abjine']=input('abjine');
        $dataa['beizhu']=input('beizhu');
        $rel=db('fenxiaozhongduan')->insert($dataa);
        if($rel){
            $userid=input('userid');
            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'增加了分销终端';
            $this->caozuojilu($caozuo);
            cache($useridcaozuo,$caozuo['data']);
            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'未添加']);
        }
    }
    public function fenxiaozhongduanedit(){
        $dataa['id']=input('id');
        $dataa['yuefen']=input('yuefen');
        $dataa['xiaoshoumoshi']=input('xiaoshoumoshi');
        $dataa['yewuyuan']=input('yewuyuan');
        $dataa['diqu']=input('diqu');
        $dataa['yiyuanmingcheng']=input('yiyuanmingcheng');
        $dataa['zhongduanjibie']=input('zhongduanjibie');
        $dataa['gonghuodanwei']=input('gonghuodanwei');
        $dataa['pinming']=input('pinming');
        $dataa['guige']=input('guige');
        $dataa['jinhuo']=input('jinhuo');
        $dataa['xiaoshou']=input('xiaoshou');
        $dataa['kucun']=input('kucun');
        $dataa['abbiaozhunshuihou']=input('abbiaozhunshuihou');
        $dataa['abjine']=input('abjine');
        $dataa['beizhu']=input('beizhu');
        $rel=db('fenxiaozhongduan')->update($dataa);
        if($rel){
            $userid=input('userid');
            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'修改了直营终端';
            $this->caozuojilu($caozuo);
            cache($useridcaozuo,$caozuo['data']);
            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'未添加']);
        }
    }
    public function fenxiaozhongduandel(){
        $id=input('id');
        $rel=db('fenxiaozhongduan')->delete($id);
        if($rel){
            $userid=input('userid');
            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'删除了分销终端';
            $this->caozuojilu($caozuo);
            cache($useridcaozuo,$caozuo['data']);
            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'未删除']);
        }
    }
    public function fenxiaozhongduansearch(){
        $yewuyuana=input('yewuyuan');
//        $pinminga=input('pinming');
//        $pinming[]=['pinming','like','%'.$pinminga.'%'];
        $yewuyuan[]=['yewuyuan','like','%'.$yewuyuana.'%'];
        $rel=db('fenxiaozhongduan')->where($yewuyuan)->select();
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
            $dataa['xiaoshoumoshi']=$v[0];
            $dataa['yewuyuan']=$v[1];
            $dataa['diqu']=$v[2];
            $dataa['yiyuanmingcheng']=$v[3];
            $dataa['zhongduanjibie']=$v[4];
            $dataa['gonghuodanwei']=$v[5];
            $dataa['pinming']=$v[6];
            $dataa['guige']=$v[7];
            $dataa['jinhuo']=$v[8];
            $dataa['xiaoshou']=$v[9];
            $dataa['kucun']=$v[10];
            $dataa['abbiaozhunshuihou']=$v[11];
            $dataa['abjine']=$v[12];
            $dataa['beizhu']=$v[13];
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
            $rel=db('fenxiaozhongduan')->insertall($res);
        }else{
            $res=db('hospital')->insertall($b);
            $rel=db('fenxiaozhongduan')->insertall($res);
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