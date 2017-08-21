<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/13
 * Time: 2:35
 */

namespace app\api\model;


use app\lib\exception\BannerMissException;

class Banner extends BaseModel
{

    public function items()
    {
        return $this->hasMany('BannerItem','banner_id','id');
    }

    public static function getBannerByID($id)
    {
        //$result = Db::query('select * from banner_item WHERE banner_id=?',[$id]);

        $result = self::with(['items','items.img'])->find($id);

        return json($result);
    }

}