<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/11/8
 * Time: 16:13
 */

namespace app\lib\exception;


class WxException extends BaseException
{
    public $code = 400;
    public $msg = 'wechat unknown error';
    public $errorCode = 999;
}