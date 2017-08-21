<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/18
 * Time: 13:37
 */

namespace app\api\controller\v1;


use app\api\validate\Count;
use app\api\model\Product as ProductModel;
use app\api\validate\IDMustBePostiveInt;
use app\lib\exception\ProductException;

class Product
{
    /**
     * 获取最新商品列表
     * @url /product/recent?count=:id
     * @param int $count
     * @return false|\PDOStatement|string|\think\Collection
     * @throws ProductException
     */
    public function getRecent($count = 15)
    {
        (new Count())->goCheck();

        $products = ProductModel::getMostRecent($count);

        if (empty($products)){
            throw new ProductException();
        }
        $collection = collection($products);

        $products = $collection->hidden(['summary']);

        return $products;
    }

    public function getAllInCategory($id)
    {
        (new IDMustBePostiveInt())->goCheck();

        $products = ProductModel::getProductsByCategoryID($id);

        if (empty($products)){
            throw new ProductException();
        }

        $collection = collection($products);

        $products = $collection->hidden(['summary']);

        return $products;

    }

    public function getOne($id)
    {
        (new IDMustBePostiveInt())->goCheck();

        $product = ProductModel::getProductDetail($id);

        if (!$product){
            throw new ProductException();
        }

        return $product;

    }

    public function deleteOne($id)
    {

    }
}