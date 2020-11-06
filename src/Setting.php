<?php

namespace Dedemao\Payjs;

use Dcat\Admin\Extend\Setting as Form;

class Setting extends Form
{
    public function form()
    {
        $this->text('mchid','商户号')->required()->help('在payjs会员中心查看');
        $this->text('appkey','通信密钥')->required()->help('在payjs会员中心查看');
        $this->select('pay_channel','支付通道')->required()->default('all')->options(['all'=>'支付宝和微信','alipay'=>'支付宝','weixin'=>'微信']);
        $this->text('notify_url','回调地址')->required()->default(url("pay/notify"))->help('接收支付异步通知的回调地址（确保可以外网访问）');
    }
}
