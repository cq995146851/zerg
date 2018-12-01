<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/11/10
 * Time: 16:38
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;

class OrderPlace extends BaseValidate
{
    protected $rule = [
        'products' => 'require|checkProducts'
    ];

    protected $singleRule = [
        'product_id' => 'require|isPositiveInt',
        'count' => 'require|isPositiveInt'
    ];

    protected function checkProducts($value)
    {
        if (!is_array($value)) {
            throw new ParameterException([
                'msg' => '商品参数错误'
            ]);
        }
        if (empty($value)) {
            throw new ParameterException([
                'msg' => '商品不能为空'
            ]);
        }
        foreach ($value as $v) {
            $this->checkProduct($v);
        }
        return true;
    }

    private function checkProduct($value)
    {
        $validate = new BaseValidate($this->singleRule);
        if(!$validate->check($value)) {
            throw new ParameterException([
               'msg' => '商品列表参数错误'
            ]);
        }
        return true;
    }
}