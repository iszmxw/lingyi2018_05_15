$(function(){
    var fansmanage_id = $("#fansmanage_id").val();//联盟主组织ID
    var _token = $("#_token").val();
    var store_id = $("#store_id").val();//店铺ID
    var zerone_user_id = $("#zerone_user_id").val();//userID
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
            } else if (json.status == 0) {
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
            console.log(json);
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
            str += '<input type="radio" id="userinfo'+address_id+'" name="dizhi" checked="checked" class="radio_address">'+
            '<label for="userinfo'+address_id+'">';
        }else{
            str += '<input type="radio" id="userinfo'+address_id+'" name="dizhi" class="radio_address action"><label for="userinfo'+address_id+'">';
        }
        str += '<label for="userinfo'+address_id+'">'+ress_info+'</label>'+realname+'&nbsp;&nbsp;&nbsp;&nbsp;'+mobile+'</label>'+
        '</div>'+
        '<div class="col-15 right_height"><a href="javascript:;" class="update_address"></a></div>'+
    '</div>';
    return str;
}
function select_ress(address_id,obj){

    console.log($(obj).find("input").attr("checked"));
}
//隐藏alert
$(".popup_alert").click(function(e){
    //stopPropagation(e);
    if(!$(e.target).is(".popup_alert_hook *") && !$(e.target).is(".popup_alert_hook")){
        $(".popup_alert_hook").removeClass('fadeInUp').addClass("fadeOutDown");
           setTimeout(function(){
              $(".popup_alert").css({display: 'none'});
         },250);
    }
})
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
