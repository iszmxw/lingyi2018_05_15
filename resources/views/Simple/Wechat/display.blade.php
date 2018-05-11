<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>选择店铺</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="{{asset('public/Wechat')}}/css/public/light7.min.css">
    <link rel="stylesheet" href="{{asset('public/Wechat')}}/css/selectshop.css">
  </head>
  <body>
  	<input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
  	<input type="hidden" name="organization_id" id="organization_id" value="{{$organization_id}}">
    <div class="page">
	    <div class="g-flexview">
			<div class="head">
				<div class="search_box">
					<div class="search_item">
						<label class="icon_search" onclick="searchshop()"></label>
						<input type="search" id="keyword" name="keyword" placeholder="输入商家信息">
					</div>
					<div class="search_right">
						<div class="search_r_img"></div>
					</div>
				</div>
				<div class="head_bottom">
					<div class="bottom_left">
						<span id="month">

						</span>
						<p class="bottomaddress" onclick="searchshop()"><i></i>获取位置信息...</p>
					</div>
					<div class="bottom_right">
						<div class="order_img"></div>
						<p>订单</p>
					</div>
				</div>
			</div>
			<div class="nav_box">
				<div class="row">
			        <div class="col-50 action"><p class="fujinddian">附近的店<span id="shopnumber"></span></p></div>
			        <div class="col-50"></div>
			    </div>
			</div>
			<div class="shoplist_box">
				<ul id="shoplist">
				</ul>
			</div>
	    </div>
		<!-- alert -->
		<div class="popup_alert">
			<div class="quhuo alert_width popup_alert_hook">
				<p class="quhuoinfo">订单备注</p>
				<div class="max_height_box">
					<p>我的取货信息我的取货信息我的取货信息</p>
				</div>
				<div class="alert_btn_wz">
					<!-- <a href="javascript:;" class="btn_alert my_text_align btn_alert_bg1">取消</a> -->
					<a href="javascript:;" class="btn_alert my_text_align btn_alert_bg" onclick="hide()">确认</a>
				</div>
			</div>
		</div>
		<!-- alert -->
    </div>
      <script type="text/javascript">
        define = null;
        require = null;
    </script>
	<script type='text/javascript' src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js" charset='utf-8'></script>
    <script type='text/javascript' src="{{asset('public/Wechat')}}/js/public/jquery.min.js" charset='utf-8'></script>
    <script type='text/javascript' src="{{asset('public/Wechat')}}/js/public/light7.min.js" charset='utf-8'></script>
    <script type='text/javascript' src="{{asset('public/Wechat')}}/js/selectshop.js" charset='utf-8'></script>
    <script type="text/javascript">
     wx.config({
	      debug: false,
          appId:'{{$appId}}',
          timestamp: '{{$timestamp}}',
          nonceStr: '{{$nonceStr}}',
          signature: '{{$signature}}',
          jsApiList: [
                'checkJsApi',
		        'openLocation',
		        'getLocation'
          ]
      });
     wx.ready(function(){
         //获取用户地理位置和获取店铺
         getltshop();
     });
     wx.error(function(res){
         // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
         alert(JSON.stringify(res));

     });
      </script>
  </body>
</html>
