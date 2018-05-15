$(function(){
    var select_url = "http://develop.01nnt.com/api/wechatApi/select_address";
    var zerone_user_id =$("#zerone_user_id").val();
    var _token =$("#_token").val();
    $.post(select_url, {
        "zerone_user_id":zerone_user_id,
        "_token":_token
    }, function(json) {
            $.smConfig.rawCitiesData = [];
        if (json.status == 1) {
                var address_info = json.data.address_info;
                //address_info传值
                    $("#city-picker").cityPicker({
                      toolbarTemplate: '<header class="bar bar-nav">\
                      <button class="button button-link pull-right close-picker">确认</button>\
                      <h1 class="title">选择收货地址</h1>\
                      </header>'
                  },address_info);
        } else if (json.status == 0) {
            $.toast("喔~获取地址出错了");
        }
    });
});
//默认地址选择
function select_morendizhi(obj){
    if($("#morendizhi").is(":checked")){
        $("#morendizhi").removeAttr('checked');
        $("#morendizhi_action").removeClass('morendizhi_action');
    }else{
        $("#morendizhi").prop("checked",true);
        $("#morendizhi_action").addClass('morendizhi_action');
    }
}
function ress_add(){
    var $ress_add = $("#ress_add");
    var url = $ress_add.attr("action");
    var data = $ress_add.serialize();
    console.log(data+"++++");
    $.post(url,data, function(json) {
        console.log(json);
        if (json.status == 1) {
            $.toast("添加成功");
            setTimeout(function(){
                var address_id = json.data.address_id;
                var status = json.data.return;
                window.location="http://develop.01nnt.com/zerone/wechatRetail/online_order?address_id="+address_id+"&status="+status;
            },1000);
        } else if (json.status == 0) {
            $.toast(json.data);
        }
    });
}
