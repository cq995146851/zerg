<?php

namespace app\api\model;

class Image extends BaseModel
{
    //隐藏字段
    protected $hidden = ['id', 'from', 'update_time', 'delete_time'];

    public function getUrlAttr($value, $data)
    {
        return $this->addImgPrefix($value, $data);
    }
}
