<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/11/8
 * Time: 14:31
 */

namespace app\api\controller\v1;


use app\api\service\Token;
use app\api\service\UserToken;
use app\api\validate\TokenGet;
use app\lib\exception\ParameterException;
use app\api\model\User as UserModel;
use app\lib\exception\UserException;
use think\Request;

class User
{
    /**
     * @url /api/v1/user/get_token
     * @http POST
     * @param $code
     * @return token信息
     */
    public function getToken($code = '')
    {
        (new TokenGet())->goCheck();
        $user_token = new UserToken($code);
        $token = $user_token->get();
        return [
            'token' => $token
        ];
    }

    /**
     * 校验token
     */
    public function verifyToken($token = '')
    {
        $data = [
          'isValid' => true
        ];
        if (!$token) {
            throw new ParameterException([
                'msg' => 'token必须传递'
            ]);
        }
        if (!Token::verify($token)) {
            $data = [
                'isValid' => false
            ];
        }
        return $data;
    }

    /**
     * 保存个人信息
     */
    public function saveInfo ()
    {
        $userInfo = Request::instance()->post();
        $user_id = Token::getCurrUserId();
        if (!$user_id) {
            throw new UserException();
        }
        UserModel::find($user_id)->saveInfo($userInfo);
    }

    /**
     * 获取用户信息
     */
    public function getInfo ()
    {
        $userInfo = Request::instance()->post();
        $user_id = Token::getCurrUserId();
        $user = UserModel::getUserById($user_id);
        return [
          'userInfo' => $user
        ];
    }
}