$(function(){
                  //     $.smConfig.rawCitiesData =
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
                  // ];
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
                console.log(address_info);
                console.log($.smConfig.rawCitiesData+"++++");
                  //$.smConfig.rawCitiesData = address_info;
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


    // $("#city-picker").change(function() {
    //     var str = $("#city-picker").val();
    //     var aa = str.split(" ");
    //     console.log(aa);
    // });

});
