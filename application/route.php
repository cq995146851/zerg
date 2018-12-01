<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;

Route::group('api/:version', function () {
    //轮播图
    Route::get('banner', 'api/:version.Banner/getBanner');
    //主题
    Route::get('theme', 'api/:version.Theme/getSimpleThemes');
    Route::get('theme/:id', 'api/:version.Theme/getThemeDetail');
    //最新产品
    Route::get('product/recent', 'api/:version.Product/getRecent');
    //商品详情
    Route::get('product', 'api/:version.Product/getDetail');
    //所有分类
    Route::get('category', 'api/:version.Category/getCategories');
    //根据分类获取商品
    Route::get('produce/by_category', 'api/:version.Product/getAllByCategory');
    //根据code换取token
    Route::post('user/get_token', 'api/:version.User/getToken');
    //校验token
    Route::post('user/verify_token', 'api/:version.User/verifyToken');
    //保存用户信息
    Route::post('user/save_info', 'api/:version.User/saveInfo');
    //获取用户信息
    Route::get('user', 'api/:version.User/getInfo');
    //用户地址管理
    Route::get('address', 'api/:version.Address/address');
    Route::post('address', 'api/:version.Address/addOrUpdateAddress');
    //下单
    Route::post('order', 'api/:version.Order/placeOrder');
    //生成支付预订单
    Route::post('pay/pre_order', 'api/:version.Pay/preOrder');
    //微信服务器回调地址
    Route::post('pay/notify', 'api/:version.Pay/receiveNotify');

    //获取用户所有简要订单
    Route::get('order/by_user', 'api/:version.Order/getSummaryByUser');
    //获取用户某个订单详情
    Route::get('order/get_detail', 'api/:version.Order/getDetail');
});
