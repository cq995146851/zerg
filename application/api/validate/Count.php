<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/11/7
 * Time: 23:28
 */

namespace app\api\validate;


class Count extends BaseValidate
{
    protected $rule = [
      'count' => 'between:1,15|isPositiveInt'
    ];

    protected $message = [
      'count.isPositiveInt' => 'count只能是正整数'
    ];
}