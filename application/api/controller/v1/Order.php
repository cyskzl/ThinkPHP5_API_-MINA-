<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/21
 * Time: 2:10
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\Order as OrderService;
use app\api\service\Token as TokenService;
use app\api\validate\IDMustBePostiveInt;
use app\api\validate\OrderPlace;
use app\api\validate\PagingParameter;
use app\api\model\Order as OrderModel;
use app\lib\exception\OrderException;

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
        'checkPrimaryScope' => ['only' => 'getDetail,getSummaryByUser']
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

    public function getDetail($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $orderDetail = OrderModel::get($id);
        if (!$orderDetail){
            throw new OrderException();
        }
        return $orderDetail->hidden(['prepay_id']);
    }

    //用户个人中心订单记录
    public function getSummaryByUser($page =1,$size = 15)
    {
        (new PagingParameter())->goCheck();
        $uid = TokenService::getCurrentUid();
        $pagingOrders = OrderModel::getSummaryByUser($uid,$page,$size);

        if ($pagingOrders->isEmpty()){
            return [
                'data' => [],
                'current_page' => $pagingOrders->getCurrentPage(),
            ];
        }

        $data = $pagingOrders;

        foreach ($data as &$v){
            $v = $v->hidden(['snap_items','snap_address','prepay_id']);
        }
        $data = $data->toArray();

        return [
            'data' => $data,
            'current_page' => $pagingOrders->getCurrentPage(),
        ];
    }

}