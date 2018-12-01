<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/10/31
 * Time: 13:12
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;
use think\Exception;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    //去检验公共方法
    public function goCheck()
    {
        $request = Request::instance();
        $params = $request->param();
        if (!$this->batch()->check($params)) {
            throw new ParameterException([
                'msg' => $this->error
            ]);
        }
        return true;

    }

    //必须是正整数
    protected function isPositiveInt($value)
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        }
        return false;
    }

    //不能为空
    protected function isNotEmpty($value, $rule = '', $data = '', $field = '')
    {
        return empty($value) ? false : true;
    }

    //手机号验证
    protected function isMobile($value)
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        return preg_match($rule, $value) ? true : false;
    }

    public function getDataByRule($arr = array())
    {
        if (array_key_exists('user_id', $arr) || array_key_exists('uid', $arr)) {
            throw new ParameterException([
               'msg' => '传递信息中含有user_id或uid等非法参数'
            ]);
        }

        $newArr = [];
        foreach ($this->rule as $key => $value) {
            $newArr[$key] = $arr[$key];
        }
        return $newArr;
    }
}








