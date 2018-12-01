<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/11/8
 * Time: 23:17
 */

namespace app\api\service;


use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\ParameterException;
use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

class Token
{
    /**
     * @return string Token值
     */
    protected function generateToken()
    {
        $rand_char = getRandChar(32);
        $timestamp = $_SERVER['REQUEST_TIME'];
        $token_salt = config('secure.token_salt');
        return md5($rand_char . $timestamp . $token_salt);
    }

    /**
     * @return user_id
     * @throws TokenException
     */
    public static function getCurrUserId()
    {
        return self::getCurrTokenValByKey('user_id');
    }

    /**
     * @param 键
     * @return 键值
     */
    public static function getCurrTokenValByKey($key)
    {
        $token = Request::instance()->header('token');
        $cacheValue = Cache::get($token);
        if (!$cacheValue) {
            throw new TokenException();
        } elseif (!is_array($cacheValue)) {
            $cacheValue = json_decode($cacheValue, true);
        }
        if (!array_key_exists($key, $cacheValue)) {
            throw new Exception('根据token查询缓存值异常');
        }
        return $cacheValue[$key];
    }

    public static function needPrimaryScope()
    {
        self::baseScope();
    }

    public static function needUserScope()
    {
        self::baseScope(ScopeEnum::USER);
    }

    public static function needSuperScope()
    {
        self::baseScope(ScopeEnum::SUSER);
    }

    private static function baseScope($scope_enum = '')
    {
        $scope = self::getCurrTokenValByKey('scope');
        if (!$scope) {
            throw new TokenException();
        }
        switch ($scope_enum) {
            case ScopeEnum::USER :
                if ($scope != ScopeEnum::USER) {
                    throw new ForbiddenException();
                }
                break;
            case ScopeEnum::SUSER:
                if ($scope != ScopeEnum::SUSER) {
                    throw new ForbiddenException();
                }
                break;
            default:
                if ($scope < ScopeEnum::USER) {
                    throw new ForbiddenException();
                }
                break;
        }
        return true;
    }

    public static function checkOperateValid($user_id)
    {
        if (!$user_id) {
            throw new ParameterException([
               'code' => 404,
               'msg' => '用户编号必须传递'
            ]);
        }
        return self::getCurrUserId() == $user_id;
    }

    /**
     * 校验token
     */
    public static function verify($token)
    {
        return Cache::get('token') == $token;
    }
}