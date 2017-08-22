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
    //管理员权限验证
    protected function checkExclusiveScope()
    {
        TokenService::needExclusiveScope();
    }

    //用户权限验证
    protected function checkPrimaryScope()
    {
        TokenService::needPrimaryScope();
    }

}