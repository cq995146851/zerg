<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/11/9
 * Time: 14:06
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\User as UserModel;
use app\api\service\Token;
use app\api\validate\NewAddress;
use app\lib\exception\SuccessException;
use app\lib\exception\UserException;
use think\Exception;

class Address extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope'  =>  ['only'=> 'addorupdateaddress'] //方法名必须全小写
    ];

    /**
     * @return 管理地址响应信息
     */
    public function addOrUpdateAddress()
    {
        //验证表单
        $validate = new NewAddress();
//        $validate->goCheck();
        //获取user_id
        $user_id = Token::getCurrUserId();
        //获取用户信息
        $user = UserModel::getUserById($user_id);
        if (!$user) {
            throw new UserException();
        }
        //过滤表单信息
        $data = $validate->getDataByRule(input('post.'));
        //判断当前用户地址是否存在
        $address = $user->address;
        //添加或更新地址
        if (!$address) {
            $user->addAddress($data);
        } else {
            $user->updateAddress($data);
        }
        return json(new SuccessException(), 201);
    }

    /**
     * 获取地址信息
     */
    public function address()
    {
        $user_id = Token::getCurrUserId();
        $user = UserModel::getUserById($user_id);
        if (!$user) {
            throw new UserException();
        }
        $address = $user->address;
        if (!$address) {
            throw new UserException([
                'msg' => '用户地址不存在',
                'errorCode' => 600001
            ]);
        }
        return [
            'address' => $address
        ];

    }
}