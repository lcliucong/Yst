<?php
namespace route;
use think\facade\Route;

// 注册路由
Route::header('Access-Control-Allow-Headers', 'Operator_id,Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With')//允许自定义标头 Operator_id
->allowCrossDomain();//路由全局允许跨域
//菜单栏
Route::rule('menu/cate$','mainmenu/mainmenu/catetree');
//测试
Route::rule('article$','mainmenu/common/test');
//导出报表
Route::rule('excel/exportexcel','mainmenu/ExcOperation/dc');
//导入报表
Route::rule('excel/importexcel','mainmenu/ExcOperation/dr');
//库存列表
Route::rule('stock/list$','stock/Stock/goodslist');
//库存添加
Route::rule('stock/add$','stock/Stock/goodsadd');
//删除
Route::rule('stock/del$','stock/Stock/goodsdel');
//修改
Route::rule('stock/edit$','stock/Stock/goodsedit');
//库存导入
Route::rule('stock/importexcel$','stock/Stock/dr');
//库存流水列表
Route::rule('direction/list$','stock/Stock/direction');
//库存流水编辑
Route::rule('direction/edit$','stock/Stock/direedit');
//库存流水删除
Route::rule('direction/del$','stock/Stock/diredel');
//流水添加
Route::rule('direction/add$','stock/Stock/direadd');
//流水导入
Route::rule('direction/importexcel$','stock/Stock/stockdr');
//psi列表/查询
Route::rule('psi/psilist$','psi/reportpsi/psilist');
//psi添加
Route::rule('psi/psiadd$','psi/reportpsi/psiadd');
//psi删除
Route::rule('psi/psidel$','psi/reportpsi/psidel');
//psi编辑
Route::rule('psi/psiedit$','psi/reportpsi/psiedit');
//psi导入报表
Route::rule('psi/psiexcimport$','psi/reportpsi/psidr');
//psi 一级配送商业列表/查询
Route::rule('psi/psionelist$','psi/reportpsi/psionelist');
//一级配送商业删除
Route::rule('psi/psionedel$','psi/reportpsi/psionedel');
//一级配送商业添加
Route::rule('psi/psioneadd$','psi/reportpsi/psioneadd');
//一级配送商业编辑
Route::rule('psi/psioneedit$','psi/reportpsi/psioneedit');
//二级配送商业列表/搜索
Route::rule('psi/psitwolist$','psi/reportpsi/psitwolist');
//一级配送商业删除
Route::rule('psi/psitwodel$','psi/reportpsi/psitwodel');
//二级配送商业添加
Route::rule('psi/psitwoadd$','psi/reportpsi/psitwoadd');
//二级配送商业编辑
Route::rule('psi/psitwoedit$','psi/reportpsi/psitwoedit');
//实销模式列表
Route::rule('acssale/salelist$','actualsales/ActSales/acslist');
######################################

//新库存列表数据渲染
Route::rule('medreserve/getstlist$','reserve/Reserve/medLists');
//新库存列表数据处理
Route::rule('medreserve/stlist$','reserve/Reserve/mstlist');
//新库存列表修改
Route::rule('medreserve/stedit$','reserve/Reserve/mstEdit');
//新库存列表添加
Route::rule('medreserve/stadd$','reserve/Reserve/mstAdd');
//新库存列表删除
Route::rule('medreserve/stdel$','reserve/Reserve/mstDel');
Route::rule('medreserve/deldel','reserve/Reserve/deldel');
//期初导入
Route::rule('medreserve/serdr','reserve/Reserve/serdr');

Route::rule('direction/add','stock/Stock/add');
Route::rule('direction/addss','stock/Stock/goodsadds');
Route::rule('direction/acc','stock/Stock/acc');
Route::rule('direction/dels','stock/Stock/dels');