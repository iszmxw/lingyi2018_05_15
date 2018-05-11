<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>我的收货地址</title>
	<meta name="viewport" content="initial-scale=1, maximum-scale=1">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<link rel="stylesheet" href="{{asset('public/Wechat')}}/css/public/light7.min.css">
	<link rel="stylesheet" href="{{asset('public/Wechat')}}/css/myRetaildeliveryRess.css">
</head>
<body>
<div class="page">
	<div class="g-flexview">
		<div class="box">
			<div class="max_height_box">
				<div class="row alert_list">
					<div class="col-85 radio_css">
						<input type="radio" id="userinfo" name="dizhi" checked="checked" class="radio_address"><label for="userinfo">
							<label for="userinfo">广东省深圳市龙岗区万汇大厦1606</label>
							薛志豪（先生） 13824322924</label>
					</div>
					<div class="col-15 right_height"><a href="javascript:;" class="update_address"></a></div>
				</div>
				<div class="row alert_list">
					<div class="col-85 radio_css">
						<input type="radio" id="userinfo1" name="dizhi" class="radio_address"><label for="userinfo1">
							<label for="userinfo1">广东省深圳市龙岗区万汇大厦1606</label>
							薛志豪（先生） 13824322924</label>
					</div>
					<div class="col-15 right_height"><a href="javascript:;" class="update_address"></a></div>
				</div>
				<div class="row alert_list">
					<div class="col-85 radio_css">
						<input type="radio" id="userinfo2" name="dizhi" class="radio_address"><label for="userinfo2">
							<label for="userinfo2">广东省深圳市龙岗区万汇大厦1606</label>
							薛志豪（先生） 13824322924</label>
					</div>
					<div class="col-15 right_height"><a href="javascript:;" class="update_address"></a></div>
				</div>
				<div class="row alert_list">
					<div class="col-85 radio_css">
						<input type="radio" id="userinfo3" name="dizhi" class="radio_address"><label for="userinfo3">
							<label for="userinfo3">广东省深圳市龙岗区万汇大厦1606</label>
							薛志豪（先生） 13824322924</label>
					</div>
					<div class="col-15 right_height"><a href="javascript:;" class="update_address"></a></div>
				</div>
			</div>
		</div>
		<a href="javascript:;" class="preservation">新增收货地址</a>
	</div>
	<!-- alert -->
	<div class="popup_alert alert" id="alert">
		<div class="quhuo alert_width popup_alert_hook">
			<p class="quhuoinfo">订单备注</p>
			<div class="max_height_box">
				<p>我的取货信息我的取货信息我的取货信息</p>
			</div>
			<div class="alert_btn_wz">
				<!-- <a href="javascript:;" class="btn_alert my_text_align btn_alert_bg1">取消</a> -->
				<a href="javascript:;" class="btn_alert my_text_align btn_alert_bg" onclick="hide('alert')">确认</a>
			</div>
		</div>
	</div>
	<!-- alert -->
</div>
<script type='text/javascript' src='{{asset('public/Wechat')}}/js/public/jquery.min.js' charset='utf-8'></script>
<script type='text/javascript' src='{{asset('public/Wechat')}}/js/public/light7.min.js' charset='utf-8'></script>
<script type='text/javascript' src='{{asset('public/Wechat')}}/js/myRetaildeliveryRess.js' charset='utf-8'></script>
</body>
</html>