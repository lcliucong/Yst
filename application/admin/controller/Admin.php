<?php
namespace app\admin\controller;
use think\Db;
use app\admin\validate\Admin as AdminValidate;
//use think\captcha\Captcha;
use think\facade\Session;
//use think\Loader;
use app\common\Common;
use think\facade\Cache;
class Admin extends Common{
//    public function  initialize(){
//        parent::initialize();
//        header('Access-Control-Allow-Origin: *');
//        header('Access-Control-Allow-Methods:POST,GET,OPTIONS,DELETE,PUT'); // 允许请求的类型
//        header('Access-Control-Allow-Credentials: true');
//        header('Access-Control-Allow-Headers:x-requested-with,Content-Type,X-CSRF-Token');
//        header('Access-Control-Allow-Headers: *');
//        $fangfa=__CLASS__;dump($fangfa);
//        $controller =get_class_methods('app\\admin\\ccontroller\\juese');
//        dump($controller);
//
//    }
    public function admin(){

            $username=input('username');//用户名
            $password=input('password');
            $name=db('admin')->where('username',$username)->find();
            $level=$name['level'];
            if(empty($name)){
                return json(['code' => 100,'message' =>'用户名不存在' ]);
            }

                if(md5($password)==$name['password']){
                    $userid=$name['id'];
                    //验证只能一个电脑登陆
//                    if(cache($userid)==$userid){
//                        return json(['message'=>'已在其他设备登陆']);
//                    }

                   $juese=db('juese')->where('jueseid',$level)->find();
                //传路由mid
                   $juesequan=db('juesequan j')->where('j.jueseid',$level)->field('mid')->select();
                    $juesequan=array_column($juesequan,'mid');
                    $juesequan=array_values($juesequan);

                //传产品
                    $juesechanpin=db('juesechanpin j')->join('chanpin c','j.chanpinid=c.id')->where('j.jueseid',$level)->field('c.chanpin')->select();
                    $juesechanpin=array_column($juesechanpin,'chanpin');
                    $juesechanpin1=array_values($juesechanpin);
                    $useridchanpin=$userid.'chanpin';

                    cache($useridchanpin,$juesechanpin1);

                    $useridname=$userid.'name';

                    cache::set($useridname,$name['xingming']);
                    //cache::set($useridquanxian,$mid);
                    //cache::set($useriddiqu,$diqu);

                    $caozuo['time']=date('Y-m-d H:i:s',time());
                    $caozuo['data']='用户'.cache($useridname).'登陆了系统';
                    $this->caozuojilu($caozuo);
                    $useridcaozuo=$userid.'caozuo';
                    cache($useridcaozuo,$caozuo['data']);

                    return json(['code' => 200,'message' =>'登陆成功','username'=>$username,'juese'=>$juese['name'],'xingming'=>$name['xingming'],'userid'=>$userid,'chanpin'=>$juesechanpin1,'mid'=>$juesequan,'imgurl'=>$name['imgurl']]);
                }
                else{
                    return json(['code' => 0,'message' =>'用户名或密码错误']);//密码错误
                }
    }
    public function adminlist(){

        $userid=input('userid');
//原管理员展示，将地区字段数据合并为一格了
//          $data = Db::query("
//            select a.id,a.username,a.xingming,a.bumen,a.level,j.name,GROUP_CONCAT(dq.diquname) as diqu,GROUP_CONCAT(dq.diqu) as diquid
//            from tp51_admin a
//            left join tp51_juese j
//            on a.level=j.jueseid
//            left join tp51_dqid d
//            on a.id=d.dqid
//            left join tp51_diqu dq
//            on d.diqu=dq.diqu
//            GROUP BY d.dqid
//            order by a.id

            $data=db('admin a')->leftJoin('juese j','a.level=j.jueseid')->field('a.id,a.username,a.xingming,a.bumen,j.jueseid,j.name,j.miaoshu')->select();

          if ($data) {
              $userid=input('userid');
              $useridcaozuo=$userid.'caozuo';
              $useridname=$userid.'name';

              $caozuo['time']=date('Y-m-d H:i:s',time());
              $caozuo['data']='用户'.cache($useridname).'查看了基础信息备案';
              if(!cache($useridcaozuo)==$caozuo['data']){
                  $this->caozuojilu($caozuo);
                  cache($useridcaozuo,$caozuo['data']);
              }else{
                  //相同不添加;
              }

              return json(['code' => 200, 'message' => '成功', 'admin' => $data]);
          } else {
              return json(['code' => 0, 'message' => '未查到']);
          }

          //dump($data);

      }


    public function adminadd(){
//        $a=session::has('diqu');dump($a);
//        die;
        $data['username']=input('username');//用户名
        $data['password']=input('password');
        $data['xingming']=input('xingming');
        $data['bumen']=input('bumen');
        $data['level']=input('jueseid');
        $data['imgurl']=input('imgurl');
        //$juese=db('juese')->field('jueseid')->field('name')->select();

        //验证器验证
        $validate = new AdminValidate;
        $i=0;
        $err='';
        if (!$validate->scene('add')->batch()->check($data)) {
            foreach ($validate->getError() as $k=>$v){

                $value[$i]=$v;
                $i+=1;
            }
            //var_dump($value[1]);
            for($j=0;$j<$i;$j++){
                if($j>0){
                    $err.= ','.$value[$j];
                }else{
                    $err.=$value[$j];
                }
            }
            return json(['code'=>'150','message'=>$err]);
        }else{
            $data['password']=md5($data['password']);
            $res=db('admin')->insertGetId($data);

//            $dqid['dqid']=$res;
//            $diqu=input('diqu');
//            if(is_array($diqu)) {
//                foreach ($diqu as $diqua) {
//                    $dqid['diqu'] = $diqua;
//                    $abc = db('dqid')->insert($dqid);
//                }
//            }else{
//                $dqid['diqu']=input('diqu');
//                $abc = db('dqid')->insert($dqid);
//            }

            if($res){
                $userid=input('userid');
                $useridname=$userid.'name';
                $useridcaozuo=$userid.'caozuo';
                $caozuo['time']=date('Y-m-d H:i:s',time());
                $caozuo['data']='用户'.cache($useridname).'添加了管理员';
                $caozuojilu=db('caozuojilu')->insert($caozuo);
                cache($useridcaozuo,$caozuo['data']);

                return json(['code'=>200,'message'=>'成功']);
            }else{
                return json(['code'=>0,'message'=>'失败']);
            }
        }

    }
    public function adminedit(){
        $userid=input('userid');
//        $useridquanxian=$userid.'quanxian';
//        $cache = cache($useridquanxian);
//        if($cache==null){return json(['code'=>0,'message'=>'您没有此操作权限']);}
//
//        if(!in_array(['mid'=>23],$cache,false)){
//            return json(['code'=>0,'message'=>'您没有此操作权限']);
//        }

        $data['id']=input('id');
        $data['xingming']=input('xingming');
        $data['username']=input('username');//用户名
        $data['bumen']=input('bumen');
        $data['level']=input('jueseid');
        //dump($data);
        $res=db('admin')->update($data);

        if($res){
            $userid=input('userid');
            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'修改了管理员';
            $caozuojilu=db('caozuojilu')->insert($caozuo);
            cache($useridcaozuo,$caozuo['data']);

            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'未修改']);
        }
    }
    public function adminpasswordedit(){
        $data['id']=input('id');
        //$mm=db('admin')->where('id',$data['id'])->find();
        //$yuanmima=md5(input('yuanmima'));
        //if($mm['password']==$yuanmima ? false:true){
        //    return json(['code'=>'0','message'=>'原密码错误，发现内鬼']);
        //}else{
            $data['password']=md5(input('password'));
            $res=db('admin')->update($data);
       // }
        if($res){
            $userid=input('userid');
            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'修改了ID为'.$data['id'].'的密码';
            $caozuojilu=db('caozuojilu')->insert($caozuo);
            cache($useridcaozuo,$caozuo['data']);

            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'失败']);
        }
    }
    public function admindel(){
        $del['id']=input('id');
//        if(in_array(1,$del['id'])){
//            return json(['code'=>0,'message'=>'超级管理员不能删除']);
//        }

        if(is_array($del)){
            foreach ($del as $dela){
                $rel=db('admin')->delete($dela);
            }

        }else{
            //var_dump($data);
            $rel=db('admin')->delete($del);
        }

        if($rel){
            $userid=input('userid');
            $useridname=$userid.'name';
            $useridcaozuo=$userid.'caozuo';
            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.cache($useridname).'删除了账号';
            $caozuojilu=db('caozuojilu')->insert($caozuo);
            cache($useridcaozuo,$caozuo['data']);

            return json(['code'=>200,'message'=>'成功']);
        }else{
            return json(['code'=>0,'message'=>'失败']);
        }
    }
    public function tuichu(){
        //Cache('mid','mid',0.01);

        $userid=input('userid');
        $useridname=$userid.'name';
        $useridcaozuo=$userid.'caozuo';
        $rel=cache::pull($useridname);
        Cache::rm($useridname);
        Cache::rm($useridcaozuo);

        if($rel){

            $caozuo['time']=date('Y-m-d H:i:s',time());
            $caozuo['data']='用户'.$rel.'退出登录';
            $caozuojilu=db('caozuojilu')->insert($caozuo);

            return json(['code'=>200,'message'=>'清除缓存成功']);
        }else{
            return json(['code'=>0,'message'=>'未清除']);
        }
    }
}