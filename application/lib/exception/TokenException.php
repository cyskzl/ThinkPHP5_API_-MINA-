<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/20
 * Time: 2:33
 */

namespace app\lib\exception;


class TokenException extends BaseException
{
    public $code = 401;
    public $msg = 'Token已过期或无效';
    public $errorCode = 10001;

}