$(function(){
    var fansmanage_id = $("#fansmanage_id").val();//联盟主组织ID
    var _token = $("#_token").val();
    var store_id = $("#store_id").val();//店铺ID
    var zerone_user_id = $("#zerone_user_id").val();//店铺ID
    //查询用户默认收货地址信息
    var address_url = "http://develop.01nnt.com/api/wechatApi/address";
    $.post(
        address_url,
        {'fansmanage_id': fansmanage_id, '_token': _token, 'store_id': store_id,'zerone_user_id':zerone_user_id},
        function (json) {
            console.log(json);
            if (json.status == 1) {
                var address_info = json.data.address_info.city_name + json.data.address_info.city_name +
                                    json.data.address_info.district_name +json.data.address_info.address;
                $("#address_info").text(address_info); 
            } else if (json.status == 0) {
                console.log(json.msg);
            }
        }
    );
});

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
