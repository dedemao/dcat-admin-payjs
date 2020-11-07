<?php

namespace Dedemao\Payjs\Http\Controllers\Front;

use Dcat\Admin\Admin;
use Dcat\Admin\Http\Middleware\Pjax;
use Dcat\Admin\Layout\Content;
use Dedemao\Payjs\Facades\OrderService;
use Dedemao\Payjs\Facades\PayjsService;
use Dedemao\Payjs\Support\Helper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class PayController extends Controller
{
    protected $setting = [];

    public function __construct()
    {
        $this->setting = Admin::setting()->getArray('dedemao:dcat-admin-payjs');
        if(empty($this->setting) || empty($this->setting['mchid']) || empty($this->setting['appkey'])){
            Pjax::respond(
                response((new Content())->withError('请先填写payjs配置信息'))
            );
        }
    }

    public function index(Request $request)
    {
        $data['out_trade_no'] = $request['out_trade_no'] ?: Helper::generateOutTradeNo();
        $data['total_fee'] = $request['total_fee'] ? sprintf("%.2f", floatval($request['total_fee'])) : 0.01;
        $data['subject'] = $request['subject'] ?: '订单号：' . $data['out_trade_no'];
        $data['pay_channel'] = $request['pay_channel'] ?: $this->setting['pay_channel'];
        $data['pay_mode'] = $request['pay_mode'] ?: 'native';
        if ($data['pay_mode'] == 'jsapi') return $this->jsapi($data);
        if ($data['pay_mode'] == 'cashier') return $this->cashier($data);
        return view('dedemao.dcat-admin-payjs::pay/index',$data);
    }

    public function jsapi($data)
    {
        $payjs = PayjsService::payment($this->setting);
        //获取openid
        $data['openid'] = $payjs->getOpenid();
        //获取jsapi参数
        $jsapiConfig = $payjs->jsapi($data);
        //添加数据库订单
        $data['outer_tid'] = $jsapiConfig['outer_tid'];
        OrderService::unify($data);
        return view('dedemao.dcat-admin-payjs::pay/jsapi', $jsapiConfig);
    }

    public function cashier($data)
    {
        $payjs = PayjsService::payment($this->setting);
        //收银台支付
        $url = $payjs->cashier($data);
        if (in_array($data['pay_channel'],['all','alipay']) && !Helper::isAlipay() && !Helper::isWeixin()) {
            $url = "alipays://platformapi/startapp?appId=20000067&url=" . urlencode($url);
        }
        header("Location:{$url}");
        exit();
    }

    public function getQrcode(Request $request)
    {
        $data['out_trade_no'] = $request['out_trade_no'] ?: Helper::generateOutTradeNo();
        $data['total_fee'] = $request['total_fee'] ? sprintf("%.2f", floatval($request['total_fee'])) : 0.01;
        $data['subject'] = $request['subject'] ?: '订单号：' . $data['out_trade_no'];
        $data['pay_channel'] = $this->setting['pay_channel'] ?: 'all';
        $data['type'] = $request['paymode'] ?: 'weixin';
        //添加数据库订单
        OrderService::unify($data);
        //获取支付二维码
        $result = PayjsService::payment($this->setting)->getQrcode($data);
        $arr = json_decode($result, true);
        if ($arr['return_code'] == 1) {
            //设置payjs平台订单号
            OrderService::setPayjsOrderId($arr['out_trade_no'], $arr['payjs_order_id']);
        }
        echo $result;
        exit();
    }

    /**
     * 查询订单支付状态
     * @param Request $request
     */
    public function checkOrder(Request $request)
    {
        $data = OrderService::orderQuery($request['out_trade_no']);
        return response()->json($data);
    }

    public function test()
    {
        return redirect("pay/index?total_fee=0.01");
    }

    /**
     * 支付结果显示页面
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function response(Request $request)
    {
        $order = OrderService::getPayjsOrderId($request['out_trade_no']);
        if (!$order->outer_tid) exit('支付失败：未找到该笔订单');
        $result = PayjsService::payment($this->setting)->orderquery($order->outer_tid);
        $data = json_decode($result, true);
        if (isset($data['message'])) {
            exit('支付失败：' . $data['message']);
        }
        if ($data['return_code'] == 0) {
            exit('支付失败：' . $data['msg']);
        }
        return view('dedemao.dcat-admin-payjs::pay/response',$data);
    }

    /**
     * 异步通知
     * @param Request $request
     */
    public function notify(Request $request)
    {
        Log::debug($request->post());
        $result = PayjsService::payment($this->setting)->check($request->post());
        if ($result === false) {
            exit('sign error');
        }
        $order = OrderService::getOrderByTid($request->post('payjs_order_id'));
        if ($order->status == 1) {
            $data = [
                'status' => 0,
                'transaction_tid' => $request->post('transaction_id'),
                'pay_at' => $request->post('time_end'),
                'buyer_info' => $request->post('openid'),
            ];
            OrderService::updateOrderByTid($request->post('payjs_order_id'), $data);
        }
        echo 'success';
        exit();
    }
}
