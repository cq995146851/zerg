<?php

namespace app\api\model;

use think\Model;

class Order extends Model
{
    protected $hidden = ['update_time', 'delete_time', 'user_id'];
    protected $autoWriteTimestamp = true;

    public static function getSummaryByUser($user_id, $page, $size)
    {
        return self::where('user_id', $user_id)
            ->order('create_time', 'desc')
            ->paginate($size, true, ['page' => $page]);
    }
}
