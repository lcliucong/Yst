<?php
namespace app\mainmenu\model;
use think\Db;
use think\Model;
use PDO;

class ModelBase extends Model
{
    /****
     * createBy phpstorm
     * auth: lc
     * date: 2021/11/19
     * time: 09:14
     * model BASE
     */
    public $autoWriteTimestamp=true;
    // 当前类名称
    public $class;
    // 查询对象
    private static $ob_query = null;
    /**
     * 基类初始化
     */
    protected function initialize()
    {
        // 当前类名

        $this->class = get_called_class();
    }
    /**
     * @param array $where
     * @return mixed
     * goods表数据联查
     */
    public function getlist($where=[],$name='',$b=''){
        $result = $name->alias('a')->leftjoin("$b b",'a.goods_id=b.id')->
        where($where)->field('a.*')->order('a.id','desc')->select()->toArray();
        return $result;
    }
    /**
     * @param array $where
     * 真实删除
     */
    public function listdel($name,$id=[]){
//        var_dump($id);die;
        return $name::where('id','in',$id)->delete();
    }
    /**
     * @param array $data
     * @param string $field
     * @param string $name
     */
    public function editlist($name='',$data=[]){
//        var_dump($name);die;
//        var_dump($data);die;
        $result = $name->where('id',$data['id'])->update($data);
        return $result;
    }
    /**
     * @param string $name 本表名称
     * @param string $b 链接表明，本方法指代goods表
     * @param array $where 查询名称
     * @param string $data requests参数
     * @param string $field 本表字段名称
     * @return mixed
     */
    public function sellist($name='',$b='',$where=[],$data='',$field=''){
        $result = $name->alias('a')->//leftjoin("$b b",'a.goods_id=b.id')->
//        where('a.goods_id','=',$data)->
        where("concat($field) LIKE '%$data%'")->  //"concat(username,company_name,labels) LIKE '%$keyword%' "
        order('a.id','desc')->select();
        return $result;
    }
    public function addlistgetid($data=[],$sequence = null,$field=true){
        $result = $this->allowField($field)->isUpdate(false)->insertGetId($data);
        return $result;
    }
    public function addlist($data=[],$sequence = null,$field=true){
        $result = $this->allowField($field)->isUpdate(false)->data($data)->save();
        return $result;
    }
    /**
     *
     * toArray()
     */
    public function toarr($ar){
        $isarr = is_object($ar) ? get_object_vars($ar) : $ar;
        $toarr = array();
        foreach ($isarr as $k=>$v){
            $v = (is_array($v) || is_object($v)) ? $this->toarr($v) : $v;
            $toarr[$k] = $v;
        }
        return $toarr;
    }
    /**
     * 取得数据表的字段信息
     * @access public
     * @param string $tableName
     * @return array
     */
    final public function getFields($tableName)
    {
        list($tableName) = explode(' ', $tableName);
        if (false === strpos($tableName, '`')) {
            if (strpos($tableName, '.')) {
                $tableName = str_replace('.', '`.`', $tableName);
            }
            $tableName = '`' . $tableName . '`';
        }
        $sql    = 'SHOW COLUMNS FROM ' . $tableName;
        $pdo    = Db::query($sql, [], false, true);
        $result = $pdo->fetchAll(PDO::FETCH_ASSOC);
        $info   = [];
        if ($result) {
            foreach ($result as $key => $val) {
                $val                 = array_change_key_case($val);
                $info[$val['field']] = [
                    'name'    => $val['field'],
                    'type'    => $val['type'],
                    'notnull' => (bool) ('' === $val['null']), // not null is empty, null is yes
                    'default' => $val['default'],
                    'primary' => (strtolower($val['key']) == 'pri'),
                    'autoinc' => (strtolower($val['extra']) == 'auto_increment'),
                ];
            }
        }
        return $info;
    }

    /**
     * 新增数据,是否返回自增的主键
     * @access public
     * @param stirng|array $data 要操作的数据
     * @param string  $sequence     自增序列名
     * @return boolean
     */
    final public function addObj($data = [], $sequence = null)
    {
        $result= $this->allowField(true)->isUpdate(false)->save($data, $where=[], $sequence);
//        if($getLastInsID){
//            if(method_exists($this, "getQuery")){
//                return $this->getQuery()->getLastInsID($sequence);
//            }else{
//                return $this->db()->getLastInsID($sequence);
//            }
//        }
        return $result;
    }

    /**
     * 更新数据
     * @access public
     * @param stirng|array $data 要操作的数据
     * @param string|array $where 操作的条件
     * @return boolean
     */
    final public function updateData($where = [], $data = [], $field = true, $sequence = null)
    {

        return $this->allowField($field)->isUpdate(true)->save($data, $where, $sequence);

    }

    /**
     * 聚合函数-统计数据
     * @access public
     * @param string|array $where 操作的条件
     * @param stirng $stat_type 统计类型，默认为count，统计条件
     * @return mixed
     */
    final public function getStatistics($where = [], $stat_type = 'count', $field = 'id')
    {

        return $this->where($where)->$stat_type($field);
    }

    /**
     * 保存多个数据到当前数据对象 
     * @access public
     * @param array   $data_list 数据
     * @param boolean $replace 是否自动识别更新和写入
     * @return array|false
     */
    final public function opObjects($data_list = [], $replace = false)
    {

        return $this->saveAll($data_list, $replace);
    }

    public function p($data){
        // 定义样式
        $str=   '<pre style="display: block;
                padding: 9.5px;margin: 50px auto 0 auto;
                font-size: 15px;width:800px;
                line-height: 1.42857;color: #F2F2F2;
                word-break: break-all;word-wrap: break-word;
                background-color: #336699;font-family: 幼圆;
                border: 1px solid #CCC;border-radius: 5px;">';
        // 如果是boolean或者null直接显示文字；否则print
        if (is_bool($data)) {
            $show_data=$data
                ?
                'true'
                :
                'false';

        }elseif (is_null($data)) {

            $show_data='null';

        }else{
            $show_data=print_r($data,true);
        }
        $str.=$show_data;
        $str.='</pre>';
        echo $str;
    }

    /**
     * 设置某个字段值
     * @access public
     * @param array   $where 条件
     * @param string $field 字段名
     * @param string $value 新的值
     * @return boolean
     */
    final public function setFieldValue($where = [], $field = '', $value = '')
    {
        return $this->updateObject($where, [$field => $value]);
    }

    /**
     * 删除数据（分真实删除与更改字段状态）
     * @access public
     * @param array   $where 条件
     * @param string $is_true 是否真实删除
     * @return boolean
     */
    final public function deleteObject($where = [], $is_true = false , $column = null)
    {
        return $is_true ? $this->where($where)->delete() : $this->setFieldValue($where, is_null($column)?DATA_STATUS:$column, DATA_DELETE);
    }

    /**
     * 得到某个列的数组
     * @access public
     * @param array   $where 条件
     * @param string $field 字段名 多个字段用逗号分隔
     * @param string $key   索引
     * @return array
     */
    final public function getColumns($where = [], $field = '', $key = '')
    {
        return Db::name($this->name)->where($where)->column($field, $key);
    }

    /**
     * 得到某个字段的值
     * @access public
     * @param array   $where 条件
     * @param string $field   字段名
     * @param mixed  $default 默认值
     * @param bool   $force   强制转为数字类型
     * @return mixed
     */
    final public function getColumnValue($where = [], $field = '', $default = null, $force = false)
    {
        return Db::name($this->name)->where($where)->value($field, $default, $force);
    }

    /**
     * 查找单条记录
     * @access public
     * @param array   $where 条件
     * @param string $field   字段名
     * @return mixed
     */
    final public function getOneObject($where = [], $field = true)
    {
        return $this->where($where)->field($field)->find();
    }

    /**
     * 获取数据列表
     * @access public
     * @param array   $where 条件
     * @param string $field   字段名
     * @param string|array $order 排序字段
     * @param array $paginate   分布处理参数
     * @param array $join   联合查询参数
     * ["join"=>["__CATEGORY__","__USER__"],"condition"=>["__CATEGORY__.id=__POSTS__.cateid","__USER__.id=__POSTS__.uid"],"type"=>["left","left"]]
     * 或["join"=>["__CATEGORY__"],"condition"=>["__CATEGORY__.id=__POSTS__.cateid"],"type"=>["left"]]
     * 或["join"=>"__CATEGORY__","condition"=>"__CATEGORY__.id=__POSTS__.cateid","type"=>"left"]
     * @param array $group   分组查询参数
     * @param mixed $limit   查询条数
     * @param mixed $data   数据集
     * @return mixed
     */
    final public function getobjlist($where = [], $field = true, $order = '', $paginate = array('rows' => null, 'simple' => false, 'config' => []), $join = array('join' => null, 'condition' => null, 'type' => 'INNER'), $group = array('group' => '', 'having' => ''), $limit = null, $data = null,$expire=0)
    {
        if(isset($where['page'])) unset($where['page']);

        $where=parse_str($where);

        $field=parse_str($field);

        $join=parse_str($join);

        $order=parse_str($order);

        $group=parse_str($group);

        $paginate['simple'] = empty($paginate['simple']) ? false   : $paginate['simple'];

        $paginate['config'] = empty($paginate['config']) ? []      : $paginate['config'];

        $join['condition']  = empty($join['condition'])  ? null    : $join['condition'];

        $join['type']       = empty($join['type'])       ? 'INNER' : $join['type'];

        $group['having']    = empty($group['having'])    ? ''      : $group['having'];

        self::$ob_query = $this->where($where)->order($order);

        if(!empty($join['join'])){

            if(is_array($join['join'])){

                foreach ($join['join'] as $key => $value) {

                    self::$ob_query = self::$ob_query->join($join['join'][$key], $join['condition'][$key], $join['type'][$key]);

                }

            }else{

                self::$ob_query = self::$ob_query->join($join['join'], $join['condition'], $join['type']);

            }

        }

        $except = false;

        is_array($field) && isset($field[1]) && $except = true;

        self::$ob_query = self::$ob_query->field($field,$except);

        !empty($group['group'])   && self::$ob_query = self::$ob_query->group($group['group'], $group['having']);

        !empty($limit)            && self::$ob_query = self::$ob_query->limit($limit);

        $cache_tag = Cache::get_cache_tag($this->name, $join);

        $cache_key = Cache::get_cache_key($this->name, $where, $field, $order, $paginate, $join, $group, $limit, $data);

        if (\think\Cache::has($cache_key) && Cache::check_cache_tag($cache_tag)) {

            return unserialize(\think\Cache::get($cache_key));

        } else {
            $result_data = !empty($paginate['rows']) ? self::$ob_query->paginate($paginate['rows'], $paginate['simple'], $paginate['config']) : self::$ob_query->select($data);

            HTML_CACHE_ON && \think\Cache::tag($cache_tag)->set($cache_key, serialize($result_data),$expire) && Cache::set_cache_tag($cache_tag,$expire);

            return $result_data;
        }
    }
    
}