<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/11/11
 * Time: 14:36
 */

namespace app\lib\enum;


class PayStatusEnum
{
    //未支付
    CONST NOPAY = 1;
    //已支付
    CONST PAID = 2;
    //已支付但未发货
    CONST PAID_NOT_SEND = 3;
    //已支付但库存不足
    CONST PAID_NOT_STOCK = 4;
}