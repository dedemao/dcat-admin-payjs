<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>扫码付款</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #f9f9f9;
            font-family: Arial, STHeiti, Helvetica, sans-serif;
            font-size: 12px;
            color: #666;
            padding-top: 4px;
        }

        h2 {
            color: #000;
            font-weight: normal;
            font-size: 15px;
            line-height: 2.6em;
            padding: 0 4.6%;
        }

        div.icon {
            width: 50%;
            margin: 1.5em auto;
        }

        div.icon img {
            width: 100%;
        }

        .message {
            text-align: center;
            font-weight: bold;
        }

        .am-button {
            -ms-box-sizing: border-box;
            box-sizing: border-box;
            display: inline-block;
            margin: 0;
            padding: 4px 8px;
            width: 100%;
            text-align: center;
            font-size: 18px;
            line-height: 2;
            border-radius: 4px;
            background-clip: padding-box;
        }

        .result {
            font-weight: 500;
        }

        .result-botton {
            margin: 0 15px 20px;
        }

        .result-botton a {
            display: block;
            margin: auto;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            max-width: 384px;
            height: 44px;
            text-align: center;
            text-decoration: none;
        }

        .result-botton a.am-button-white {
            color: #333;
            background-color: #fff;
            border: 1px solid #ccc;
        }

        .result-botton a.am-button-green {
            color: #fff;
            background: #00c800;
            border: 1px solid #00aaee;
        }

        .result-botton a.am-button-blue {
            color: #fff;
            background: #108ee9;
            border: 1px solid #00aaee;
        }

        .result-botton .am-button[disabled=disabled] {
            color: #e6e6e6;
            background: #f8f8f8;
            border: 1px solid #dedede;
        }

        @media (max-width: 767px) {
            #pay-tip {
                width: 100%
            }
        }

        #qrDiv {
            width: 300px;
            display: block;
            margin: auto;
            border: 1px #ccc solid;
            height: 300px;
            background: url(data:image/gif;base64,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) #fff 50% no-repeat;
        }

        #qrDiv img {
            text-align: center;
            padding: 10px;
        }
    </style>
    <script src="https://cdn.bootcss.com/jquery/2.1.0/jquery.min.js" type="text/javascript"></script>
</head>
<body>
<div class="am-content">
    <div id="pay-content" style="display:none">
        <div style="padding: 20px 0 10px;text-align:center;">
            <div style="font-size: 22px;display: block;margin: auto;" id="order_subject">{{$subject}}</div>
        </div>
        <div style="padding: 5px 0 5px;text-align:center">
            <div style="font-size: 14px;display: block;margin: auto;"><span> 付款金额</span> <span
                    style="margin: 2px 2px;color:#f60"> ￥ {{$total_fee}}</span></div>
        </div>
        <div>
            <div style="position: relative;width: 100%;display: block">
                <div id="qrDiv"></div>
            </div>
        </div>
        <div style="padding: 5px 0 5px;text-align:center">
            <div id="tips"
                 style="font-size: 12px;display: block;margin: auto;width: 90%;">使用 微信 扫码完成付款
            </div>
        </div>
        @if($pay_channel == 'all')
            <div class="result" style="padding: 10px 0 10px;">
                <div class="result-botton"><a class="J-change am-button am-button-white" href="#">选择其他支付方式</a></div>
            </div>
        @endif
    </div>
    <div id="change-content">
        <div style="padding: 20px 0 10px;text-align:center">
            <div style="font-size: 22px;display: block;margin: auto;">请选择支付方式</div>
        </div>
        <div class="pay-types" style="padding: 10px 0 10px;">
            <div class="result-botton"><a class="J-change-paytype am-button am-button-blue" data-type="alipay" href="#">支付宝</a>
            </div>
            <div class="result-botton"><a class="J-change-paytype am-button am-button-green" data-type="weixin"
                                          href="#">微信支付</a></div>
        </div>
        <input type="hidden" id="out_trade_no" value="{{$out_trade_no}}">
    </div>
</div>
<script>
    $.ajaxSetup({cache: false});
    var t = 0;
    $(".J-change").click(function () {
        clearInterval(t);
        $("#pay-content").hide();
        $("#change-content").show();
    });
    @if($pay_channel == 'alipay')
    doPay('alipay');
    @elseif($pay_channel == 'weixin')
    doPay('weixin');
    @endif

    $(".J-change-paytype").click(function () {
        var me = $(this);
        var type = me.data('type');
        doPay(type);
    });

    function doPay(paymode) {
        $("#pay-content").show();
        $("#change-content").hide();
        $("#qrDiv").html('');
        var out_trade_no = $("#out_trade_no").val();
        var order_subject = $("#order_subject").text();
        $("#tips").text('付款二维码获取中...');
        $.getJSON('{{ url('pay/getQrcode') }}', {
            paymode: paymode,
            out_trade_no: out_trade_no,
            total_fee:{{$total_fee}},
        }, function (result) {
            var reg = "/" + out_trade_no + "/g";
            $("#order_subject").text(order_subject.replace(eval(reg), result.out_trade_no));
            $("#out_trade_no").val(result.out_trade_no);
            var paytype = paymode == 'alipay' ? '支付宝' : '微信';
            if (result.return_code == 1) {
                var tip = '使用 ' + paytype + ' 扫码完成付款';
                $("#tips").text(tip);
                checkOrder(result.qrcode);
            } else {
                $("#tips").html('<b style="color:red">' + result.return_msg + '</b>');
            }
        });
    }

    function checkOrder(qrurl) {
        var out_trade_no = $("#out_trade_no").val();
        t = setInterval(function () {
            var url = "{{ url('pay/checkOrder') }}?out_trade_no=" + out_trade_no;
            $.getJSON(url, function (result) {
                //alert(result.code);
                if (result.code == 0) {
                    window.location.href = '{{ url('pay/response') }}?out_trade_no=' + out_trade_no + '&total_fee=<?php echo $total_fee?>';
                }
                if (result.code == 2) {
                    $("#wait-pay").show();
                    $("#tips").text('等待付款');
                }
            });
        }, 2000);

        var html = '<img src="' + qrurl + '" style="display: block;margin: auto;"/>';
        $('#qrDiv').html(html);

    }
</script>
</body>
</html>
