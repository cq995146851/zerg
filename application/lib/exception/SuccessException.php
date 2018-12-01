<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/11/9
 * Time: 15:30
 */

namespace app\lib\exception;


class SuccessException extends BaseException
{
    public $code = 201;
    public $msg = 'success';
    public $errorCode = 0;
}