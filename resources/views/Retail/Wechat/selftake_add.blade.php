<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>添加取货信息</title>
	<meta name="viewport" content="initial-scale=1, maximum-scale=1">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<link rel="stylesheet" href="{{asset('public/Wechat')}}/css/public/light7.min.css">
	<link rel="stylesheet" href="{{asset('public/Wechat')}}/css/addpugoods.css">
</head>
<body>
<div class="page">
	<form role="form" id="selftake_add"  action="{{ url('api/wechatApi/selftake_add') }}">
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<input type="hidden" name="zerone_user_id" id="zerone_user_id" value="{{session("zerone_auth_info.zerone_user_id")}}">
	<div class="g-flexview">
		<div class="main_block">
			<div class="main_item">
				<ul>
					<li class="a_b_b a_b_t">
						<div class="item_title title_f">姓名:</div>
						<div class="item_input">
							<input type="text" name="realname" placeholder="请填写取货人的姓名">
						</div>
						<div class="weixin_ress"></div>
					</li>
					<li class="a_b_b a_b_t">
						<div class="item_title title_f"></div>
						<div class="item_input item_input_pd1">
							<input type="radio" id="userinfo1" name="sex" checked="checked" class="radio_address" value="1">
							<label for="userinfo1">先生</label>
							<input type="radio" id="userinfo2" name="sex" class="radio_address" value="2">
							<label for="userinfo2">女士</label>
						</div>
					</li>
					<li class="a_b_b a_b_t">
						<div class="item_title title_f item_input_pd">电话:</div>
						<div class="item_input item_input_pd">
							<input type="number" name="mobile" min="0" max="9" placeholder="请填写取货人的手机号码">
						</div>
					</li>
				</ul>
			</div>
		</div>
		<div class="morendizhi" onclick="select_morendizhi()">
				<span>
					<input type="checkbox" name="status" id="morendizhi" value="1">
					<label for="morendizhi" class="" id="morendizhi_action">
						<label for="morendizhi">设为默认取货信息</label>
					</label>
				</span>
		</div>
		<a href="javascript:;" class="preservation" onclick="selftake_add_cm()">保存</a>
	</div>
	</form>
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
<script type='text/javascript' src='{{asset('public/Wechat')}}/js/public/jquery.min.js' charset='utf-8'></script>
<script type='text/javascript' src='{{asset('public/Wechat')}}/js/public/light7.min.js' charset='utf-8'></script>
<script type='text/javascript' src='{{asset('public/Wechat')}}/js/addpugoods.js' charset='utf-8'></script>
</body>
</html>
