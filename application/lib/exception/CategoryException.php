<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/19
 * Time: 4:42
 */

namespace app\lib\exception;


class CategoryException extends BaseException
{
    public $code = 404;
    public $msg = '指定类目不存在，请检查参数';
    public $errorCode = 50000;

}