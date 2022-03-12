<?php
namespace app\admin\validate;
use think\Validate;

class Chanpin extends Validate{
    protected $rule = array(
        'chanpin' => 'require|unique:chanpin', // 必填、最长10个字符、不能重复

    );

    protected $message = array(
        'chanpin.require'=>'产品必填',
        'chanpin.unique'=>'不能重复',
    );

    protected $scene = array(
        'add' => array('chanpin'),
    );

}