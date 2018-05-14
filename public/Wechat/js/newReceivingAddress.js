$("#city-picker").cityPicker({
    toolbarTemplate: '<header class="bar bar-nav">\
    <button class="button button-link pull-right close-picker">确认</button>\
    <h1 class="title">选择收货地址</h1>\
    </header>'
  });
  $(function(){
      console.log($.smConfig.rawCitiesData);
    $.smConfig.rawCitiesData = [
        {
            "name":"北dfgfd京",
            "sub":[
                {
                    "name":"请选择"
                },
                {
                    "name":"东城dfgdf区"
                }
            ],
            "type":0
        }
    ]
  });
