<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/17
 * Time: 22:41
 */

namespace app\api\model;


class BannerItem extends BaseModel
{
    public function img()
    {
        return $this->belongsTo('Image','img_id','id');
    }
}