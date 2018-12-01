<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/11/8
 * Time: 14:33
 */

namespace app\api\validate;


class TokenGet extends BaseValidate
{
    protected $rule = [
      'code' => 'require|isNotEmpty'
    ];

    protected $message = [
      'code.require' => 'code必须传递',
      'code.isNotEmpty' => 'code不能为空'
    ];
}