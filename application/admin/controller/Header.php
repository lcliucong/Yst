<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use app\admin\model\Ystheader;


class Header extends Controller{

    public function headercate(){
        $ystheader = new Ystheader;
        $list = $ystheader->where('son_father',' ')->select();
        $ejlist = $ystheader->where('is_son',1)->select();
        return $this->fetch('headercate',['data'=>$list,'datas'=>$ejlist]);
    }
    public function headeredit(Request $request){
        if($request->isGet()){
            $id = $request->get('id');
            $ystheader = new Ystheader;
            $data = $ystheader->where('id',$id)->find();
            if($data['is_son']==2){
                return $this->fetch('headeredit',['data'=>$data]);
            }else{
                return $this->fetch('headersonedit',['data'=>$data]);
            }
        }else{
            $data = $request->param();
            $ystheader = new Ystheader;
            if(!$data){
                return $this->returnres(22,'数据获取失败');
            }else{
                $res = $ystheader->where('id',$data['id'])->update($data);
                if($res){
                    return $this->returnres(1,'ok');
                }else{
                    return $this->returnres(2,'no');
                }
            }
        }
    }
    public function status(Request $request){
        $data = $request->param();

        $ystheader =Ystheader::get($data['id']);

        $ystheader->status  = $data['status'];
        $res = $ystheader->save();
        if($res){
            return $this->returnres(1,'修改成功');
        }else{
            return $this->returnres(2,'修改失败');
        }
    }

    public function headerdel(Request $request){
        $id = $request->param();
        $ystheader = new Ystheader;
        $son = $ystheader->where('son_father',$id['id'])->select();
            foreach ($son as $k=>$v){
                $arr[] = $v['id'];
            }
            array_unshift($arr,$id['id']);
            $res = Ystheader::destroy($arr);
            if($res){
                return json(['code'=>1,'message'=>'删除成功']);
            }else{
                return json(['code'=>2,'message'=>'删除失败']);
            }
            $res = Ystheader::destroy($id);
            if($res){
                return json(['code'=>1,'message'=>'删除成功']);
            }else{
                return json(['code'=>2,'message'=>'删除失败']);
            }
    }
    public function sondel(Request $request){
        $id = $request->param();
        $res = Ystheader::destroy($id['id']);
        if($res){
            return json(['code'=>1,'message'=>'删除成功']);
        }else{
            return json(['code'=>2,'message'=>'删除失败']);
        }
    }
    public function addson(Request $request){
        if($request->isGet()){
            $id = $request->get('id');
            $ystheader = new Ystheader;
            $data = $ystheader->where('id',$id)->find();
            return $this->fetch('addson',['data'=>$data]);
        }else{
            $ystheader = new Ystheader;
            $data = $request->param();
            $res = $ystheader->data($data)->allowField(true)->save();
        }

    }
    public function headeradd(){
        return $this->fetch('headeradd');
    }
    public function returnres($code='',$msg="",$data=array()){
        return json_encode(array(
            "code"=>$code,
            "msg"=>$msg,
            "data"=>$data
        ));
    }

}