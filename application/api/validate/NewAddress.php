<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/11/7
 * Time: 23:28
 */

namespace app\api\validate;


class NewAddress extends BaseValidate
{
    protected $rule = [
        'name' => 'require|isNotEmpty',
        'mobile' => 'require|isMobile',
        'province' => 'require|isNotEmpty',
        'city' => 'require|isNotEmpty',
        'country' => 'require|isNotEmpty',
        'detail' => 'require|isNotEmpty'
    ];

    protected $message = [
        'mobile.isMobile' => '手机号码格式不正确'
    ];
}