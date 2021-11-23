<?php 
namespace app\admin\validate;
use think\Validate;

class News extends Validate
{
    protected $rule = [
        'news_title|新闻标题' =>'require|length:5,10',
        'news_content|新闻内容'=>'require|max:500',
        'news_auth|新闻作者' =>'require|length:2,10'
    ];
    protected $scene = [
        'statusZt' => ['news_title','news_content','news_auth'],
        'update' => ['news_title','news_content']
    ];
    protected $message = [
        'news_title.require' =>'请输入新闻标题',
        'news_title.length'=>'新闻标题的长度必须在5-10个字符之间',
        'news_content.require'=>'请输入新闻内容',
        'news_content.max' =>'新闻内容最大长度为500个字符',
        'news_auth.require' =>'请输入新闻作者'
    ];
}








?>