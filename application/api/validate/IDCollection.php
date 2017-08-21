<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/18
 * Time: 6:50
 */

namespace app\api\validate;


class IDCollection extends BaseValidate
{
    protected $rule = [
        'ids' => 'require|checkIDs'
    ];

    protected $message = [
        'ids'=> 'ids参数必须是以逗号分隔的正整数'
    ];

    protected function checkIDs($vaule)
    {
        $vaules = explode(',',$vaule);

        if (empty($vaules)){
            return false;
        }

        foreach ($vaules as $id){
            if (!$this->isPositiveInteger($id)){
                return false;
            }
        }

        return true;
    }

}