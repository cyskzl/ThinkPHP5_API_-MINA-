<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/20
 * Time: 12:04
 */

namespace app\api\model;


class ProductImage extends BaseModel
{
    protected $hidden = ['img_id','delete_time','product_id'];

    public function imgUrl()
    {
        return $this->belongsTo('Image','img_id','id');
    }

}