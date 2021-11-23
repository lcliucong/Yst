<?php
namespace app\admin\validate;

use think\Validate;
class Indexshow extends Validate
{
    protected $rule = [
        'notice_title'  =>  'require|max:50',
        'notice_content' =>  'max:5000',
        'notice_auth'=>'max:20'
    ];
    protected $message = [
        'notice_title.require' =>'请输入标题',
        'notice_title.max' =>'标题最大长度为50字符',
        'notice_content.max'=>'内容最大长度为5000字符',
        'notice_auth.max'=>'作者最大长度为20字符'
    ];
    protected $scene = [

    ];
}