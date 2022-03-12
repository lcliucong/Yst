<?php
namespace app\admin\behavior;

use think\Hook;

class LogHook{
    /***
     * @param $params
     * 初始化标签位
     *
     */
    public function run($params)
    {
        echo $params;
        echo 'this is test hook 1';
        return true;
    }

}