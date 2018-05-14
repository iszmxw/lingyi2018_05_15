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
                var selftake_id = json.data.selftake_info.id;
                var mobile = json.data.selftake_info.mobile;
                var realname = json.data.selftake_info.realname;
                var sex = json.data.selftake_info.sex;
                var status = json.data.selftake_info.status;
                $("#selftake_id").val(selftake_id);
                $("#realname").val(realname);
                $("#mobile").val(mobile);
                if(sex==1){
                    $("#sex2").attr("checked",false);
                    $("#sex1").attr("checked","checked");
                }else{
                    $("#sex1").attr("checked",false);
                    $("#sex2").attr("checked","checked");
                }
                if(status && status == 1){
                    $("#morendizhi").attr("checked","checked");
                }
            } else if (json.status == 0) {
                $.toast("没找到您的信息喔,刷新一下吧");
            }
        }
    );
});
function selftake_edit(){
    var $edit_form = $("#selftake_edit");
    var url = $edit_form.attr("action");
    var data = $edit_form.serialize();
    console.log(data);
        $.post(
        url,
        data,
        function (json) {
            console.log(json);
            if (json.status == 1) {
                $.toast("修改成功");
                setTimeout(function(){
                    
                    var selftake_id = json.data.selftake_id;
                    var status = json.data.return;
                    $.toast("添加成功");
                    //window.location="http://develop.01nnt.com/zerone/wechat/online_order?selftake_id="+selftake_id+"&status="+status;
                },1000);
            } else if (json.status == 0) {
                $.toast("没找到您的信息喔,刷新一下吧");
            }
        }
    );
}
//自取信息选择
function select_morendizhi(obj){
    if($("#morendizhi").is(":checked")){
        $("#morendizhi").removeAttr('checked');
        $("#morendizhi_action").removeClass('morendizhi_action');
    }else{
        $("#morendizhi").prop("checked",true);
        $("#morendizhi_action").addClass('morendizhi_action');
    }
}
//获取url中的参数
function getUrlParam(name) {
 var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
 var r = window.location.search.substr(1).match(reg); //匹配目标参数
 if (r != null) return unescape(r[2]); return null; //返回参数值
}
