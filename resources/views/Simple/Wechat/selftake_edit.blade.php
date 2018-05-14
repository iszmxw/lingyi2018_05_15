<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>编辑取货信息</title>
	<meta name="viewport" content="initial-scale=1, maximum-scale=1">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<link rel="stylesheet" href="{{asset('public/Wechat')}}/css/public/light7.min.css">
	<link rel="stylesheet" href="{{asset('public/Wechat')}}/css/editpugoods.css">
</head>
<body>
<div class="page">
	<input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
    <input type="hidden" name="zerone_user_id" id="zerone_user_id" value="{{session("zerone_auth_info.zerone_user_id")}}">
	<div class="g-flexview">
		<form role="form" id="selftake_edit"  action="{{ url('api/wechatApi/selftake_edit') }}">
				<input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
			<div class="main_block">
			    <input type="hidden" name="self_take_id" id="selftake_id" value="">
				<div class="main_item">
					<ul>
						<li class="a_b_b a_b_t">
							<div class="item_title title_f">姓名:</div>
							<div class="item_input">
								<input type="text" name="realname" id="realname" placeholder="请填写取货人的姓名" value="">
							</div>
							<!-- <div class="weixin_ress"></div> -->
						</li>
						<li class="a_b_b a_b_t">
							<div class="item_title title_f"></div>
							<div class="item_input item_input_pd1">
								<input type="radio" id="sex1" name="sex" checked="checked" class="radio_address" value="1">
								<label for="sex1">先生</label>
								<input type="radio" id="sex2" name="sex" class="radio_address" value="2">
								<label for="sex2">女士</label>
							</div>
						</li>
						<li class="a_b_b a_b_t">
							<div class="item_title title_f item_input_pd">电话:</div>
							<div class="item_input item_input_pd">
								<input type="text" name="mobile" id="mobile" placeholder="请填写取货人的手机号码" value="">
							</div>
						</li>
					</ul>
				</div>
			</div>
			<div class="morendizhi">
				<span>
					<input type="checkbox" name="status" id="morendizhi" value="1">
					<label for="morendizhi" class="" id="morendizhi_action">
						<label for="morendizhi">设为默认取货信息</label>
					</label>
				</span>
		</div>
		</form>
		<a href="javascript:;" class="preservation" onclick="selftake_edit()">保存</a>
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
<script type='text/javascript' src='{{asset('public/Wechat')}}/js/public/jquery.min.js' charset='utf-8'></script>
<script type='text/javascript' src='{{asset('public/Wechat')}}/js/public/light7.min.js' charset='utf-8'></script>
<script type='text/javascript' src='{{asset('public/Wechat')}}/js/editpugoods.js' charset='utf-8'></script>
</body>
</html>
