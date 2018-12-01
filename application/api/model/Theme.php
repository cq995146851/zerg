<?php

namespace app\api\model;

use app\lib\exception\MissException;

class Theme extends BaseModel
{
    //隐藏的字段
    protected $hidden = ['update_time', 'delete_time', 'head_img_id', 'topic_img_id'];

    //获取topicImg
    public function topicImg()
    {
        return $this->belongsTo('Image', 'topic_img_id', 'id');
    }

    //获取headImg
    public function headImg()
    {
        return $this->belongsTo('Image', 'head_img_id', 'id');
    }

    //获取所有产品
    public function products()
    {
        return $this->belongsToMany('Product', 'theme_product', 'product_id', 'theme_id');
    }

    //根据ids获取对应主题
    public static function getThemesByIds($ids)
    {
        //select返回的是集合对象
        $themes = self::with(['topicImg', 'headImg'])->select($ids);
        if ($themes->isEmpty()) {
            throw new MissException();
        }
        return $themes;
    }

    //根据id获取相应带产品详情的主题
    public static function getThemeDetailById($id)
    {
        //find返回的是空
        $theme = self::with(['products', 'topicImg', 'headImg'])->find($id);
        if (!$theme) {
            throw new MissException();
        }
        return $theme;
    }
}
