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
	<link rel="stylesheet" href="{{asset('public/Wechat')}}/css/onlineOrder15-19.css">
</head>
<body>
<div class="page">
	<div class="g-flexview">
		<div class="top">
			<div class="top_box">
				<a href="javascript:;" class="return_btn"></a>
				<a href="javascript:;" class="personal_btn"></a>
			</div>
		</div>
		<div class="order order_m">
			<div class="order_box">
				<h3 class="order_state">待支付</h3>
				<p class="price"><em>&yen;</em>888<span>9折</span></p>
				<p class="order_number">LYLSXS20180414160808-1</p>
				<div class="order_btn">
					<a href="javascript:;"><i></i>取消订单</a>
				</div>
			</div>
			<div class="emotion_icon"></div>
		</div>
		<div class="order order_m1">
			<div class="order_address">
				<div class="row">
					<div class="col-20"><span class="dizhi">配送到</span></div>
					<div class="col-80">
						<p class="order_address_p">龙城广场万汇大厦1606</p>
						<p>薛志豪（先生） 13824322924</p>
					</div>
				</div>
				<div class="order_btn">
					<div class="row" onclick="show()">
						<div class="col-20"><span class="dizhi">备注</span></div>
						<div class="col-80 item_icon">
							<p class="">要点要点要点要点的要点</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="order order_m1">
			<div class="order_address1">
				<div class="row order_address_m">
					<div class="col-50 dindanmx"><i></i>订单明细<em>&nbsp;&nbsp;(6份商品)</em></div>
					<div class="col-50 riqi">2018-04-19 05:45</div>
				</div>
				<div class="order_list">
					<div class="row order_list_box">
						<div class="col-60">2018春装新款格子修</div>
						<div class="col-15">&#215;1</div>
						<div class="col-25">&yen;88</div>
					</div>
					<div class="row order_list_box">
						<div class="col-60">2018春装新款格子修</div>
						<div class="col-15">&#215;1</div>
						<div class="col-25">&yen;88</div>
					</div>
					<div class="row order_list_box">
						<div class="col-60">2018春装新款格子修</div>
						<div class="col-15">&#215;1</div>
						<div class="col-25">&yen;88</div>
					</div>
					<div class="row order_list_box">
						<div class="col-60">2018春装新款格子修</div>
						<div class="col-15">&#215;1</div>
						<div class="col-25">&yen;88</div>
					</div>
					<div class="row order_list_box">
						<div class="col-60">2018春装新款格子修</div>
						<div class="col-15">&#215;1</div>
						<div class="col-25">&yen;88</div>
					</div>
					<div class="row order_list_box">
						<div class="col-60">2018春装新款格子修</div>
						<div class="col-15">&#215;1</div>
						<div class="col-25">&yen;88</div>
					</div>
					<div class="row order_list_box">
						<div class="col-60">2018春装新款格子修</div>
						<div class="col-15">&#215;1</div>
						<div class="col-25">&yen;88</div>
					</div>
					<div class="order_btn order_zongji">
						<p><span class="yunfei">运费</span><em>&yen;50</em></p>
					</div>
					<div class="order_btn order_zongji">
						<p><em>总计</em>&nbsp;&nbsp;&yen;789</p>
					</div>
				</div>
			</div>
		</div>
		<div class="tijiao">
			<a href="javascript:;" class="tijao_btn">确认付款<em>&yen;568</em></a>
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
<script type='text/javascript' src='{{asset('public/Wechat')}}/js/public/jquery.min.js' charset='utf-8'></script>
<script type='text/javascript' src='{{asset('public/Wechat')}}/js/public/light7.min.js' charset='utf-8'></script>
<script type='text/javascript' src='{{asset('public/Wechat')}}/js/onlineOrder15-19.js' charset='utf-8'></script>
</body>
</html>
