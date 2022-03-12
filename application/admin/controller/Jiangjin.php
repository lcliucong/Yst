<?php
namespace app\admin\controller;
use think\Controller;
use app\common\Common;
use think\facade\Cache;
use think\Db;
use think\Query;
use think\cache\driver\Redis;
class Jiangjin extends Common
{
    public function jiangjin()
    {
        $manager = Db::query("
            select o.id,o.managername,o.telephone,o.status,j.jxs,j.jxsid,h.hospitalid,h.name,h.anothername,h.place,r.djid,r.dengjiname,r.renwu
            from tp51_out o 
            left join tp51_jxs j 
            on o.jxsid=j.jxsid
            left join tp51_hospital h
            on o.hospitalid=h.hospitalid
            left join tp51_renwu r
            on o.djid=r.djid
            order by o.id
        ");
        dump($manager);
        $xishu = input('xishu');
        $xishu = 0.2;
        foreach ($manager as $value) {
            $jiangjin = $value['renwu'] * $xishu;
            dump('奖金为' . $jiangjin);
        }
    }

    public function ceshi()
    {
        $a1 = array('河北', '北京2', '河 北', '北京');
        $a2 = array(
            array('石家庄', 'a'),
            array('中关村2', 'b'),
            array('邢台', 'c'),
            array('中关村', 'd'),
        );
        $num = array_multisort($a1, $a2);
        dump($a1);
        dump($a2);
    }

    public function ceshi2()
    {
        $a1 = array('河北 ', 'a');
        $a2 = array(
            array('河北', 'a'),
            array('中关村2', 'b'),
            array('邢台', 'c'),
            array('中关村', 'd'),
        );
        $num = in_array($a1, $a2);
        dump($num);
    }

    public function ceshi3()
    {
        $zhi = mt_rand() . time();
        session('a', $zhi, 'zxc');
        $a = session('a', '', 'zxc');
        dump($a);
    }

    public function ceshi4()
    {

        $b = session('a', '', 'zxc');
        dump($b);
    }

    public function ceshi5()
    {
        $a = 1 / 3;
        $b = 3;
        $c = 5;
        for ($i = 1; $i <= 100; $i++) {
            for ($j = 1; $j <= 35; $j++) {
                for ($k = 1; $k <= 24; $k++) {
                    if ($i + $j + $k == 100) {
                        echo $i . '&nbsp;' . '&nbsp;' . $j . '&nbsp;' . '&nbsp;' . $k . '<br>';
                    }
                }
            }
        }
    }

    public function ceshi6()
    {
        $a = cache('1name');
        dump($a);
    }

    public function ceshi7()
    {
        $a = [array('a')];
        $b = count($a);
        var_dump($b);
    }

    public function ceshi8()
    {
        $a = 0.60;
        $b = bcadd($a, 1, 2);
        var_dump($b);
    }

    public function ceshi9()
    {

        $a = [['id' => 1, 'data' => 'aaa'], ['id' => 2, 'data' => 'bbb']];

        Db::startTrans();
        try {//注意不要用助手函数
            Db::name('caozuojilu')->insert(['id' => 5]);
            Db::name('caozuojilu')->delete(15221);
            Db::name('caozuojilu')->insertall($a);
            // 提交事务
            Db::commit();
            $this->success("操作成功！", "");
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();

            return json(['mes' => ($e->getMessage())]);
        }
//        Db::transaction(function ()use($a) {
//            Db::name('caozuojilu')->insert(['id'=>5]);
//            Db::name('caozuojilu')->delete(15221);
//            Db::name('caozuojilu')->insertall($a);
//        });
    }

    public function ceshi10()
    {
        $list = Db::name('out')->paginate(10, true)->toarray();
//dump($list['data']);
        $this->assign('list', $list['data']);

        return $this->fetch();

    }

    public function ceshi11()
    {
        if (
            3 == 3 &&
            (3 == 4 || 3 == 3) &&
            4 == 3
        ) {
            echo 1;
        }
    }

    public function ceshi12()
    {
        //dump(round(bcmul((bcsub($fahuo[$a]['abjine'],$fahuo[$a]['renwu'],3)),0.01,3),2));
    }

    public function ceshi13()
    {
        dump(null < 0.1);
    }

    public function ceshi14()
    {
        $a = [['id' => 1, 'zhifufangfa' => 2], ['id' => 2, 'zhifufangfa' => 1]];
        $b = array_filter($a, function ($a) {

            return $a['zhifufangfa'] == 1;

        });
        dump($b);
        dump($a);
    }

    public function ceshi15()
    {
        $my_array = array(3, "Dog", "Cat", "Horse", 'a');

        list($a, , $c) = $my_array;
        echo "我在这里只用了 $a 和 $c 变量。";
    }

    public function redis()
    {
        $config = [
            'host' => '127.0.0.1',
            'port' => 6379,
            'password' => '',
            'select' => 0,
            'timeout' => 0,
            'expire' => 0,
            'persistent' => false,
            'prefix' => '',
        ];

        $Redis = new Redis($config);
        return phpinfo();
    }

    public function ceshi16()
    {
        $data = 'a2022-12-23 11:12:0';
        preg_match_all("/[^2][0-9]{3}.?[0-9]{2}.?[0-9]{2}$/", $data, $pat_array);
        $a = preg_replace('/[1236]/', '*', 123456);
        dump($a);
        var_dump($pat_array);
    }

    public function ceshi17()
    {
        require_once('../vendor/phpoffice/phpexcel/Classes/PHPExcel/Reader/Excel2007.php');
        require_once('../vendor/phpoffice/phpexcel/Classes/PHPExcel/Reader/Excel5.php');
        $data = request()->file('data');
        if ($data) {
            $wenjian = $data->validate(['ext' => 'xls,xlsx'])->move('../public/uploads');
            $wenjian1 = str_replace("\\", "/", $wenjian->getSaveName());
            dump($wenjian);
            dump($wenjian1);
            exit;
            $suffix = $wenjian->getExtension();
            //判断哪种类型
            if ($suffix == "xlsx") {
                $reader = \PHPExcel_IOFactory::createReader('Excel2007');
            } else {
                $reader = \PHPExcel_IOFactory::createReader('Excel5');
            }
        } else {
            $this->error();
        }

        $a = '../public/uploads/' . $wenjian1;      //相对路径
        if (!$reader->canRead($a)) {
            $reader = \PHPExcel_IOFactory::createReader('Excel5');
        }
        $excel = $reader->load($a, $encode = 'utf-8');
        // $objPHPExcel = $objReader->load($a); //读取excel文件
        $sheetContent = $excel->getSheet(0)->toArray();
        dump($sheetContent);
        exit;
        unset($sheetContent[0]);
        if (empty($sheetContent)) {
            return json(['code' => 0, 'mes' => '空数据']);
        }
        foreach ($sheetContent as $k => $v) {
            if (empty(array_filter($v))) {
                continue;
            }
            $dataa['diqu'] = str_replace([' ', '/'], '-', $v[2]);

            $res[] = $dataa;

        }
        dump($res);
        exit;
        if ($rel > 0) {

            return json(['code' => 200, 'mse' => '成功', 'total' => $total]);
        } else {
            return json(['code' => 0, 'mes' => '失败', 'total' => $total]);

        }
    }

    public function ceshi18()
    {
        $data = db('out')
            ->where('zhuguan', '戚子建（主管）')
//            ->whereor('bumenjingli','戚子健')
            ->update(['zhuguan' => '戚子建(主管)']);
//            ->field('daibiao,zhuguan')
//        ->select();
        dump($data);
    }

    public function ceshi19()
    {
        $data = db('flowofmed')->where('in_time', 'between', ['2022-01-01', '2022-01-31'])->
        field('med_name,customer_name,med_specs')->where('med_name', '双冬胶囊')->group('customer_name,med_specs')
            ->order('med_name')->select();
//        $data=Db::name('flowofmed')->field('customer_name,med_name,med_specs,facname')
//            ->group('customer_name,med_name,med_specs,facname')->where('in_time','between',['2022-01-01','2022-01-31'])->having('count(*)>1')->select();

//        $a=Db::query("
//                select  distinct  x.a,x.b,x.c
//                from tp51_a as x,tp51_a as y
//                where x.a!=y.a and x.b=y.b and x.c=y.c");
//        $a=Db::query("
//                select  distinct  x.facname,x.med_name,x.med_specs,x.customer_name
//                from tp51_flowofmed as x,tp51_flowofmed as y
//                where x.facname!=y.facname and x.customer_name=y.customer_name and x.med_name=y.med_name and x.med_specs=y.med_specs");

    }

    public function ceshi20()
    {
        $data=db('zhifu')->where('yuefen','2022-02')->select();
//        $data = Db::name('flowofmed')->field('sum(med_salenum) as med_salenum,med_name,med_specs,customer_name,customer_nameb,med_price,facname')
//            ->where('in_time', 'between time', ['2022-01-01','2022-01-31'])
//            ->group('med_name,med_specs,customer_name')
//            ->select();
        dump(count($data));
        exit;

    }

    public function ceshi21()
    {
        $data1 = db('renwu')->where('bumen', '直营')->select();
        $data2 = db('renwu')->where('bumen', 'like', '%预算%')->select();
        $data3 = db('renwu')->where('bumen', 'like', '医院预算%')->select();
        dump($data3);
        dump(array_merge($data1, $data2, $data3));
    }
    public function ceshi22(){
        ini_set('memory_limit','1024M');
        set_time_limit(0);
        $timenow=input('time');
        $time=input('time');
        $time=explode('-',$time);
        $ces=array_pop($time);
//        dump($timenow);exit;//
        $time=$time['0'];
        $time2 = date("t",strtotime($timenow));
        if($ces>1){
            $ces-=1;
            if(strlen($ces)<2){
                $ces='0'.$ces;
            }
        }else{
            $time-=1;
            $ces=12;
        }
        $timeup=$time.'-'.$ces;
        $i=0;

        $result=array();
        $nomate=array();
        $start = microtime(true);
        $yaoming=Db::name('flowofmed')->where('in_time','between time',[$timenow.'-01',$timenow.'-'.$time2])->distinct(true)->field('med_name,med_specs')->select();
//        dump($yaoming);exit;

        foreach($yaoming as $ym) {

            $data=Db::name('flowofmed')->field('sum(med_salenum) as med_salenum,med_name,med_specs,customer_name,customer_nameb,med_price')
                ->where('in_time', 'between time', [$timenow . '-01', $timenow . '-' . $time2])
                ->where('med_name', $ym['med_name'])
                ->where('med_specs', $ym['med_specs'])
                ->group('med_name,med_specs,customer_name,customer_nameb')
                ->order('med_name,med_specs')->select();
            $zhifu = Db::name('zhifu')->field('kehumingcheng1,yiyuanjibie,yewuyuan,zhuguan,bumenjingli,diqu,pinming,guige,
            bumen,daibiao,zhuguanjiangjinticheng,jinglijiangjinticheng,abbiaozhunshuihou,lunwenfei,daibiaojiangjinticheng,kehumingcheng2')
                ->where('yuefen', $timeup)
                ->where('pinming', $ym['med_name'])
                ->where('guige', $ym['med_specs'])
                ->order('pinming')
                ->select();

            $zhifu30 = Db::name('zhifu30')->field('kehumingcheng1,yiyuanjibie,yewuyuan,bumenjingli,diqu,pinming,guige,
            bumen,yapiabbiaozhun,kehumingcheng2')
                ->where('yuefen', $timeup)
                ->where('pinming', $ym['med_name'])
                ->where('guige', $ym['med_specs'])
                ->order('pinming')
                ->select();
            $zhifu60 = Db::name('zhifu60')->field('kehumingcheng1,yiyuanjibie,yewuyuan,bumenjingli,diqu,pinming,guige,
            bumen,yapiabbiaozhun,kehumingcheng2')
                ->where('yuefen', $timeup)
                ->where('pinming', $ym['med_name'])
                ->where('guige', $ym['med_specs'])
                ->order('pinming')
                ->select();
            $xinxibeian = array_merge($zhifu, $zhifu30, $zhifu60);

            foreach ($data as $j => $value) {

                $zhifu['med_name'] = $value['med_name'];
                $zhifu['med_specs'] = $value['med_specs'];
                $zhifu['customer_name'] = $value['customer_name'];
                $zhifu['customer_nameb'] = $value['customer_nameb'];

                foreach ($xinxibeian as $v) {
                    //dump($v);exit;


                    if (
                        $zhifu['med_name'] == $v['pinming'] &&
                        $zhifu['med_specs'] == $v['guige'] &&
                        $zhifu['customer_name'] == $v['kehumingcheng1'] &&
                        $zhifu['customer_nameb'] == $v['kehumingcheng2']
                    ) {
                        $result[$i]['yuefen'] = $timenow;
                        $result[$i]['diqu'] = $v['diqu'];
                        $result[$i]['bumen'] = $v['bumen'];
//                        $result[$i]['yewuyuan'] = $v['yewuyuan'];
//                        $result[$i]['daibiao'] = $v['daibiao'];
//                        $result[$i]['zhuguan'] = $v['zhuguan'];
//                        $result[$i]['kehumingcheng1'] = $value['customer_name'];
//                        $result[$i]['kehumingcheng2'] = $value['customer_nameb'];
//                        $result[$i]['bumenjingli'] = $v['bumenjingli'];
//                        $result[$i]['yiyuanjibie'] = $v['yiyuanjibie'];
//                        $result[$i]['shangyegongsi'] = $value['facname'];
//                        $result[$i]['pinming'] = $v['pinming'];
//                        $result[$i]['guige'] = $v['guige'];
//                        $result[$i]['shangyegongsi'] = $value['facname'];
//                        $result[$i]['shangyueyushu'] = $shangyueyushu;
//                        $result[$i]['benyuejinhuo'] = $value['med_salenum'];
//                        $result[$i]['med_salenum'] = $value['med_salenum'];
//                        $result[$i]['benyuexiaoshou'] = '';
//                        $result[$i]['benyueyushu'] = '';
//                        $result[$i]['abbiaozhunshuihou'] = $v['abbiaozhunshuihou'];
//                        $result[$i]['abjine'] = '';
//                        $result[$i]['lunwenfei'] = $v['lunwenfei'];
//                        $result[$i]['lunwenfeijine'] = '';
//                        $result[$i]['daibiaojiangjinticheng'] = $v['daibiaojiangjinticheng'];
//                        $result[$i]['daibiaojiangjin'] = '';
//                        $result[$i]['zhuguanjiangjinticheng'] = $v['zhuguanjiangjinticheng'];
//                        $result[$i]['zhuguanjiangjin'] = '';
//                        $result[$i]['jinglijiangjinticheng'] = $v['jinglijiangjinticheng'];
//                        $result[$i]['jinglijiangjin'] = '';
//                        $result[$i]['shangyegonghuojia'] = $value['med_price'];
//                        $result[$i]['wanchengjine'] = '';
//                        $result[$i]['renwu'] = $v['renwu'];
//                        $result[$i]['wanchenglv'] = '';
//                        $result[$i]['jiangfa'] = '';
//                        $result[$i]['shizhijine'] = '';
//                        $result[$i]['zhifufangfa'] = $v['zhifufangfa'];
//                        if (empty($result[$i]['kehumingcheng1']) || empty($result[$i]['pinming']) || empty($result[$i]['guige'])) {
//
//                            return json(['code' => 0, 'mes' => '数据为空', 'data' => $result[$i]]);
//                        }
                        unset($v);
                        break;
                    }

                    unset($v);

                }

                if (empty($result[$i])) {
                    //添加未匹配成功原因

                    $data[$j]['message'] = '信息备案不存在（相等两条及以下视为不存在）';
//                foreach ($xinxibeian as $xinxi2) {
//                    if($zhifu['med_name']!==$xinxi2['pinming']&&$zhifu['med_specs']==$xinxi2['guige']&&$zhifu['customer_name']==$xinxi2['zhongduanmingcheng']&&$zhifu['customer_nameb']==$xinxi2['zhongduanmingcheng2']
//                    ) {
//                        $data[$j]['message'] = '品名有误（存在规格，终端名称，终端别名一致）';break;
//                    } elseif ($zhifu['customer_name']!==$xinxi2['zhongduanmingcheng']&&$zhifu['med_name']==$xinxi2['pinming']&&$zhifu['med_specs']==$xinxi2['guige']&&$zhifu['customer_nameb']==$xinxi2['zhongduanmingcheng2']) {
//                        $data[$j]['message'] = '终端名称有误（存在品名，规格，终端别名一致）';break;
//                    } elseif ($zhifu['med_specs']!==$xinxi2['guige']&&$zhifu['med_name']==$xinxi2['pinming']&&$zhifu['customer_name']==$xinxi2['zhongduanmingcheng']&&$zhifu['customer_nameb']==$xinxi2['zhongduanmingcheng2']) {
//                        $data[$j]['message'] = '规格有误（存在品名，终端名称，终端别名一致）';break;
//                    } elseif ($zhifu['customer_nameb']!==$xinxi2['zhongduanmingcheng']&&$zhifu['med_name']==$xinxi2['pinming']&&$zhifu['med_specs']==$xinxi2['guige']&&$zhifu['customer_name']==$xinxi2['zhongduanmingcheng']) {
//                        $data[$j]['message'] = '终端别名有误（存在品名，规格，终端名称一致）';break;
//                    }
////
//                }
                    $nomate[] = $data[$j];
                }
                $i++;

            }//
        }

        $elapsed = (microtime(true) - $start)*100;
        dump($elapsed);

        dump(count($result));
        dump(count($nomate));
        exit;
    }
    public function ceshi23(){
        $a=bcsub(5,null,3);
        dump(empty("1"));
        $fun=function(){
            for($i=0;$i<10;$i++){
                $data=yield $i;
                if($data==3){
                    return;
                }
            }
        };
        $a=$fun();
        foreach ($a as $value){
            if($value==2){
                $a->send(3);
            }
            echo $value;
        }

    }
    public function ceshi24(){
        $a=5;$b=-3;
        dump( $a+$b);

        $a=db('zhifu')->where('yuefen','2022-03')->delete();
        dump($a);        ob_clean();
        $a='2';
        $b=4;
        dump($a*$b);
    }
    public function ceshi25(){
        $data=['1','2','3','a','b'];
        foreach ($data as$k =>&$v){
            if($v=='a'){
                $v='4';
            }
            if($v=='b'){
                $v='__('.'$v'.')';
            }
            echo $v;
        }
        dump($v);
        dump($data);
    }
}