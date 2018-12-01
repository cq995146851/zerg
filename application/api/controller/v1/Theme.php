<?php

namespace app\api\controller\v1;

use app\api\model\Theme as ThemeModel;
use app\api\validate\IdCollection;
use app\api\validate\IdMustBePositiveInt;

class Theme
{
    /**
     * @url /api/v1/theme?ids=1,2,3.....
     * @http GET
     * @return 多个主题
     */
    public function getSimpleThemes($ids = '')
    {
        (new IdCollection())->goCheck();
        return ThemeModel::getThemesByIds($ids);
    }

    /**
     * @url /api/v1/theme/:id
     * @http GET
     * @id 正整数
     *@return 单个主题详情
     */
    public function getThemeDetail($id)
    {
        (new IdMustBePositiveInt())->goCheck();
        return ThemeModel::getThemeDetailById($id);
    }
}
