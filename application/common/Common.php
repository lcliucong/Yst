<?php
namespace app\common;
use think\Controller;
use think\facade\Session;
use think\facade\Request;
use think\facade\Cache;

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
class common extends Controller
{
    protected function initialize()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods:POST,GET,OPTIONS,DELETE,PUT'); // 允许请求的类型
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Headers:x-requested-with,Content-Type,X-CSRF-Token');
        header('Access-Control-Allow-Headers: *');
        $level = Session('level');
        $fangfa=get_called_class();
        $fangfa=explode('\\',$fangfa);
        $data=array_pop($fangfa);
        $fangfa =get_class_methods("app\\admin\\controller\\$data");
        //dump($data);dump($controller);

    }
    protected function caozuojilu($caozuo){
        $caozuojilu=db('caozuojilu')->insert($caozuo);

    }
    public function __call($name,$arguments){
        echo '没有'.$name;
    }


}
