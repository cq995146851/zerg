<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/10/31
 * Time: 14:59
 */

namespace app\api\model;


use app\lib\exception\MissException;

class Banner extends BaseModel
{
    //隐藏字段
    protected $hidden = ['update_time', 'delete_time'];

    //获取所有的BannerItem
    public function items()
    {
        return $this->hasMany('BannerItem', 'banner_id', 'id');
    }

    //根据BannerId获取banner信息
    public static function getBannerById($id)
    {
        $banner =  self::with(['items', 'items.image'])->find($id);
        if (!$banner) {
            throw new MissException();
        }
        return $banner;
    }
}