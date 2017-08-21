<?php

namespace app\api\model;

use think\Model;

class BaseModel extends Model
{
    protected $hidden = ['delete_time','update_time'];


    /**
     * 图片URL 读取器
     * @param $value
     * @param $data
     * @return string
     */
    protected function prefixImgUrl($value,$data)
    {
        $finalUrl = $value;
        if ($data['from'] ==1){
            $finalUrl = config('setting.img_prefix').$value;
        }
        return $finalUrl;
    }
}
