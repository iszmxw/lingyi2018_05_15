<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>线上订单-订单详情</title>
	<meta name="viewport" content="initial-scale=1, maximum-scale=1">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<link rel="stylesheet" href="{{asset('public/Wechat')}}/css/public/light7.min.css">
	<link rel="stylesheet" href="{{asset('public/Wechat')}}/css/newReceivingAddress.css">
</head>
<body>
<div class="page">
	<input type="hidden" name="fansmanage_id" id="fansmanage_id" value="{{session("zerone_auth_info.organization_id")}}">
	<input type="hidden" name="store_id" id="store_id" value="{{session("store_id")}}">
    <input type="hidden" name="shop_user_id" id="shop_user_id" value="{{session("zerone_auth_info.shop_user_id")}}">
	<div class="g-flexview">
		<form role="form" id="ress_add" action="{{ url('api/api/wechatApi/address_add') }}">
		    <input type="hidden" name="zerone_user_id" id="zerone_user_id" value="{{session("zerone_auth_info.zerone_user_id")}}">
		    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
			<div class="main_block">
				<div class="main_item">
					<ul>
						<li class="a_b_b a_b_t">
							<div class="item_title title_f">联系人:</div>
							<div class="item_input">
								<input type="text" name="realname" id="realname" placeholder="请填写收货人的姓名">
							</div>
							<!-- <div class="weixin_ress"></div> -->
						</li>
						<li class="a_b_b a_b_t">
							<div class="item_title title_f"></div>
							<div class="item_input item_input_pd1">
								<input type="radio" id="userinfo2" name="sex" checked="checked" value="1" class="radio_address">
								<label for="userinfo2">先生</label>
								<input type="radio" id="userinfo1" name="sex" class="radio_address" value="2">
								<label for="userinfo1">女士</label>
							</div>
						</li>
						<li class="a_b_b a_b_t">
							<div class="item_title title_f item_input_pd">手机号码:</div>
							<div class="item_input item_input_pd">
								<input type="text" type="number" name="mobile" min="0" max="9" placeholder="请填写收货人的手机号码">
							</div>
						</li>
						<li class="a_b_t">
							<div class="item_title title_f item_input_pd">收货地址:</div>
							<div class="item_input item_input_pd item_icon item_input_right">
								<input type="text" name="address_info" placeholder="请选择" id="city-picker">
							</div>
							<!-- <div class="qingxuanz"><p>请选择</p></div> -->
						</li>
						<li class="a_b_t">
							<div class="list-block">
								<textarea name="address" id="address" placeholder="详细地址"></textarea>
							</div>
						</li>
					</ul>
				</div>
			</div>
			<div class="morendizhi">
					<span>
						<input type="checkbox" name="status" id="morendizhi">
						<label><label for="morendizhi">设为默认地址</label></label>
					</span>
			</div>
			<a href="javascript:;" class="preservation" onclick="ress_add()">保存</a>
		</form>
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
<script type='text/javascript' src='{{asset('public/Wechat')}}/js/public/light7-city-picker.js' charset='utf-8'></script>
<script type='text/javascript' src='{{asset('public/Wechat')}}/js/newReceivingAddress.js?v=<?php echo time(); ?>"' charset='utf-8'></script>
</body>
</html>
