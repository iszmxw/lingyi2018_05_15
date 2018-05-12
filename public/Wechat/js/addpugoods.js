function select_morendizhi(){
    alert();
}
//自取信息选择
function select_morendizhi(obj){
    if($("#morendizhi").is(":checked")){
        alert("a");
    }else{
alert("b");
    }
    // $(":radio[name='morendizhi']").removeAttr('checkbox');//找到所有单选框删除选择状态
    // $(obj).find("input").attr("checked","checked");//赋值当前单选框状态
    // $(":radio[name='selftake']").removeClass('action');//到所有单选框inco删除action状态
    // $(obj).find("input").addClass('action');//赋值当前单选框icon
    // var ress_info = $(obj).find("input").val();
    // $("#selftake_info").text(ress_info);//赋值上面的
}
