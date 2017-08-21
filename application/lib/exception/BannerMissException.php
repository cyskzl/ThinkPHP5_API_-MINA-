<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/13
 * Time: 12:48
 */

namespace app\lib\exception;


class BannerMissException extends  BaseException
{
    public $code = 404;
    public $msg = '请求的Banner不存在';
    public $errorCode = 40000;

}