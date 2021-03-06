$(function(){
    var fansmanage_id = $("#fansmanage_id").val();//联盟主组织ID
    var _token = $("#_token").val();
    var store_id = $("#store_id").val();//店铺ID
    var zerone_user_id = $("#zerone_user_id").val();//userID
    var shop_user_id = $("#shop_user_id").val();//用户店铺ID
    //查询用户默认收货地址信息
    var address_url = "http://develop.01nnt.com/api/wechatApi/address";
    $.post(
        address_url,
        {'fansmanage_id': fansmanage_id, '_token': _token, 'store_id': store_id,'zerone_user_id':zerone_user_id},
        function (json) {
            console.log(json);
            if (json.status == 1) {
                var address_info = json.data.address_info.city_name + json.data.address_info.city_name +
                                    json.data.address_info.district_name +json.data.address_info.address
                                    +json.data.address_info.mobile;
                $("#address_info").text(address_info);
                $("#address_info_box").show();//现在添加收货地址按钮
            } else if (json.status == 0) {
                show('selectexpress');//现有默认收货地址弹出选择(快递自取)选项
                console.log(json.msg);
            }
        }
    );
    //查询购物车商品数据
    var cart_list_url = "http://develop.01nnt.com/api/wechatApi/shopping_cart_list";
    $.post(
        cart_list_url,
        {'fansmanage_id': fansmanage_id, '_token': _token, 'store_id': store_id,'zerone_user_id':zerone_user_id,
        'user_id':shop_user_id},
        function (json) {
            console.log(json);
            if (json.status == 1) {
                var str = "";//拼接字符串
                var order_num_price = 0;
                for (var i = 0; i < json.data.goods_list.length; i++) {
                    str += order_list_box(json.data.goods_list[i].goods_name,json.data.goods_list[i].num,
                                json.data.goods_list[i].goods_price);
                    order_num_price +=parseFloat(json.data.goods_list[i].goods_price) *
                                      parseInt(json.data.goods_list[i].num);
                }
                var $order_list =$("#order_list");
                $order_list.empty();
                $order_list.append(str);
                //购物车总数
                var order_num = json.data.total;
                $("#order_num").html("&nbsp;&nbsp;("+order_num+"份商品)");
                //总价
                $("#order_num_price").html("<em>总计</em>&nbsp;&nbsp;&yen;"+order_num_price.toFixed(2));
                $("#order_btn_price").html("&yen;"+order_num_price.toFixed(2));
            } else if (json.status == 0) {
                window.history.go(-1);
                console.log(json.msg);
            }
        }
    );

});
//收货地址列表查询
function ress_list(){
    var _token = $("#_token").val();
    var zerone_user_id = $("#zerone_user_id").val();//userID
    var url = "http://develop.01nnt.com/api/wechatApi/address_list";
    $.post(
        url,
        {'_token': _token,'zerone_user_id':zerone_user_id},
        function (json) {
            if (json.status == 1) {
                var str ="";
                for (var i = 0; i < json.data.address_list.length; i++) {
                    var ress_info =json.data.address_list[i].province_name + json.data.address_list[i].city_name+
                                    json.data.address_list[i].district_name + json.data.address_list[i].address;
                    var realname =json.data.address_list[i].realname;
                    var mobile =json.data.address_list[i].mobile;
                    var status =json.data.address_list[i].status;
                    var address_id =json.data.address_list[i].address_id;
                    str += ress_list_box(ress_info,realname,mobile,status,address_id);
                }
                var $ress_list = $("#ress_list_box");
                $ress_list.empty();
                $ress_list.append(str);
                show('quhuoinfo');
            } else if (json.status == 0) {
                show('quhuoinfo');
                console.log(json.msg);
            }
        }
    );
}
//收货地址列表拼接
function ress_list_box(ress_info,realname,mobile,status,address_id){
    var str = "";
    str += '<div class="row alert_list">'+
        '<div class="col-85 radio_css" onclick="select_ress('+address_id+',this)">';
        if(status && status == 1){
            str += '<input type="radio" id="userinfo'+address_id+'" name="dizhi" checked="checked" value="'+ress_info+realname+mobile+'" class="radio_address action">'+
            '<label for="userinfo'+address_id+'">';
        }else{
            str += '<input type="radio" id="userinfo'+address_id+'" name="dizhi" value="'+ress_info+realname+mobile+'" class="radio_address"><label for="userinfo'+address_id+'">';
        }
        str += '<label for="userinfo'+address_id+'">'+ress_info+'</label>'+realname+'&nbsp;&nbsp;&nbsp;&nbsp;'+mobile+'</label>'+
        '</div>'+
        '<div class="col-15 right_height"><a href="javascript:;" class="update_address"></a></div>'+
    '</div>';
    return str;
}
//订单列表拼接
function order_list_box(goods_name,num,goods_price){
    var str = "";
    str += '<div class="row order_list_box">'+
        '<div class="col-60">'+goods_name+'</div>'+
        '<div class="col-15">&#215;'+num+'</div>'+
        '<div class="col-25">&yen;'+goods_price+'</div>'+
    '</div>'
    return str;
}
//收货地址选择
function select_ress(address_id,obj){
    $(":radio[name='dizhi']").removeAttr('checked');//找到所有单选框删除选择状态
    $(obj).find("input").attr("checked","checked");//赋值当前单选框状态
    $(":radio[name='dizhi']").removeClass('action');//到所有单选框inco删除action状态
    $(obj).find("input").addClass('action');//赋值当前单选框icon
    var ress_info = $(obj).find("input").val();
    $("#address_info").text(ress_info);//赋值上面的
}
//配送选择
function selectexpress(obj,address){
    var $this = $(obj);
    $this.addClass("action").siblings().removeClass('action');
    //隐藏添加收货地址按钮
    if (address == 1) {//快递配送
        $("#ress_confirm").show();
        $("#peisong_confirm").hide();
    }else if(address == 2){//到店自提
        $("#ress_confirm").hide();
        $("#peisong_confirm").show();
    }
}
//隐藏alert
// $(".popup_alert").click(function(e){
//     //stopPropagation(e);
//     if(!$(e.target).is(".popup_alert_hook *") && !$(e.target).is(".popup_alert_hook")){
//         $(".popup_alert_hook").removeClass('fadeInUp').addClass("fadeOutDown");
//            setTimeout(function(){
//               $(".popup_alert").css({display: 'none'});
//          },250);
//     }
// })
//因为冒泡了，会执行到下面的方法。
function stopPropagation(e) {
    var ev = e || window.event;
    if (ev.stopPropagation) {
        ev.stopPropagation();
    }
    else if (window.event) {
        window.event.cancelBubble = true;//兼容IE
    }
}
function show(obj){
     $("#"+obj).css({display: 'flex'});
    $("#"+obj+" .popup_alert_hook").addClass('fadeInUp');
}
function hide(obj) {
    $("#"+obj).css({display: 'none'});
    $("#"+obj+" .popup_alert_hook").removeClass('fadeInUp').addClass("fadeOutDown");
}
