<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/10/31
 * Time: 13:12
 */

namespace app\api\validate;


class IdMustBePositiveInt extends BaseValidate
{
    protected $rule = [
      'id' => 'require|isPositiveInt'
    ];

    protected $message = [
      'id.require' => 'id必须传递',
      'id.isPositiveInt' => 'id必须是正整数'
    ];
}