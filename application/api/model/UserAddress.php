<?php

namespace app\api\model;

use think\Model;

class UserAddress extends Model
{
    //隐藏字段
    protected $hidden = ['update_time', 'delete_time', 'user_id'];
}
