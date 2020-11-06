<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>支付成功</title>
    <link rel="stylesheet" href="//res.wx.qq.com/open/libs/weui/1.1.3/weui.min.css"/>
    <style>
        @media (min-width: 768px) {.weui-msg__opr-area{width: 300px;margin:0 auto}.page__bd{width: 400px;margin:0 auto}}
    </style>
</head>
</head>
<body>
<div class="container" id="container">
    <div class="page msg_warn js_show">
        <div class="weui-msg">
            <div class="weui-msg__icon-area"><i class="weui-icon-success weui-icon_msg"></i></div>
            <div class="weui-msg__text-area">
                <h2 class="weui-msg__title">{{$status==1 ? '支付成功' : '支付失败'}}</h2>
            </div>
        </div>
        <div class="page__bd">
            <div class="weui-cells">
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <p>订单号</p>
                    </div>
                    <div class="weui-cell__ft">{{$out_trade_no}}</div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <p>订单金额</p>
                    </div>
                    <div class="weui-cell__ft">{{$total_fee/100}}元</div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <p>支付时间</p>
                    </div>
                    <div class="weui-cell__ft">{{$paid_time}}</div>
                </div>
            </div>
        </div>


        <div class="weui-msg__extra-area">
            <div class="weui-footer">
                <p class="weui-footer__links">
                    <a href="javascript:void(0);" class="weui-footer__link">织梦猫</a>
                </p>
                <p class="weui-footer__text">Copyright @ 2013-2020 织梦猫 版权所有</p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
