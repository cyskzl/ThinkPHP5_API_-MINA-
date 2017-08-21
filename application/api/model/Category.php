<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/19
 * Time: 4:34
 */

namespace app\api\model;


class Category extends BaseModel
{
    public function img()
    {
        return $this->belongsTo('Image','topic_img_id','id');
    }

}