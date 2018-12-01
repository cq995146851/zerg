<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/11/9
 * Time: 15:04
 */

namespace app\lib\exception;


class UserException extends BaseException
{
    public $code = 404;
    public $msg = '用户不存在';
    public $errorCode = 60000;
}