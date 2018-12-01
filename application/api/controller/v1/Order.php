<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/11/10
 * Time: 16:34
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\Token;
use app\api\validate\IdMustBePositiveInt;
use app\api\validate\OrderPlace;
use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use app\api\validate\PageParameter;

class Order extends BaseController
{
    protected $beforeActionList = [
      'checkUserScope' => ['only' => 'placeorder']
    ];

    /**
     * 获取我的全部简要订单
     */
    public function getSummaryByUser($page = 1, $size = 10)
    {
        (new PageParameter())->goCheck();
        $user_id = Token::getCurrUserId();
        $orders = OrderModel::getSummaryByUser($user_id, $page, $size);
        return $orders->isEmpty() ? [] : $orders->hidden(['prepay_id', 'snap_items', 'snap_address']);
    }

    /**
     * 获取某个订单详情
     */
    public function getDetail($id)
    {
        (new IdMustBePositiveInt())->goCheck();
        return OrderModel::find($id)->hidden(['prepay_id']);
    }

    /**
     * 生成订单
     */
    public function placeOrder()
    {
        (new OrderPlace)->goCheck();
        $oProducts = input('post.products/a');
        $user_id = Token::getCurrUserId();
        $order_service = new OrderService($user_id, $oProducts);
        return $order_service->place();
    }
}