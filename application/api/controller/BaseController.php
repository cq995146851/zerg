<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/11/10
 * Time: 15:54
 */

namespace app\api\controller;


use app\api\service\Token;
use think\Controller;

class BaseController extends Controller
{
    /**
     * 普通权限
     */
    protected function checkPrimaryScope()
    {
        Token::needPrimaryScope();
    }

    /**
     * 用户特有权限
     */
    protected function checkUserScope()
    {
        Token::needUserScope();
    }

    /**
     * 管理员权限
     */
    protected function checkSuperScope()
    {
        Token::needSuperScope();
    }
}