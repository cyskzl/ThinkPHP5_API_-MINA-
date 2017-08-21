<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/21
 * Time: 0:58
 */

namespace app\lib\exception;


class ForbiddenException extends BaseException
{
    public $code = 403;
    public $msg = '权限不足';
    public $errorCode = 10001;

}