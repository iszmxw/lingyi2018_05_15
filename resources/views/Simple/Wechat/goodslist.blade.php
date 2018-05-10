<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ Session::get('organization_name') }}</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="{{asset('public/Wechat')}}/css/public/light7.min.css">
    <link rel="stylesheet" href="{{asset('public/Wechat')}}/css/public/dropload.css">
    <link rel="stylesheet" href="{{asset('public/Wechat')}}/css/goodslist.css?v=<?php echo time(); ?>">

</head>
<body>
<input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
<input type="hidden" name="fansmanage_id" id="fansmanage_id" value="{{$fansmanage_id}}">
<input type="hidden" name="store_id" id="store_id" value="{{$store_id}}">
<input type="hidden" name="shop_user_id" id="shop_user_id" value="{{session("zerone_auth_info.shop_user_id")}}">
<input type="hidden" name="zerone_user_id" id="zerone_user_id" value="{{session("zerone_auth_info.zerone_user_id")}}">
{{--<!-- 分页 -->--}}
<input type="hidden" name="limit" id="limit" value="0">


<div class="page1">
    <div class="g-flexview">
        <div class="head">
            <div class="nav">
                <ul class="clear_after" id="goods_cs_lt">
                </ul>
            </div>
            <div class="nav_right" onclick="goodsclass('goodsclass')">
                <i class="nav_icon buy_drop_down action"></i>
                <i class="nav_icon nav_icon_into"></i>
            </div>
        </div>
        <div class="search">
            <div class="search_item">
                <label class="icon_search" onclick="search_click()"></label>
                <input type="search" id="search" name="" placeholder="七夕礼物 玫瑰">
                <label class="icon_scavenging"></label>
            </div>
        </div>


        <div class="goodslist">
            <div class="lists" id="goodslist">
            </div>
        </div>
    </div>

    {{--<div class="cart_bottom">--}}
        {{--<div class="cart_fl">--}}
            {{--<div class="cart_left">--}}
                {{--<div class="cart_kuang clear_after" id="cart_btn_b" onclick="showcart('cart',this)">--}}
                    {{--<div class="cart_kuang_img">--}}
                        {{--<span class="goods_number" id="goods_totalnum" data-totalnum="0">0</span>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<h3 class="cart_price" id="cart_price" data-totalprice="0">您还未选购商品哦~</h3>--}}
            {{--</div>--}}
            {{--<div class="cart_right"><a href="javascript:;" onclick="show('alert')">去结算 <i></i></a></div>--}}
        {{--</div>--}}
    {{--</div>--}}
    <!-- alert -->
    <div class="popup_alert" id="alert">
        <div class="quhuo alert_width popup_alert_hook">
            <p class="quhuoinfo">确认清空购物车吗?</p>
            <div class="max_height_box">
                <!-- <p>我的取货信息我的取货信息我的取货信息</p> -->
            </div>
            <div class="alert_btn_wz">
                <a href="javascript:;" class="btn_alert my_text_align btn_alert_bg1" onclick="hide('alert')">取消</a>
                <a href="javascript:;" class="btn_alert my_text_align btn_alert_bg" onclick="cart_empty()">确认</a>
            </div>
        </div>
    </div>
    <!-- alert -->
    <!-- alert -->
    <div class="popup_alert quhuoinfo cart" id="cart">
        <div class="cart_box_h popup_alert_hook">
            <div class="sanjiaoxing_box">
                <div class="sanjiaoxing"></div>
                <div class="open_cart_kuang">
                    <div class="cart_kuang clear_after" onclick="show('cart')">
                        <div class="cart_kuang_img">
                            <span class="goods_number" id="total" data-totalnum="0">0</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="cart_top">
                <span>已选商品</span>
                <span onclick="show('alert')">清空</span>
            </div>
            <ul class="cart_list" id="cart_list">

            </ul>
        </div>
    </div>
    <!-- alert -->
    <!-- alert -->
    <div class="popup_alert quhuoinfo goodsclass" id="goodsclass">
        <div class="goodsclass_box_h popup_alert_hook">
            <ul class="clear_after" id="goods_cs_lt_alert">
            </ul>
        </div>
        <div class="goodsclass_close" onclick="hidegoodsclass('goodsclass')"></div>
    </div>
    <!-- alert -->
</div>

<script src="https://www.w3cways.com/demo/vconsole/vconsole.min.js?v=2.2.0"></script>

<script type='text/javascript' src="{{asset('public/Wechat')}}/js/public/jquery.min.js" charset='utf-8'></script>
<script type='text/javascript' src="{{asset('public/Wechat')}}/js/public/light7.min.js" charset='utf-8'></script>
<script type='text/javascript' src="{{asset('public/Wechat')}}/js/public/dropload.min.js" charset='utf-8'></script>
<script type='text/javascript' src="{{asset('public/Wechat')}}/js/goodslist.js?v=<?php echo time(); ?>"
        charset='utf-8'></script>
</body>
</html>
