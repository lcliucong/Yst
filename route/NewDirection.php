<?php
namespace route;
use think\facade\Route;

// 注册路由
Route::header('Access-Control-Allow-Headers', 'Operator_id,Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With')//允许自定义标头 Operator_id
->allowCrossDomain();//路由全局允许跨域
//商业发货数据列表
Route::rule('delivery/delist$','delivery/Delivery/deliveryList');
//商业发货数据报表导入
Route::rule('delivery/deimport$','delivery/Delivery/deliveryImport');
//商业发货数据报表导出
Route::rule('delivery/deexport$','delivery/Delivery/deliveryExport');
//商业发货列表数据删除
Route::rule('delivery/dedels$','delivery/Delivery/deliveryDel');
//商业发货列表数据添加
Route::rule('delivery/deadd$','delivery/Delivery/deliveryAdd');
//商业发货列表数据修改
Route::rule('delivery/deedit$','delivery/Delivery/deliveryEdit');
//商业发货流水流向列表
Route::rule('flowofmed/flowlistone$','delivery/FlowOfMed/floflist');
//商业发货流水流向列表编辑
Route::rule('flowofmed/flowedit$','delivery/FlowOfMed/flowEdit');
//商业发货流水流向列表删除
Route::rule('flowofmed/flowdel$','delivery/FlowOfMed/flowDel');
//商业发货流水流向列表删除
Route::rule('flowofmed/flowadd$','delivery/FlowOfMed/flowAdd');
//商业发货一级列表流水流向导入
Route::rule('flowofmed/flowdr$','delivery/FlowOfMed/flowdr');
Route::rule('aa/bb$','delivery/FlowOfMed/importTeacher');
//商业发货二级列表流水流向导入
Route::rule('flowofmed/flowtwodr$','delivery/FlowOfMed/flowtwodr');
//商业名称、产品名称，规格，产地列表
Route::rule('oplist/oplist$','delivery/MedOptions/oplist');
//商业名称、产品名称，规格，产地列表添加
Route::rule('opadd/optionadd$','delivery/MedOptions/opadd');
//商业名称、产品名称，规格，产地列表删除
Route::rule('opdel/optiondel$','delivery/MedOptions/opdel');
//商业名称、产品名称，规格，产地列表编辑
Route::rule('opedit/optionedit$','delivery/MedOptions/opEdit');
//废弃（测试添加商业发货数据）
Route::rule('delivery/dd$','delivery/Delivery/addddd');
//删除上次导入的一级、二级数据
Route::rule('deldr/deldr','delivery/FlowOfMed/deldel');
##############流水导出
Route::rule('flowdc/dclist','delivery/FlowOfMed/dclist');
##############流水数据缺失项
Route::rule('flowofmed/nrqs','delivery/FlowOfMed/missingdata');
############################################################删除
Route::rule('des/des','delivery/FlowOfMed/des');
Route::rule('a/b','reserve/Reserve/setnul');