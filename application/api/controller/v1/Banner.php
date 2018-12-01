<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/10/31
 * Time: 12:58
 */

namespace app\api\controller\v1;


use app\api\model\Banner as BannerModel;
use app\api\validate\IdMustBePositiveInt;

class Banner
{
    /**
     * 根据id获取banner信息
     * @url /api/v1/banner/:id
     * @http GET
     * @id banner的id
     * @return banner信息
     */
    public function getBanner($id = '')
    {
        (new IdMustBePositiveInt())->goCheck();
        return BannerModel::getBannerById($id);
    }
}