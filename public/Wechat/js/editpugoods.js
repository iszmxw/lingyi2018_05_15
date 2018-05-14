$(function(){
    var id = getUrlParam("selftake_id");
    if(!id){
        $.toast("程序发生了点错误");
        setTimeout(function(){
            window.history.go(-1);
        },1000);
    }
    var _token = $("#_token").val();
    var zerone_user_id = $("#zerone_user_id").val();//userID
    //查询返回来(新添加)的自取信息
    var selftake_info = "http://develop.01nnt.com/api/wechatApi/selftake_info";
    $.post(
        selftake_info,
        {'zerone_user_id': zerone_user_id, '_token': _token,'self_take_id':id},
        function (json) {
            console.log(json);
            if (json.status == 1) {
                // var selftake_id = json.data.selftake_info.id;
                // var mobile = json.data.selftake_info.mobile;
                // var realname = json.data.selftake_info.realname;
                // var sex = json.data.selftake_info.sex;
                // $("#shipping_type").val("2");//修改到点自提id(存)
                // $("#selftake_id").val(selftake_id);
                // $("#shipping_mobile").val(mobile);
                // $("#shipping_realname").val(realname);
                // $("#shipping_sex").val(sex);
                // $("#selftake_info").text(realname+"-"+mobile);
                // $("#address_info_box").hide();//隐藏收货地址列表
                // $("#selftake_info_box").show();//显示自取信息列表
                // $("#address").hide();//隐藏收货地址按钮
                // $("#select_distribution").text('到店自取');//配送方式
            } else if (json.status == 0) {
                $.toast("没找到您的信息喔,刷新一下吧");
            }
        }
    );
});
//获取url中的参数
function getUrlParam(name) {
 var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
 var r = window.location.search.substr(1).match(reg); //匹配目标参数
 if (r != null) return unescape(r[2]); return null; //返回参数值
}
