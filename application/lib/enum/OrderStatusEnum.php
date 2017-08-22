<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/22
 * Time: 13:31
 */

namespace app\lib\enum;


class OrderStatusEnum
{
    //未支付
    const UNPAID = 1;

    //已支付
    const PAID = 2;

    //已发货
    const DELIVERED = 3;

    //
    const PAID_BUT_OUT_OF = 4;

}