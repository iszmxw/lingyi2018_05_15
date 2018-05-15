//隐藏alert
$(".popup_alert").click(function(e){
    //stopPropagation(e);
    if(!$(e.target).is(".popup_alert_hook *") && !$(e.target).is(".popup_alert_hook")){
        hide();
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
function show(){
    $(".popup_alert").css({display: 'flex'});
    $(".popup_alert_hook").addClass('fadeInUp');
}
function hide() {
    $(".popup_alert").css({display: 'none'});
    $(".popup_alert_hook").removeClass('fadeInUp').addClass("fadeOutDown");
}