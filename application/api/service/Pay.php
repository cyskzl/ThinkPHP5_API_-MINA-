<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/22
 * Time: 3:39
 */

namespace app\api\service;


use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Exception;
use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use think\Loader;
use think\Log;

//Think Loader文件导入方法
Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');

class Pay
{
    private $orderID;
    private $orderNO;

    public function __construct($orderID)
    {
        if (!$orderID) {
            throw new Exception('订单号不允许为NULL');
        }
        $this->orderID = $orderID;
    }

    public function pay()
    {
        //订单号检测是否存在
        //订单号存在，是否与当前用户匹配
        //订单可能不是未支付状态
        //进行库存量检测
        $this->checkOrderValid();
        $orderService = new OrderService();
        $status = $orderService->checkOrderStock($this->orderID);
        if (!$status['pass']) {
            return $status;
        }
        return $this->makeWeChatPerOrder($status['orderPrice']);
    }

    /**
     * 微信预订单处理
     */
    public function makeWeChatPerOrder($statusPrice)
    {
        $openid = Token::getCurrentTokenVar('openid');
        if (!$openid){
            throw new TokenException();
        }
        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNO);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($statusPrice * 100);
        $wxOrderData->SetBody('零食商户');
        $wxOrderData->SetOpenid($openid);
        $wxOrderData->SetNotify_url('http://qq.com');
        return $this->getPaySignature($wxOrderData);

    }

    private function getPaySignature($wxOrderData)
    {
        $wxOrder = \WxPayApi::unifiedOrder($wxOrderData);
        if ($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] != 'SUCCESS'){
            Log::record($wxOrder,'error');
            Log::record('获取预支付订单失败','error');
        }
        $this->recordPreOrder($wxOrder);
        $signature = $this->sign($wxOrder);
        return $signature;
    }

    private function sign($wxOrder)
    {
        $jsApiPayData = new \WxPayJsApiPay();
        $jsApiPayData->SetAppid(config('wechat.app_id'));
        $jsApiPayData->SetTimeStamp((string)time());

        $rand = md5(time().mt_rand(0,1000));
        $jsApiPayData->SetNonceStr($rand);

        $jsApiPayData->SetPackage('prepay_id='.$wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('md5');

        $sign = $jsApiPayData->MakeSign();
        $rawValues = $jsApiPayData->GetValues();

        $rawValues['paySing'] = $sign;

        unset($rawValues['appId']);
        return $rawValues;
    }

    private function recordPreOrder($wxOrder)
    {
        var_dump($wxOrder);die();
        OrderModel::where('id','=',$this->orderID)->update(['prepay_id'=>$wxOrder['prepay_id']]);
    }

    /**
     * 订单验证
     * @return bool
     * @throws OrderException
     * @throws TokenException
     */
    public function checkOrderValid()
    {
        $order = OrderModel::where('id','=',$this->orderID)->find();
        if (!$order){
            throw new OrderException();
        }

        if (!Token::isValidOperate($order->user_id)){
            throw new TokenException([
                'msg' => '订单信息与用户不匹配',
                'errorCode' => 10003
            ]);
        }

        if ($order->status != OrderStatusEnum::UNPAID){
            throw new OrderException([
                'msg' => '订单已支付',
                'code' => 400,
                'errorCode'=> 80003,
            ]);
        }
        $this->orderNO = $order->order_no;
        return true;
    }

}