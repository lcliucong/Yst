<?php
namespace app\reserve\model;
use app\mainmenu\model\ModelBase;
use think\Db;

class MedReserve extends ModelBase{
    /**
     * createBy phpstorm
     * auth : lc
     * Date : 2021/11/30
     * Time : 15:15
     */
    protected function initialize()
    {
        parent::initialize();
    }
    public function meselects($data,$field){
            $name = $data['name'];
            $month = $data['month_time'];
            if(empty($month)){
                $time = date("Y-m");
                $time1 = $time.'-01';
                $time2 = date("t",strtotime($time));
                $time2 = $time.'-'.$time2;
            }else{
                $time1 = $month.'-01';
                $time2 = date("t",strtotime($month));
                $time2 = $month.'-'.$time2;
            }
            if(empty($name)){
                $result = $this->where('operation_time','between time',[$time1,$time2])->order('id','desc')->select();
                $counts='nocounts';
                return [$result,$counts];
            }else{
                $result = $this->where('operation_time','between time',[$time1,$time2])->where("concat($field) LIKE '%$name%'")->order('id','desc')->select();
                $counts = $this->where('operation_time','between time',[$time1,$time2])->where("concat($field) LIKE '%$name%'")->sum('stock_num');
                if(empty($result)){
                    $counts=0;
                }
                return [$result,$counts];
            }
    }
    public function medstlist(){
        $time = date("Y-m");
        $time1 = $time.'-01';
        $time2 = date("t",strtotime($time));
        $time2 = $time.'-'.$time2;
        return Db::name('med_reserve')->where('operation_time','between time',[$time1,$time2])->order('id desc')->select();
    }
    /**
     * @param string $data
     * @param bool $field
     * @return mixed
     */
    public function medstadd($data=[],$field=true){
        $result = $this->allowField($field)->isUpdate(false)->data($data)->save();
        return $result;
    }
    /**
     * @param string $time
     * @param string $time2
     * @return mixed
     * 时间查询  废弃
     */
    public function medtimesele($time='',$time2=''){
        $time.='-01';
        $result = $this->whereTime('operation_time','between',[$time,$time2])->order('id desc')->select()->toArray();
        return $result;
    }

    /**
     * @param $data
     * @param $field
     * @return mixed
     * 普通查询 废弃
     */
    public function medcommsele($data){
            $where = [
                ['company_name','like',"$data%"],
                ['names','like',"$data%"],
                ['fac_specs','like',"$data%"],
                ['measuring_unit','like',"$data%"],
                ['origin','like',"$data%"],
                ['stock_num','like',"$data%"],
                ['batch_num','like',"$data%"]

            ];
        $result = $this->whereOr($where)->order('id','desc')->select()->toArray();
        return $result;
    }

    /**
     * @param string $time 月初时间
     * @param string $time2 月末时间
     * @param string $data 上传的查询数据
     * @param string $field 查询字段
     * @return mixed
     * 选定时间范围后的内容查询  废弃
     */
    public function medallsele($time, $time2,$data,$field){
        $time.='-01';
        $where = [
            ['names','like',"$data%"],
            ['fac_specs','like',"$data%"],
            ['measuring_unit','like',"$data%"],
            ['origin','like',"$data%"],
            ['stock_num','like',"$data%"],
            ['batch_num','like',"$data%"]
        ];
        $result = $this->whereTime('operation_time','between',["$time","$time2"])->where('company_name','like',"$data%")->whereOr($where)->order('id','desc')->select();
        return $result;
    }
}