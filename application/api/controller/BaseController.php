<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/21
 * Time: 11:20
 */

namespace app\api\controller;


use app\api\service\Token as TokenService;
use think\Controller;

class BaseController extends Controller
{
    protected function checkExclusiveScope()
    {
        TokenService::needExclusiveScope();
    }

    protected function checkPrimaryScope()
    {
        TokenService::needPrimaryScope();
    }

}