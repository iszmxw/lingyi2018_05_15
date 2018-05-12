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
function selftake_add_cm(){
    var $selftake_add = $("#selftake_add");
    var url = $selftake_add.attr("action");
    var data = $selftake_add.serialize();
    $.post(url,data,function(json){
        if(json.status==1){
            console.log(json);
            var selftake_id = json.data.selftake_id;
            var status = json.data.return;
            var realname = json.data.realname;
            var mobile = json.data.mobile;
            $.toast("添加成功");
            window.location="http://develop.01nnt.com/zerone/wechat/online_order?selftake_id="+selftake_id+"&status="+status+"&realname="+
                            realname+"-"+mobile;
        }else if(json.status==0){
            $.toast(json.data);
        }
    });
}
