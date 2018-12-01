<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/11/10
 * Time: 17:48
 */

namespace app\lib\exception;


class OrderException extends BaseException
{
    public $code = 404;
    public $msg = '订单不存在';
    public $errorCode = 80000;
}