<?php

namespace app\api\model;

use app\lib\exception\UserException;
use think\Model;

class User extends Model
{
    //获取当前用户所有地址
    public function address()
    {
        return $this->hasOne('UserAddress', 'user_id', 'id');
    }

    //根据openid获取user
    public static function getUserByOpenid($openid)
    {
        return self::where('openid', $openid)->find();
    }

    //根据user_id获取user
    public static function getUserById($user_id)
    {
        return self::where('id', $user_id)->find();
    }

    //添加地址
    public function addAddress($data)
    {
        return $this->address()->save($data);
    }

    //更新地址
    public function updateAddress($data)
    {
        return $this->address->save($data);
    }

    /**
     * 保存用户信息
     */
    public function saveInfo ($userInfo)
    {
        $this->nickname = $userInfo['nickName'];
        $this->avatar = $userInfo['avatarUrl'];
        $this->save();
        return true;
    }
}
