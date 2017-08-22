<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/23
 * Time: 0:45
 */

namespace app\api\service;
use app\lib\enum\OrderStatusEnum;
use think\Exception;
use think\Loader;
use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use app\api\model\Product as ProductModel;
use think\Log;

Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');

class WxNotify extends \WxPayNotify
{
    public function NotifyProcess($data, &$msg)
    {
        if ($data['result_code'] == 'SUCCESS'){
            $orderNo = $data['out_trade_no'];
            try{
                $order = OrderModel::where('order_no','=',$orderNo)->find();
                if ($order->status ==1){
                    $service = new OrderService;
                    $stockStatus = $service->checkOrderStock($order->id);
                    if ($stockStatus['pass']){
                        $this->updateOrderStatus($order->id,true);
                        $this->reduceStock($stockStatus);
                    }else{
                        $this->updateOrderStatus($order->id,false);
                    }
                }
                return true;
            }catch (Exception $ex){
                Log::error($ex);
                return false;
            }
        }else{
            return true;
        }
    }
    
    private function reduceStock($stockStatus)
    {
        foreach ($stockStatus['pStatusArray'] as $singlePStatus){
            //$singlePStatus['count'];
            ProductModel::where('id','=',$singlePStatus['id'])->setDec('stock',$singlePStatus['count']);
        }
    }

    private function updateOrderStatus($orderID,$success)
    {
        $status = $success ? OrderStatusEnum::PAID : OrderStatusEnum::PAID_BUT_OUT_OF;
        OrderModel::where('order_id','=',$orderID)->update(['status'=>$status]);
    }

}