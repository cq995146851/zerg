<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/11/10
 * Time: 17:29
 */

namespace app\api\service;


use app\api\model\OrderProduct;
use app\api\model\Product;
use app\api\model\UserAddress;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;
use app\api\model\Order as OrderModel;
use think\Db;
use think\Exception;

class Order
{
    protected $oProducts;
    protected $products;
    protected $userId;

    public function __construct($user_id, $oProducts)
    {
        $this->userId = $user_id;
        $this->oProducts = $oProducts;
        $this->products = $this->getProductsByOrder($oProducts);
    }

    public function place()
    {
        $order_status = $this->getOrderStatus();
        if (!$order_status['pass']) {
            $order_status['order_id'] = -1;
            return $order_status;
        }
        //生成快照
        $order_snap = $this->snapOrder($order_status);
        //生成订单
        return $this->createOrder($order_snap);
    }

    private function createOrder($order_snap)
    {
        Db::startTrans();
        try {
            $order_no = self::makeOrderNo();
            $order = new OrderModel();
            $order->order_no = $order_no;
            $order->user_id = $this->userId;
            $order->total_price = $order_snap['totle_price'];
            $order->total_count = $order_snap['totle_count'];
            $order->snap_img = $order_snap['snap_img'];
            $order->snap_name = $order_snap['snap_name'];
            $order->snap_address = $order_snap['snap_address'];
            $order->snap_items = $order_snap['snap_items'];
            $order->save();
            foreach ($this->oProducts as &$oProduct) {
                $oProduct['order_id'] = $order->id;
            }
            $order_product = new OrderProduct();
            $order_product->saveAll($this->oProducts);
            Db::commit();
            return [
                'pass' => true,
                'order_id' => $order->id,
                'order_no' => $order->order_no,
                'create_time' => $order->create_time
            ];
        } catch (Exception $e) {
            Db::rollback();
            throw $e;
        }
    }

    private function snapOrder($order_status)
    {
        $snap['totle_price'] = $order_status['totle_price'];
        $snap['totle_count'] = $order_status['totle_count'];
        $snap['totle_count'] = $order_status['totle_count'];
        $snap['snap_items'] = json_encode($order_status['product_status_arr']);
        $snap['snap_name'] = (count($this->oProducts) > 1) ? $this->products[0]['name'] . ' 等' : $this->products[0]['name'];
        $snap['snap_img'] = $this->products[0]['main_img_url'];
        $snap['snap_address'] = json_encode($this->getUserAddress());
        return $snap;
    }

    private function getUserAddress()
    {
        $address = UserAddress::where('user_id', $this->userId)->find();
        if (!$address) {
            throw new UserException([
                'msg' => '用户地址不存在',
                'errorCode' => 60001
            ]);
        }
        return $address->toArray();
    }

    public function checkOrderStock($order_id)
    {
        $this->oProducts = OrderProduct::where('order_id', $order_id)->select()->toArray();
        $this->products = $this->getProductsByOrder($this->oProducts);
        return $this->getOrderStatus();
    }

    private function getOrderStatus()
    {
        $status = [
            'pass' => true,
            'totle_price' => 0,
            'totle_count' => 0,
            'product_status_arr' => []
        ];
        foreach ($this->oProducts as $oProduct) {
            $product_status = $this->getProductStatus($oProduct, $this->products);
            $status['totle_price'] += $product_status['totle_price'];
            $status['totle_count'] += $product_status['count'];
            if (!$product_status['pass']) {
                $status['pass'] = false;
            }
            array_push($status['product_status_arr'], $product_status);
        }
        return $status;
    }

    private function getProductStatus($oProduct, $products)
    {
        $product_status = [];
        foreach ($products as $product) {
            if ($product['id'] == $oProduct['product_id']) {
                $product_status['id'] = $product['id'];
                $product_status['name'] = $product['name'];
                $product_status['main_img_url'] = $product['main_img_url'];
                $product_status['count'] = $oProduct['count'];
                $product_status['price'] = $product['price'];
                $product_status['totle_price'] = $product['price'] * $oProduct['count'];
                $product_status['id'] = $product['id'];
                $product_status['pass'] = ($product['stock'] >= $oProduct['count']) ? true : false;
                break;
            } else {
                continue;
            }
        }
        if (empty($product_status)) {
            throw new OrderException([
                'msg' => 'id为' . $oProduct['product_id'] . '的商品不存在，订单创建失败'
            ]);
        }
        return $product_status;
    }

    private function getProductsByOrder($oProducts)
    {
        $proIds = [];
        foreach ($oProducts as $oProduct) {
            array_push($proIds, $oProduct['product_id']);
        }
        return Product::all($proIds)->toArray();
    }

    public static function makeOrderNo()
    {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        return $yCode[intval(date('Y')) - 2017] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
    }
}