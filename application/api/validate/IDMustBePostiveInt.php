<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/12
 * Time: 22:28
 */

namespace app\api\validate;


class IDMustBePostiveInt extends BaseValidate
{
    protected $rule = [
        'id'=>'require|isPositiveInteger',
        'num'=>'in:1,2,3'
    ];

    protected $message = [
      'id'=>'id必须是正整数',
    ];
}