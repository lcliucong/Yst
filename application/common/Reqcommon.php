<?php
namespace app\common;

use think\Controller;
use tpfcore\helpers\Json;
use tpfcore\Core;
use think\Request;

class Reqcommon extends Controller
{
    // 当前类名称
    protected $class;
    // 当前控制器名称
    protected $name;
    // 请求参数
    protected $param;
    // 请求的POST参数
    protected $post;

    /**
     * 基类初始化
     */
    protected function _initialize()
    {
        // 初始化请求信息
        $this->initRequestInfo();

        // 当前类名
        $this->class = get_called_class();

        if (empty($this->name)) {
            // 当前模型名
            $name       = str_replace('\\', '/', $this->class);
            $this->name = basename($name);
            if (\think\Config::get('class_suffix')) {
                $suffix     = basename(dirname($name));
                $this->name = substr($this->name, 0, -strlen($suffix));
            }
        }
    }
    
/**
 * 初始化请求信息
 */
final private function initRequestInfo()
{
    $this->request->filter("tpfcore\helpers\StringHelper::paramFilter");
    defined('IS_POST')          or define('IS_POST',         $this->request->isPost());
    defined('IS_GET')           or define('IS_GET',          $this->request->isGet());
    defined('IS_AJAX')          or define('IS_AJAX',         $this->request->isAjax());
    defined('MODULE_NAME')      or define('MODULE_NAME',     $this->request->module());
    defined('CONTROLLER_NAME')  or define('CONTROLLER_NAME', $this->request->controller());
    defined('ACTION_NAME')      or define('ACTION_NAME',     $this->request->action());
    defined('URL')              or define('URL',             strtolower($this->request->controller() . '/' . $this->request->action()));
    defined('URL_MODULE')       or define('URL_MODULE',      strtolower($this->request->module()) . '/' . URL);
    $request = Request::instance();
    $this->param = $this->request->param();
    $this->post = $this->request->post();
}

}