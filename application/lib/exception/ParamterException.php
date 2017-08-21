<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/14
 * Time: 1:53
 */

namespace app\lib\exception;


class ParamterException extends BannerMissException
{
    public $code = 400;
    public $msg = '参数错误';
    public $errorCode = 10000;

}