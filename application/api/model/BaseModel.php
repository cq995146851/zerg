<?php

namespace app\api\model;

use think\Model;

class BaseModel extends Model
{
    //图片路径加前缀
    public function addImgPrefix($value, $data)
    {
        return ($data['from'] == 1) ? (config('setting.img_prefix') . $value) : $value;
    }
}