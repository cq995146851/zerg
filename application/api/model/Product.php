<?php

namespace app\api\model;

use app\lib\exception\MissException;

class Product extends BaseModel
{
    //隐藏字段
    protected $hidden = ['delete_time', 'main_img_id', 'pivot', 'from', 'category_id',
        'create_time', 'update_time'];

    //获取商品所有的图片
    public function imgs()
    {
        return $this->hasMany('ProductImage', 'product_id', 'id');
    }

    //获取商品所有的属性
    public function properties()
    {
        return $this->hasMany('ProductProperty', 'product_id', 'id');
    }

    //处理image路径前缀
    public function getMainImgUrlAttr($value, $data)
    {
        return $this->addImgPrefix($value, $data);
    }

    //获取最近商品
    public static function getRecent($count)
    {
        $products = self::limit($count)
            ->order('create_time', 'desc')
            ->select()
            ->hidden(['summary']);
        if ($products->isEmpty()) {
            throw new MissException();
        }
        return $products;
    }

    //根据分类id获取商品
    public static function getAllByCategory($id)
    {
        $products = self::where('category_id', $id)
            ->select();
        if ($products->isEmpty()) {
            throw new MissException();
        }
        return $products;
    }

    //根据id获取商品详情
    public static function getDetail($id)
    {
        $product = self::with([
            'imgs' => function ($query) {
                $query->with(['imgUrl'])->order('order', 'asc');
            }
        ])->with(['properties'])
            ->where('id', $id)
            ->find();
        if (!$product) {
            throw new MissException();
        }
        return $product;
    }
}
