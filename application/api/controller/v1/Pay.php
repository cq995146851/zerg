<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/11/11
 * Time: 14:19
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\Notify;
use app\api\validate\IdMustBePositiveInt;
use app\api\service\Pay as PayService;
use think\Loader;

Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');

class Pay extends BaseController
{
    protected $wxConfig;

    public function __construct()
    {
        $this->wxConfig = new \WxPayConfig();
    }

    protected $beforeActionList = [
      'checkUserScope' => ['only' => 'preorder']
    ];

    public function preOrder()
    {
        $order_id = input('post.id');
        (new IdMustBePositiveInt())->goCheck();
        $pay_service = new PayService($order_id);
        return $pay_service->pay();
    }

    public function receiveNotify()
    {
        $notify = new Notify();
        $notify->Handle($this->wxConfig);
    }
}