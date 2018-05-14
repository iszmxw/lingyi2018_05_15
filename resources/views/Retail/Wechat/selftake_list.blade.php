<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>我的取货地址</title>
	<meta name="viewport" content="initial-scale=1, maximum-scale=1">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<link rel="stylesheet" href="{{asset('public/Wechat')}}/css/public/light7.min.css">
	<link rel="stylesheet" href="{{asset('public/Wechat')}}/css/mypuinfo.css">
</head>
<body>
<div class="page">
	<div class="g-flexview">

		<div class="max_height_box">
			<div class="box">
				<div class="alert_list">
					<div class="radio_css">
						<input type="radio" id="userinfo" name="dizhi" checked="checked" class="radio_address"><label for="userinfo">
							薛志&nbsp;&nbsp;13824322924</label>
					</div>
					<div class="puinfo_btn">
						<a href="javascript:;" class="puinfo_btn_xiugai">修改</a>
						<a href="javascript:;" class="puinfo_btn_shanchu">删除</a>
					</div>
				</div>
			</div>
			<div class="box">
				<div class="alert_list">
					<div class="radio_css">
						<input type="radio" id="userinfo1" name="dizhi" class="radio_address"><label for="userinfo1">
							薛志&nbsp;&nbsp;13824322924</label>
					</div>
					<div class="puinfo_btn">
						<a href="javascript:;" class="puinfo_btn_xiugai">修改</a>
						<a href="javascript:;" class="puinfo_btn_shanchu">删除</a>
					</div>
				</div>
			</div>
		</div>
		<a href="javascript:;" class="preservation">新增取货地址</a>
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
<script type='text/javascript' src='{{asset('public/Wechat')}}/js/mypuinfo.js' charset='utf-8'></script>
</body>
</html>