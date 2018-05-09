<html>
<head>
    <meta name="viewport" id="viewport" content="width=device-width, initial-scale=1">
    {{--<meta name="apple-mobile-web-app-capable" content="yes" />--}}
    {{--<meta name="apple-mobile-web-app-status-bar-style" content="black" />--}}
    {{--<meta charset="utf-8">--}}
    {{--<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">--}}
    {{--<meta content="telephone=no" name="format-detection">--}}

</head>

<body>

<h1>订单支付</h1>

<button onclick="callpay()">
    <h4>
        确定支付
    </h4>
</button>


<script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script src="https://www.w3cways.com/demo/vconsole/vconsole.min.js?v=2.2.0"></script>
<script>
    wx.config({
        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端"console.log出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: '{{$signPackage["appId"]}}', // 必填，公众号的唯一标识
        timestamp: '{{$signPackage["timestamp"]}}', // 必填，生成签名的时间戳
        nonceStr: '{{$signPackage["nonceStr"]}}', // 必填，生成签名的随机串
        signature: '{{$signPackage["signature"]}}',// 必填，签名
        jsApiList: [
            'checkJsApi',
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'openAddress',
            'chooseWXPay',
        ] // 必填，需要使用的JS接口列表
    });

    // wx.ready(function () {
        // wx.checkJsApi({
        //     jsApiList: ['checkJsApi',
        //         'onMenuShareTimeline',
        //         'onMenuShareAppMessage',
        //         'openAddress',
        //         'chooseWXPay'
        //     ], // 需要检测的JS接口列表，所有JS接口列表见附录2,
        //     success: function (res) {
        //         // 以键值对的形式返回，可用的api值true，不可用为false
        //         // 如：{"checkResult":{"chooseImage":true},"errMsg":"checkJsApi:ok"}
        //     }
        // });
        // wx.openAddress({
        //     success: function (res) {
        //         console.log(JSON.stringify(res));
        //     }
        // });
    // })

    function callpay() {

        wx.ready(function () {
            // config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，config是一个客户端的异步操作，所以如果需要在页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。对于用户触发时才调用的接口，则可以直接调用，不需要放在ready函数中。
            wx.chooseWXPay({
                appId: '{{$wxpay["appid"]}}',
                timestamp: '{{$wxpay["appid"]}}', // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
                nonceStr: '{{$wxpay["nonce_str"]}}', // 支付签名随机串，不长于 32 位
                package: 'prepay_id={{$wxpay["prepay_id"]}}', // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=***）
                signType: 'MD5', // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
                paySign: '{{$wxpay["sign"]}}', // 支付签名
                success: function (res) {
                    // 支付成功后的回调函数
                    if (res.errMsg == "chooseWXPay:ok") {
                        //支付成功
                        console.log('支付成功');
                    } else {
                        console.log(res.errMsg);
                    }
                },
                cancel: function (res) {
                    //支付取消
                    console.log('支付取消');
                }
            });
        });
    }
</script>
</body>
</html>