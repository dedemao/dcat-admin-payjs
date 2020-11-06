<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="email=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link href="https://res.wx.qq.com/open/libs/weui/1.1.3/weui.min.css" rel="stylesheet">
    <style>
        body{background-color:#f8f8f8;padding:0;margin:0;}
        .header {border-bottom: 1px solid #ccc;background-color: #fff;}
        .footer{margin:0;padding:0;}
        .footer p{text-align: center; color:#999; font-weight: 200;margin-top:20px;font-size:12px;}
        a{font-size:14px !important; font-weight: 300;padding:5px 20px;}
        .logo{text-align: center; padding-top:60px;}
        .logo img{width:70px;opacity: 0.8; border-radius: 50%;}
        .amount{font-size:48px; text-align: center; color:#333;}
        .mchname{color:#999;margin:0 20px;border-top:1px solid #eee;padding:12px;font-size:13px;font-weight: 200;}
        .popfeng{position:absolute; bottom:40px;left:0;right:0;background-color:#f8f8f8;line-height: 2px;}
        .popfeng p{color:#bfbfbf;font-size: 12px; text-align: center;width:100%;}
    </style>
    <title>微信公众号支付</title>
</head>
<body class="hide">
<div class="header">
    <div class="logo">
        <img src="http://v53.884358.com/plus/img/pay-logo.png">
    </div>
    <div class="amount">¥ {{$total_fee}}</div>
    <div class="mchname">订单号: {{$out_trade_no}}</div>
</div>

<div class="footer" style="padding:40px 20px;">
    <a href="javascript:;" class="weui-btn weui-btn_primary" id="payBtn">微信支付</a>
    <a href="javascript:;" class="weui-btn weui-btn_default" id="close" style="background-color: #fff;border-color: #e5e5e5;color:#333;">取消支付操作</a>
    <p>支付完成后, 如需退款请及时联系卖家</p>
    <div class="popfeng">
        <p>由 织梦猫 提供支付技术服务</p>
    </div>
</div>

<div id="toast" style="opacity: 0; display: none;">
    <div class="weui-mask_transparent"></div>
    <div class="weui-toast">
        <i class="weui-icon-success-no-circle weui-icon_toast"></i>
        <p class="weui-toast__content">已完成</p>
    </div>
</div>
<script src="https://cdn.bootcss.com/zepto/1.2.0/zepto.min.js"></script>
<script>
    document.addEventListener('touchmove', function(e){e.preventDefault()}, false);

    if (typeof WeixinJSBridge == "undefined"){
        if( document.addEventListener ){
            document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
        }else if (document.attachEvent){
            document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
            document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
        }
    }else{
        onBridgeReady();
    }

    function onBridgeReady(){
        WeixinJSBridge.call('hideOptionMenu');
    }

    $('#payBtn').on('click', pay);

    $('#close').on('click',function(){
        WeixinJSBridge.call('closeWindow');
    });

    function pay() {
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest', {
                "appId": "{{$jsapi['appId']}}",
                "timeStamp": "{{$jsapi['timeStamp']}}",
                "nonceStr": "{{$jsapi['nonceStr']}}",
                "package": "{{$jsapi['package']}}",
                "signType": "{{$jsapi['signType']}}",
                "paySign": "{{$jsapi['paySign']}}"
            },
            function(res){
                //alert(JSON.stringify(res));
                switch(res.err_msg) {
                    case 'get_brand_wcpay_request:cancel':
                        //alert('取消支付');
                        WeixinJSBridge.call('closeWindow');
                        break;
                    case 'get_brand_wcpay_request:fail':
                        alert('支付失败');
                        break;
                    case 'get_brand_wcpay_request:ok':
                        //alert('支付成功');
                        window.location.href = '{{ url('pay/response') }}?out_trade_no={{$out_trade_no}}';
                        break;
                    default:
                        break;
                }
            }
        );
    }

</script>

</body>
</html>
