<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/22
 * Time: 2:47
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\WxNotify;
use app\api\validate\IDMustBePostiveInt;
use app\api\service\Pay as PayService;

class Pay extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'getPreOrder']
    ];

    public function getPreOrder($id = '')
    {
        (new IDMustBePostiveInt())->goCheck();
        $pay = new PayService($id);
        return $pay->pay();

    }

    public function receiveNotify()
    {
        //微信支付后异步 通知频率为 15/15/30/180/1800/1800/1800/3600。单位：秒

        //1.检测库存量
        //2.更新订单状态
        //3.减库存
        //如果成功处理，返回微信成功处理的消息。否则，需要返回没有成功处理。
        //微信返回信息特点：请求：POST;格式：XML;不会携带参数
        $notify = new WxNotify();
        $notify->Handle();

    }

}