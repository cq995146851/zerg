<?php

namespace app\api\model;

use think\Model;

class ProductImage extends Model
{
    //隐藏字段
    protected $hidden = ['product_id', 'delete_time'];

    //获取图片地址
    public function imgUrl()
    {
        return $this->belongsTo('Image', 'img_id', 'id');
    }
}
