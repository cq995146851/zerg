<?php

namespace app\api\model;

use think\Model;

class ProductProperty extends Model
{
    //隐藏字段
    protected $hidden = ['product_id', 'update_time', 'delete_time'];
}
