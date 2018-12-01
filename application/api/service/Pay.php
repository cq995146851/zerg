<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/11/11
 * Time: 14:23
 */

namespace app\api\service;


use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use app\lib\enum\PayStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\ParameterException;
use app\lib\exception\TokenException;
use think\Loader;
use think\Log;

Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');

class Pay
{
    protected $orderId;
    protected $orderNo;
    protected $wxConfig;

    public function __construct($order_id)
    {
        if (!$order_id) {
            throw new ParameterException([
                'code' => 404,
                'msg' => '订单参数未传递'
            ]);
        }
        $order_no = $this->checkOrderIdValid($order_id);
        $this->orderNo = $order_no;
        $this->orderId = $order_id;
        $this->wxConfig = new \WxPayConfig();
    }

    public function pay()
    {
        //检验库存
        $status = (new OrderService('', []))->checkOrderStock($this->orderId);
        if (!$status['pass']) {
            return $status;
        }
        //生成预订单
        return $this->makeWxPreOrder($status['totle_price']);
    }

    private function makeWxPreOrder($total_price)
    {
        $openid = Token::getCurrTokenValByKey('openid');
        if (!$openid) {
            throw new TokenException();
        }
        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNo);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($total_price * 100);
        $wxOrderData->SetBody('晓琳商店');
        $wxOrderData->SetOpenid($openid);
        $wxOrderData->SetNotify_url(config('secure.wx_pay_back_url'));
        return $this->getPaySignature($wxOrderData);
    }

    private function getPaySignature($wxOrderData)
    {
        $wxOrder = \WxPayApi::unifiedOrder($this->wxConfig, $wxOrderData);
        //模拟成功返回数据
        $wxOrder = [
            'appid' => 'aaaaa',
            'mch_id' => 'bbbbbbbbb',
            'nonce_str' => 'w6z07v2wueiorj',
            'prepay_id' => 'wx2018111221324a2343783467384',
            'result_code' => 'SUCCESS',
            'return_code' => 'SUCCESS',
            'return_msg' => 'OK',
            'sign' => '0A2BCBDFJKFKJSDKJKSDFJ7897FSDF',
            'trade_type' => 'JSAPI'
        ];
        // 失败时不会返回result_code
        if ($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] != 'SUCCESS') {
            Log::record($wxOrder, 'error');
            Log::record('获取预支付订单失败', 'error');
        }
        //将prepay_id存入到数据库，方便主动回调给客户端信息
        $this->savePrepayId($wxOrder['prepay_id']);
        //生成客户端微信支付所需参数
        return $this->makePaySign($wxOrder);
    }

    private function makePaySign($wxOder)
    {
        $jsApiPayData = new \WxPayJsApiPay();
        $jsApiPayData->SetAppid(config('wx.app_id'));
        $jsApiPayData->SetTimeStamp((string)time());
        $jsApiPayData->SetNonceStr(md5(time() . rand(0, 1000)));
        $jsApiPayData->SetPackage('prepay_id=' . $wxOder['prepay_id']);
        $jsApiPayData->SetSignType('md5');
        $raw_value = $jsApiPayData->GetValues();
        $raw_value['paySign'] = $jsApiPayData->MakeSign($this->wxConfig);
        unset($raw_value['appId']);
        return $raw_value;
    }

    private function savePrepayId($prepay_id)
    {
        $order_model = OrderModel::find($this->orderId);
        $order_model->prepay_id = $prepay_id;
        $order_model->save();
    }

    private function checkOrderIdValid($order_id)
    {
        $order = OrderModel::where('id', $order_id)->find();
        if (!$order) {
            throw new OrderException([]);
        }
        if (!Token::checkOperateValid($order->user_id)) {
            throw new TokenException([
                'msg' => '订单与用户不匹配',
                'errorCode' => 10003
            ]);
        }
        if ($order->status != PayStatusEnum::NOPAY) {
            throw new OrderException([
                'msg' => '订单已支付过啦',
                'errorCode' => 80003,
                'code' => 400
            ]);
        }
        return $order->order_no;
    }
}