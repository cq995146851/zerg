<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/10/31
 * Time: 13:12
 */

namespace app\api\validate;


class IdCollection extends BaseValidate
{
    protected $rule = [
        'ids' => 'require|checkIds'
    ];

    protected $message = [
        'ids.require' => 'ids必须传递',
        'ids.checkIds' => 'ids必须是以逗号分隔的正整数'
    ];

    public function checkIds($value)
    {
        $ids = explode(',', $value);
        foreach ($ids as $id) {
            if(!$this->isPositiveInt($id)) {
                return false;
            }
        }
        return true;
    }
}