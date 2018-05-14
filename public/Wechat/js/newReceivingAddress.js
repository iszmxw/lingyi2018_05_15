$(function(){
    var select_url = "http://develop.01nnt.com/api/wechatApi/select_address";
    var zerone_user_id =$("#zerone_user_id").val();
    var _token =$("#_token").val();
    $.post(select_url, {
        "zerone_user_id":zerone_user_id,
        "_token":_token
    }, function(json) {
        if (json.status == 1) {
                var address_info = json.data.address_info;
                console.log(address_info);
                  $.smConfig.rawCitiesData = address_info;
                  // [
                  //     {
                  //         "name":"1231",
                  //         "sub":[
                  //             {
                  //                 "name":"请选择"
                  //             },
                  //             {
                  //                 "name":"13213"
                  //             }
                  //         ],
                  //         "type":0
                  //     }
                  // ]
        } else if (json.status == 0) {
            $.toast("喔~获取地址出错了");
        }
    });

  $("#city-picker").cityPicker({
      toolbarTemplate: '<header class="bar bar-nav">\
      <button class="button button-link pull-right close-picker">确认</button>\
      <h1 class="title">选择收货地址</h1>\
      </header>'
    });
    // $("#city-picker").change(function() {
    //     var str = $("#city-picker").val();
    //     var aa = str.split(" ");
    //     console.log(aa);
    // });

});
