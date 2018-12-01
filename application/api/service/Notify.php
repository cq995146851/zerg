<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/11/12
 * Time: 16:13
 */

namespace app\api\service;

use app\api\model\Product;
use app\lib\enum\PayStatusEnum;
use think\Db;
use think\Exception;
use think\Loader;
use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use think\Log;

Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');

class Notify extends \WxPayNotify
{
    public function NotifyProcess($objData, $config, &$msg)
    {
        if ($objData['return_code'] == 'SUCCESS') {
            Db::startTrans();
            try {
                $order = OrderModel::where('order_no', $objData['out_trade_no'])
                    ->lock(true)
                    ->find();
                if ($order->status == 1) {
                    $status = (new OrderService('', []))->checkOrderStock($order->id);
                    if ($status['pass']) {
                        $this->updateStatus($order->id, true);
                        $this->reduceStock($status);
                    } else {
                        $this->updateStatus($order->id, false);
                    }
                }
                Db::commit();
            } catch (Exception $e) {
                Db::rollback();
                Log::error($e);
                return false;
            }
        }
        return true;
    }

    private function updateStatus($order_id, $success = true)
    {
        $status = $success ? PayStatusEnum::PAID : PayStatusEnum::PAID_NOT_STOCK;
        OrderModel::where('id', $order_id)->update([
           'status' => $status
        ]);
    }

    private function reduceStock($status)
    {
        foreach ($status['product_status_arr'] as $product) {
            Product::where('id', $product['id'])->setDec('stock', $product['count']);
        }
    }
}