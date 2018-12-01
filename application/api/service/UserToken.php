<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/11/8
 * Time: 14:44
 */

namespace app\api\service;


use app\lib\enum\ScopeEnum;
use app\lib\exception\TokenException;
use app\lib\exception\WxException;
use think\Exception;
use app\api\model\User;

class UserToken extends Token
{
    protected $appId;
    protected $appSecret;
    protected $code;
    protected $loginUrl;

    /**
     * @param $code
     * 地址初始化
     */
    public function __construct($code)
    {
        $this->code = $code;
        $this->appid = config('wx.app_id');
        $this->appSecret = config('wx.app_secret');
        $this->loginUrl = sprintf(config('wx.login_url'), $this->appid, $this->appSecret, $this->code);
    }

    /**
     * @param $code
     * @return 用户token信息
     */
    public function get()
    {
        $wx_result = curl_get($this->loginUrl);
        $wx_result = json_decode($wx_result, true);;
        if (empty($wx_result)) {
            throw new Exception('获取session_key及openID时异常，微信内部错误');
        } elseif (array_key_exists('errcode', $wx_result)) {
            $this->processLoginError($wx_result);
        }
        return $this->grantToken($wx_result);
    }

    private function grantToken($wx_result)
    {
        $user = User::getUserByOpenid($wx_result['openid']);
        if (!empty($user)) {
            $user_id = $user->id;
        } else {
            $user_id = User::create([
                'openid' => $wx_result['openid']
            ]);
        }
        return $this->saveToCache($wx_result, $user_id);
    }

    private function saveToCache($wx_result, $user_id)
    {
        $key = $this->generateToken();
        $value = $wx_result;
        $value['user_id'] = $user_id;
        $value['scope'] = ScopeEnum::USER;
        $expire_in = config('setting.token_expire_in');
        $result = cache($key, json_encode($value), $expire_in);
        if (!$result) {
            throw new TokenException([
               'msg' => '服务器缓存异常',
               'errorCode' =>  10005
            ]);
        }
        return $key;
    }

    private function processLoginError($wx_result)
    {
        throw new WxException([
            'msg' => $wx_result['errmsg'],
            'errorCode' => $wx_result['errcode']
        ]);
    }
}