<?php 
namespace app\admin\controller;
use think\Controller;
use app\admin\model\News as mNews;
use think\Request;
use think\Loader;


class News extends Controller
{
    public function newsList(){
        #查询
        $news = new mNews();
        $news_list = $news::all();
        return $this->fetch('news-list',['news_list'=>$news_list]);
    }
    #添加
    public function newsAdd(Request $request){
        #get方式访问,渲染页面
        if($request->isGet()){
            return $this->fetch('news-add');
        }else{
            #post 方式访问页面
            #获取前端上传的数据
            $data = $request->param();
            $validate = Loader::validate('News');
            $result = $validate->check($data);
            if($result){
                #插入数据
                $news = new mNews();
                $res = $news::create($data);
                if($res){
                    return json(['code'=>1,'message'=>'添加成功']);
                }else{
                    return json(['code'=>2,'message'=>'添加失败']);
                }
            }else{
                $errmsg = $validate->getError();
                return json(['code'=>2,'message'=>$errmsg]);
            }
       
        }
       
    }
    #修改/状态修改
    public function newsUpdate(Request $request){
        if($request->isGet()){
            #修改前 先获取本条数据的内容
            $id = $request->param();
            $res = mNews::get($id);
            return $this -> fetch('news-edit',['news'=>$res]);
        }else{
            $data = $request->param();
            if(isset($data['type']) && $data['type']=='newsUp'){
                $validate = Loader::validate('News');
                $result = $validate->scene('update')->check($data);
                if($result){
                    $news = new mNews;
                    $res = $news->allowField(true)->save($data,['news_id'=>$data['news_id']]);
                    if($res) {
                        return json(['code'=>1,'message'=>'修改成功']);
                    }else{
                        return json(['code'=>2,'message'=>'修改失败']);
                    }
                }else{
                    return json(['code'=>2,'message'=>$validate->getError()]);
                }
            }else{
                    $res = mNews::update($data);
                    if($res) {
                        return json(['code'=>1,'message'=>'修改成功']);
                    }else{
                        return json(['code'=>2,'message'=>'修改失败']);
                    }
               
            }
            
           
         }
    }
    #删除
    public function newsDel(Request $request){
       $id = $request->param();
       $res = mNews::destroy($id['news_id']);
       if($res){
           return json(['code'=>1,'message'=>'删除成功']);
       }else{
           return json(['code'=>2,'message'=>'删除失败']);
       }
       
    }

}
?>