<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/20
 * Time: 17:46
 */

namespace app\lib\exception;


class SuccessMessage extends BaseException
{
    public $code = 201;
    public $msg = 'ok';
    public $errorCode = 0;

}