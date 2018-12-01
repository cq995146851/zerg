<?php
/**
 * Created by PhpStorm.
 * User: 陈骞
 * Date: 2018/11/8
 * Time: 0:47
 */

namespace app\api\controller\v1;

use app\api\model\Category as CategoryModel;

class Category
{
    /**
     * @url /api/v1/category
     * @http GET
     * @return 所有的分类
     */
    public function getCategories()
    {
        return CategoryModel::getCategories();
    }
}