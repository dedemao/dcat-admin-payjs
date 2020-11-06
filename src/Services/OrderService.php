<?php

namespace Dedemao\Payjs\Services;

use Dcat\Admin\Admin;
use Dedemao\Payjs\Facades\ConfigService;
use Dedemao\Payjs\Models\PayjsOrders;
use Dedemao\Payjs\Facades\PayjsService;

class OrderService
{
    /**
     * 生成订单记录
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    protected function create(array $data)
    {
        return PayjsOrders::query()->create($data);
    }

    /**
     * 根据payjs订单号查询订单记录
     * @param $outTradeNo
     * @param int $status
     */
    public function getOrderByTid(string $tid)
    {
        return PayjsOrders::query()->where(['outer_tid' => $tid])->firstOrFail();
    }

    /**
     * 根据payjs订单号修改订单记录
     * @param $outTradeNo
     * @param int $status
     */
    public function updateOrderByTid(string $tid, $data)
    {
        return PayjsOrders::query()->where(['outer_tid' => $tid])->update($data);
    }

    /**
     * 修改订单状态
     * @param $outTradeNo
     * @param int $status
     */
    public function changeOrderStatus(string $outTradeNo, int $status = 1)
    {
        return PayjsOrders::query()->where(['out_trade_no' => $outTradeNo])->update([
            'status' => intval($status)
        ]);
    }

    /**
     * 修改订单支付方式
     * @param $outTradeNo
     * @param int $status
     */
    public function changePayType(string $outTradeNo, string $type = 'weixin')
    {
        return PayjsOrders::query()->where(['out_trade_no' => $outTradeNo])->update([
            'type' => $type
        ]);
    }

    /**
     * 设置payjs平台订单号
     * @param $outTradeNo
     * @param int $status
     */
    public function setPayjsOrderId(string $outTradeNo, string $orderId)
    {
        return PayjsOrders::query()->where(['out_trade_no' => $outTradeNo])->update([
            'outer_tid' => $orderId
        ]);
    }

    /**
     * 获取payjs平台订单号
     * @param string $outTradeNo
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function getPayjsOrderId(string $outTradeNo)
    {
        return PayjsOrders::query()->where(['out_trade_no' => $outTradeNo])->firstOrFail('outer_tid');
    }

    /**
     * 统一下单
     */
    public function unify(array $data)
    {
        //添加订单记录
        $order['out_trade_no'] = $data['out_trade_no'] ?? $this->outTradeNo();
        $order['subject'] = $data['subject'] ?? '订单号：' . $data['out_trade_no'];
        $order['total_fee'] = $data['total_fee'] ? floatval($data['total_fee']) : 0.01;
        $order['type'] = $data['type'] ?? 'weixin';
        return $this->create($order);
    }

    /**
     * 退款
     * @param string $outTradeNo 商户订单号
     * @return mixed
     */
    public function refund(string $outTradeNo)
    {
        $data['status'] = 'error';
        $order = PayjsOrders::query()->where(['out_trade_no' => $outTradeNo])->firstOrFail();
        if ($order->status != 0) {
            $data['msg'] = '该订单状态不能退款';
            return $data;
        }
        $setting = Admin::setting()->getArray('dedemao:dcat-admin-payjs');
        $result = PayjsService::payment($setting)->refund($order->outer_tid);
        $result = json_decode($result, true);
        if ($result['return_code'] == 1) {
            //退款成功，修改订单状态
            $this->changeOrderStatus($outTradeNo, 2);
            $data['status'] = 'success';
            $data['msg'] = '退款成功';
            return $data;
        } else {
            $data['msg'] = $result['return_msg'];
            return $data;
        }
    }

    /**
     * 订单状态查询
     * @param string $outTradeNo 商户订单号
     */
    public function orderQuery(string $outTradeNo)
    {
        $data['status'] = 'error';
        $data['code'] = 2;      //未支付状态
        $order = $this->getPayjsOrderId($outTradeNo);
        if (!$order->outer_tid) {
            $data['msg'] = '未找到该笔订单';
            return $data;
        }
        $setting = Admin::setting()->getArray('dedemao:dcat-admin-payjs');
        $result = PayjsService::payment($setting)->orderquery($order->outer_tid);
        $result = json_decode($result, true);
        if ($result['return_code'] == 1) {
            //查询成功
            $data['status'] = 'success';
            if ($result['status'] == 1 && $order->status == 1) {
                $this->changeOrderStatus($outTradeNo, 0);
            }
            if ($result['status'] == 1) $data['code'] = 0;     //修改为已支付状态
            return $data;
        } else {
            $data['msg'] = $result['return_msg'];
            return $data;
        }
    }
}
