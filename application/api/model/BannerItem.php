<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/10/31
 * Time: 14:59
 */

namespace app\api\model;


class BannerItem extends BaseModel
{
    //隐藏字段
    protected $hidden = ['id', 'img_id', 'banner_id', 'update_time', 'delete_time'];

    public function image()
    {
        return $this->belongsTo('Image', 'img_id', 'id');
    }
}