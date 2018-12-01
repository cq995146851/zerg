<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/10/31
 * Time: 21:08
 */

namespace app\lib\exception;


class ParameterException extends BaseException
{
    public $code = 400;
    public $msg = '参数错误';
    public $errorCode = 10000;
}