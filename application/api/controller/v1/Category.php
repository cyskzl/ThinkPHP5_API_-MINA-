<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/19
 * Time: 4:34
 */

namespace app\api\controller\v1;

use app\api\model\Category as CategoryModel;
use app\lib\exception\ProductException;

class Category
{
    /**
     * 获取类目
     * @return $this|false|static[]
     * @throws ProductException
     */
    public function getAllCategories()
    {
        $categories = CategoryModel::all([],'img');

        if (empty($categories)){
            throw new ProductException();
        }

        $colletion = collection($categories);

        $categories = $colletion->hidden(['description']);

        return $categories;

    }

}