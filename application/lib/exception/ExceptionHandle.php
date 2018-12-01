<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/10/31
 * Time: 17:16
 */

namespace app\lib\exception;


use think\exception\Handle;
use think\Log;
use think\Request;

class ExceptionHandle extends Handle
{
    public $code;
    public $msg;
    public $errorCode;

    public function render(\Exception $e)
    {
        if($e instanceof BaseException) {
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        } else {
            if(config('app_debug')) {
                return parent::render($e);
            }
            $this->code = '500';
            $this->msg = '服务器未知错误';
            $this->errorCode = '999';
            //记录日志
            $this->recordErrorLog($e);
        }
        $result = [
            'error_code' => $this->errorCode,
            'msg' => $this->msg,
            'url' => Request::instance()->url()
        ];
        return json($result, $this->code);
    }

    private function recordErrorLog(\Exception $e)
    {
        Log::init([
           'type' => 'File',
            'path' => LOG_PATH,
            'level' => ['error']
        ]);
        Log::record($e->getMessage(), 'error');
    }
}