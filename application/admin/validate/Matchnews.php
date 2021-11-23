<?php
namespace app\admin\validate;

use think\Validate;
class Matchnews extends Validate
{
    protected $rule = [
        'news_title'  =>  'require|max:50',
        'news_content' =>  'max:5000',
        'news_auth'=>'max:20'
    ];
    protected $message = [
        'news_title.require' =>'请输入标题',
        'news_title.max' =>'标题最大长度为50字符',
        'news_content.max'=>'内容最大长度为5000字符',
        'news_auth.max'=>'作者最大长度为20字符'
    ];
    protected $scene = [

    ];
}