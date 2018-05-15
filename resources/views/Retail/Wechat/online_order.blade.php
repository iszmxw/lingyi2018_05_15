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
    <link rel="stylesheet" href="{{asset('public/Wechat')}}/css/onlineOrder15-5.css">
  </head>
  <body>
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
	<input type="hidden" name="fansmanage_id" id="fansmanage_id" value="{{session("zerone_auth_info.organization_id")}}">
	<input type="hidden" name="store_id" id="store_id" value="{{session("store_id")}}">
    <input type="hidden" name="zerone_user_id" id="zerone_user_id" value="{{session("zerone_auth_info.zerone_user_id")}}">
    <input type="hidden" name="shop_user_id" id="shop_user_id" value="{{session("zerone_auth_info.shop_user_id")}}">
    <input type="hidden" id="dispatch" value="{{url('api/wechatApi/dispatch_mould')}}">
    <input type="hidden" id="order_submit" value="{{url('api/wechatApi/order_submit')}}">
    <div class="page">
	    <div class="g-flexview">
            <input type="hidden" name="shipping_type" id="shipping_type">
            <input type="hidden" name="address_info" id="address_info_ch">
            <input type="hidden" name="selftake_info_ch" id="selftake_info_ch">
	        <div class="top">
	            <div class="top_box">
					<a href="javascript:;" class="return_btn"></a>
					<a href="javascript:;" class="personal_btn"></a>
	            </div>
	        </div>
	        <div class="order order_m">
				<div class="order_box">
					<h3 class="order_state15-10">待支付</h3>
                    <a href="http://develop.01nnt.com/zerone/wechatRetail/address_add" id="address" class="address_btn" external>
                    <i></i>添加收货地址</a>
                    <a href="http://develop.01nnt.com/zerone/wechatRetail/selftake_add" id="addselftake" class="address_btn" external>
                    <i></i>添加取货信息</a>
			    	<div class="item address_info_box" id="address_info_box" onclick="ress_list()">
				    	<div class="row">
					      <div class="col-20 item_info"><span id="address_info_name">收货地址</span></div>
					      <div class="col-80 item_info1 item_icon">
							<p id="address_info"></p>
					      </div>
					    </div>
			    	</div>
                    <div class="item selftake_info_box" id="selftake_info_box" onclick="selftake_list()">
				    	<div class="row">
					      <div class="col-20 item_info"><span id="selftake_info_name">自取信息</span></div>
					      <div class="col-80 item_info1 item_icon">
							<p id="selftake_info"></p>
					      </div>
					    </div>
			    	</div>
				    <div class="item" onclick="show('selectexpress')">
					    <div class="row">
					      <div class="col-20 item_info"><span>配送方式</span></div>
					      <div class="col-80 item_info1 item_icon">
							<p id="select_distribution">快递配送</p>
					      </div>
					    </div>
				    </div>
				    <div class="item" onclick="show('alert')">
					    <div class="row">
					      <div class="col-20 item_info"><span>备注</span></div>
					      <div class="col-80 item_info1 item_icon">
							<p id="remarks_box">请输入您需要交代的话~</p>
					      </div>
					    </div>
				    </div>
				</div>
				<div class="emotion_icon"></div>
	        </div>
	        <div class="order order_m1">
				<div class="order_address1">
					<div class="row order_address_m">
				      <div class="col-50 dindanmx"><i></i>订单明细<em id="order_num"></em></div>
				      <!-- <div class="col-50 riqi"></div> -->
				    </div>
		    	    <div class="order_list">
					    <div id="order_list">
                        </div>
				        <div class="order_btn order_zongji dispatch_hook" id="dispatch_hook">
			            	<p><span class="yunfei">运费</span><em id="dispatch_price"></em></p>
			            </div>
			            <div class="order_btn order_zongji">
			            	<p id="order_num_price"><em>总计</em></p>
			            </div>
		    	    </div>
				</div>
	        </div>
	        <div class="tijiao">
				<a href="javascript:;" class="tijao_btn" onclick="sbOrder()">提交订单<em id="order_btn_price"></em></a>
	        </div>
	    </div>
		<!-- 收货地址alert -->
		<div class="popup_alert quhuoinfo" id="quhuoinfo">
			<div class="quhuo alert_bottom popup_alert_hook">
				<p class="quhuoinfo">我的收货地址</p>
				<div class="max_height_box" id="ress_list_box">
				</div>
				<a href="http://develop.01nnt.com/zerone/wechatRetail/address_add"
                class="add_address_btn my_text_align" external>添加收货地址</a>
			</div>
		</div>
		<!-- 收货地址alert -->
        <!-- 自取信息alert -->
		<div class="popup_alert quhuoinfo" id="quhuoinfo_selftake">
			<div class="quhuo alert_bottom popup_alert_hook">
				<p class="quhuoinfo">我的取货信息</p>
				<div class="max_height_box" id="selftake_list_box">
				</div>
				<a href="http://develop.01nnt.com/zerone/wechatRetail/selftake_add"
                class="add_address_btn my_text_align" external>添加取货信息</a>
			</div>
		</div>
		<!-- 自取信息alert -->
		<!-- alert -->
		<div class="popup_alert alert" id="alert">
			<div class="quhuo alert_width popup_alert_hook">
				<p class="quhuoinfo">订单备注</p>
				<div class="max_height_box">
					<div class="list-block">
    					<textarea placeholder="请输入您需要交代的话~" id="remarks"></textarea>
    				</div>
				</div>
				<div class="alert_btn_wz">
					<!-- <a href="javascript:;" class="btn_alert my_text_align btn_alert_bg1">取消</a> -->
					<a href="javascript:;" class="btn_alert my_text_align btn_alert_bg" onclick="remarks()">确认</a>
				</div>
			</div>
		</div>
		<!-- alert -->
        <!-- alert -->
        <div class="popup_alert selectexpress" id="selectexpress">
            <div class="quhuo alert_width popup_alert_hook">
                <p class="quhuoinfo">选择配送方式</p>
                <div class="max_height_box">
                    <a href="javascript:;" class="action" onclick="selectexpress(this,1)">快递配送</a>
                    <a href="javascript:;" onclick="selectexpress(this,2)">到店自提</a>
                </div>
                <div class="alert_btn_wz">
                    <!-- <a href="javascript:;" class="btn_alert my_text_align btn_alert_bg1">取消</a> -->
                    <a href="javascript:;" onclick="address_user()"
                    class="btn_alert my_text_align btn_alert_bg ress_confirm"
                    id="ress_confirm" external>确认</a>
					<a href="javascript:;" onclick="selectSelftake()"
                    class="btn_alert my_text_align btn_alert_bg peisong_confirm"
                    id="peisong_confirm" external>确认</a>
                </div>
            </div>
        </div>
        <!-- alert -->
    </div>

    <script type='text/javascript' src='{{asset('public/Wechat')}}/js/public/jquery.min.js' charset='utf-8'></script>
    <script type='text/javascript' src='{{asset('public/Wechat')}}/js/public/light7.min.js' charset='utf-8'></script>
    <script type='text/javascript' src='{{asset('public/Wechat')}}/js/onlineOrder15-5.js?v=<?php echo time(); ?>"' charset='utf-8'></script>
  </body>
</html>
