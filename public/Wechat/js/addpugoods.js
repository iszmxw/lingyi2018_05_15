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
    console.log(url);
    console.log(data);
}
