<?php
namespace app\front\controller;

use think\addons\Controller;
use think\Contorller;
class HeadCommon extends Controller
{
    protected $headers;
    public function heads(){
        $headers =  [
            header("Access-Control-Allow-Origin:*"),
            header('Access-Control-Allow-Methods:POST,GET'),
            header('Access-Control-Allow-Headers:x-requested-with, content-type')
        ];
    }
}
