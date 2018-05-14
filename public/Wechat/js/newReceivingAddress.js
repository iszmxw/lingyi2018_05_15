$(function(){
  // $.smConfig.rawCitiesData = [
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
  $("#city-picker").cityPicker({
      toolbarTemplate: '<header class="bar bar-nav">\
      <button class="button button-link pull-right close-picker">确认</button>\
      <h1 class="title">选择收货地址</h1>\
      </header>'
    });
    $("#city-picker").change(function() {
        var str = $("#city-picker").val();
        var aa = str.split(" ");
        console.log(aa);
    });

});
