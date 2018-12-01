<?php

namespace app\api\model;

use app\lib\exception\MissException;
use think\Model;

class Category extends BaseModel
{

    //隐藏的字段
    protected $hidden = ['update_time', 'delete_time'];

    //获取topicImg
    public function topicImg()
    {
        return $this->belongsTo('Image', 'topic_img_id', 'id');
    }

    //获取所有分类
    public static function getCategories()
    {
        $categories = self::with('topicImg')->select();
        if ($categories->isEmpty()) {
            throw new MissException();
        }
        return $categories;
    }
}
