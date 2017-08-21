<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/20
 * Time: 15:02
 */

namespace app\api\validate;


class AddressNew extends BaseValidate
{
    protected $rule = [
        'name' => 'require|isNotEmpty',
        'mobile' => 'require|isMobile',
        'province' => 'require|isNotEmpty',
        'city' => 'require|isNotEmpty',
        'country' => 'require|isNotEmpty',
        'detail' => 'require|isNotEmpty',
        //'uid' => ''
    ];

}