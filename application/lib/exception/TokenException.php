<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/11/8
 * Time: 23:31
 */

namespace app\lib\exception;


class TokenException extends BaseException
{
    public $code = 401;
    public $msg = 'Token不存在或者已经失效';
    public $errorCode = 10001;
}