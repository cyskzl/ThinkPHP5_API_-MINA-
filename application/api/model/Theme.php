<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/18
 * Time: 6:30
 */

namespace app\api\model;



class Theme extends BaseModel
{
    public function topicImg()
    {
        return $this->belongsTo('Image','topic_img_id','id');
    }
    public function headImg()
    {
        return $this->belongsTo('Image','head_img_id','id');
    }

    public function products()
    {
        return $this->belongsToMany('Product','theme_product','product_id','theme_id');
    }

    public static function getThemeWithProduct($id)
    {
        $theme = self::with('products,topicImg,headImg')->find($id);
        return $theme;
    }
}