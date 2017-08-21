<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/21
 * Time: 2:10
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\Token as TokenService;
use app\api\validate\OrderPlace;
use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use app\api\service\Order as OrderService;

class Order extends BaseController
{
    //用户在选择商品,在API提交包含它所选择商品的相关信息
    //API在接受到信息后,需要检查订单相关商品的库存量
    //有库存,把订单的数据存入数据库中.等于下单成功,返回客户端消息,告诉客户端可以支付
    //调用支付接口,进行支付
    //还需要再次进行库存量检测
    //服务器这边就可以调用微信的支付接口进行支付
    //微信会返回一个支付的结果(异步)
    //成功:也需要进行库存量的检测
    //支付成功,进行库存量的扣除,失败返回一个支付失败的结果

    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'placeOrder'],
    ];

    public function placeOrder()
    {
        (new OrderPlace())->goCheck();
        $products = input('post.products/a');

        $uid = TokenService::getCurrentUid();

        $order = new OrderService();
        $status = $order->place($uid,$products);

        return $status;
    }

}