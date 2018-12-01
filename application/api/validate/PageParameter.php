<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/11/19
 * Time: 14:32
 */

namespace app\api\validate;


class PageParameter extends BaseValidate
{
    protected $rule = [
      'page' => 'isPositiveInt',
      'size' => 'isPositiveInt'
    ];

    protected $message = [
        'page.isPositiveInt' => '页数必须是正整数',
        'size.isPositiveInt' => '每页数量必须是正整数'
    ];
}