<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/11/7
 * Time: 23:25
 */

namespace app\api\controller\v1;


use app\api\validate\Count;
use app\api\model\Product as ProductModel;
use app\api\validate\IdMustBePositiveInt;
use app\lib\exception\MissException;

class Product
{
    /**
     * @url /api/v1/produve/recent?count=2
     * @http GET
     * @count 数量
     * @return 最新的商品
     */
    public function getRecent($count = 15)
    {
        (new Count())->goCheck();
        return  ProductModel::getRecent($count);
    }

    public function getAllByCategory($id = '')
    {
        (new IdMustBePositiveInt())->goCheck();
        return ProductModel::getAllByCategory($id);
    }

    public function getDetail($id = '')
    {
        (new IdMustBePositiveInt())->goCheck();
        return ProductModel::getDetail($id);
    }
}