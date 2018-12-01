<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/10/31
 * Time: 17:14
 */

namespace app\lib\exception;


use think\Exception;

class BaseException extends Exception
{
    public $code;
    public $msg;
    public $errorCode;

    public function __construct($parameter = [])
    {
        if (!is_array($parameter)) {
            return;
        }
        if (array_key_exists('code', $parameter)) {
            $this->code = $parameter['code'];
        }
        if (array_key_exists('msg', $parameter)) {
            $this->msg = $parameter['msg'];
        }
        if (array_key_exists('error_code', $parameter)) {
            $this->errorCode = $parameter['error_code'];
        }
    }
}