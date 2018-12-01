<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/11/10
 * Time: 14:52
 */

namespace app\lib\exception;


class ForbiddenException extends BaseException
{
    public $code = 403;
    public $msg = '权限不足';
    public $errorCode = 10001;
}