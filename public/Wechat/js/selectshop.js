$(function(){
	//获取当前月日
	var myDate = new Date();
	var month = myDate.getMonth();
	var dateri = myDate.getDate();
	var arr = ['一', '二', '三', '四', '五', '六', '七', '八', '九', '十', '十一', '十二'];
	month = arr[parseInt(month+1,10)-1]
	$("#month").html(dateri+"<span>"+month+"月</span>");
});
function searchshop(){
     wx.getLocation({
      success: function (res) {
      	var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
	       	var longitude = res.longitude ; // 经度，浮点数，范围为180 ~ -180。
	        var speed = res.speed; // 速度，以米/每秒计
	        var accuracy = res.accuracy; // 位置精度
	        var organization_id=$("#organization_id").val();
	        var _token=$("#_token").val();
	        var keyword=$("#keyword").val();
	        var url = "http://develop.01nnt.com/api/wechatApi/store_list";
         $.showPreloader('加载中');
         //获取店铺
	        $.post(
	        	url,
	            {'organization_id': organization_id,'_token':_token,'keyword':keyword,'lat':latitude,'lng':longitude},
	        	function(json){
	        		if (json.status == 1) {
	        			var str="";
	        			for (var i = json.data.storelist.length - 1; i >= 0; i--) {
    					str += shoplist(json.data.storelist[i].name,json.data.storelist[i].address,json.data.storelist[i].logo,json.data.storelist[i].distance);
	        			}
	        			var $shoplist = $("#shoplist");
	        			var $shopnumber = $("#shopnumber");
	        			$shoplist.empty();
	        			$shoplist.append(str);
	        			$shopnumber.text("("+json.data.storelist.length+"家)");
    				$.hidePreloader();
	        		}
        	})
      },
      cancel: function (res) {
        alert('用户拒绝授权获取地理位置');
      }
    });
}

function shoplist(name,address,logo,distance) {
	var str = "<li>"+"<a href='javascript:;'>"+
					"<div class='shop_img'>"+
						"<img src='http://develop.01nnt.com/"+logo+"'>"+
					"</div>"+
					"<div class='shop_right'>"+
						"<section class='shop_name'><h3><span>"+name+"</span></h3></section>"+
						"<section class='shop_youhui'><p>满120减10</p></section>"+
						"<section class='shop_juli'><p>距离<span><em><</em>&nbsp;"+distance+"km</span></p></section>"+
						"<section class='shop_juli'><p>地址:"+address+"</p></section>"+
					"</div>"+
				"</a>"+
			"</li>";
	return str;
}
