$(function(){
    var fansmanage_id = $("#fansmanage_id").val();//联盟主组织ID
    var _token = $("#_token").val();
    var store_id = $("#store_id").val();//店铺ID
    var zerone_user_id = $("#zerone_user_id").val();//userID
    var shop_user_id = $("#shop_user_id").val();//用户店铺ID
    var status = getUrlParam("status");//返回自取状态
    if(status && status=="selftake"){
        var selftake_id = getUrlParam("selftake_id");
        //查询返回来(新添加)的自取信息
        var selftake_info = "http://develop.01nnt.com/api/wechatApi/selftake_info";
        $.post(
            selftake_info,
            {'zerone_user_id': zerone_user_id, '_token': _token,'self_take_id':selftake_id},
            function (json) {
                if (json.status == 1) {
                    var selftake_id = json.data.selftake_info.id;
                    var mobile = json.data.selftake_info.mobile;
                    var realname = json.data.selftake_info.realname;
                    var sex = json.data.selftake_info.sex;
                    $("#shipping_type").val("2");//修改到点自提id(存)
                    $("#selftake_info").text(realname+"-"+mobile);
                    $("#address_info_box").hide();//隐藏收货地址列表
                    $("#selftake_info_box").show();//显示自取信息列表
                    $("#address").hide();//隐藏收货地址按钮
                    $("#select_distribution").text('到店自取');//配送方式
                    //存到店自提信息
                    $("#selftake_info_ch").attr({
                        "data-selftake_id": selftake_id,
                        "data-realname": realname,
                        "data-sex": sex,
                        "data-mobile": mobile
                    });
                } else if (json.status == 0) {
                    $.toast("数据找不到了");
                }
            }
        );
    }else if(status && status =="address"){
        var address_id = getUrlParam("address_id");
        selectRessId(address_id);
    }
    else{
        //默认查找用户默认的收获地址
        address_user();
    }
    //查询购物车商品数据
    var cart_list_url = "http://develop.01nnt.com/api/wechatApi/shopping_cart_list";
    $.post(
        cart_list_url,
        {'fansmanage_id': fansmanage_id, '_token': _token, 'store_id': store_id,'zerone_user_id':zerone_user_id,
        'user_id':shop_user_id},
        function (json) {
            console.log(json);
            if (json.status == 1) {
                var str = "";//拼接字符串
                var order_num_price = 0;
                for (var i = 0; i < json.data.goods_list.length; i++) {
                    var goods_name = (json.data.goods_list[i].goods_name) ? json.data.goods_list[i].goods_name : "";
                    var num = (json.data.goods_list[i].num) ? json.data.goods_list[i].num : "";
                    var goods_price = (json.data.goods_list[i].goods_price) ? json.data.goods_list[i].goods_price : "";
                    var id = (json.data.goods_list[i].goods_id) ? json.data.goods_list[i].goods_id : "";
                    var goods_thumb = (json.data.goods_list[i].goods_thumb) ? json.data.goods_list[i].goods_thumb : "";
                    var stock = (json.data.goods_list[i].stock) ? json.data.goods_list[i].stock : "";
                    str += order_list_box(goods_name,num,goods_price,id,goods_thumb,stock);
                    order_num_price +=parseFloat(json.data.goods_list[i].goods_price) *
                                      parseInt(json.data.goods_list[i].num);
                }
                var $order_list =$("#order_list");
                $order_list.empty();
                $order_list.append(str);
                //购物车总数
                var order_num = json.data.total;
                $("#order_num").html("&nbsp;&nbsp;("+order_num+"份商品)");
                //总价
                $("#order_num_price").html("<em>总计</em>&nbsp;&nbsp;&yen;"+order_num_price);
                $("#order_btn_price").html("&yen;"+order_num_price);
                $("#order_num_price").attr('data-price', order_num_price);
            } else if (json.status == 0) {
                window.history.go(-1);
                console.log(json.msg);
            }
        }
    );

});
//收货地址列表查询
function ress_list(){
    var _token = $("#_token").val();
    var zerone_user_id = $("#zerone_user_id").val();//userID
    var url = "http://develop.01nnt.com/api/wechatApi/address_list";
    $.post(
        url,
        {'_token': _token,'zerone_user_id':zerone_user_id},
        function (json) {
            if (json.status == 1) {
                var str ="";
                for (var i = 0; i < json.data.address_list.length; i++) {
                    var province_name =(json.data.address_list[i].province_name) ? json.data.address_list[i].province_name : "";
                    var city_name = (json.data.address_list[i].city_name) ? json.data.address_list[i].city_name : "";
                    var district_name = (json.data.address_list[i].district_name) ? json.data.address_list[i].district_name : "";
                    var address = (json.data.address_list[i].address) ? json.data.address_list[i].address : "";

                    var ress_info = province_name +"-"+city_name+"-"+district_name+"-"+address;
                    var realname =json.data.address_list[i].realname;
                    var mobile =json.data.address_list[i].mobile;
                    var status =json.data.address_list[i].status;
                    var address_id =json.data.address_list[i].address_id;
                    str += ress_list_box(ress_info,realname,mobile,status,address_id);
                }
                var $ress_list = $("#ress_list_box");
                $ress_list.empty();
                $ress_list.append(str);
                show('quhuoinfo');
            } else if (json.status == 0) {
                show('quhuoinfo');
                console.log(json.msg);
            }
        }
    );
}
//根据ID查找运费和选择地址
function selectRessId(address_id){
    //查询返回来(新添加)的地址
    var zerone_user_id = $("#zerone_user_id").val();//userID
    var _token = $("#_token").val();
    var store_id = $("#store_id").val();//店铺ID
    var address_info = "http://develop.01nnt.com/api/wechatApi/address_info";
    $.post(
        address_info,
        {'zerone_user_id': zerone_user_id, '_token': _token,'address_id':address_id,'store_id':store_id},
        function (json) {
            console.log(json,"id地址");
            if (json.status == 1) {
                var province_name =(json.data.address_info.province_name) ? json.data.address_info.province_name : "";
                var city_name = (json.data.address_info.city_name) ? json.data.address_info.city_name : "";
                var area_name = (json.data.address_info.area_name) ? json.data.address_info.area_name : "";
                var address = (json.data.address_info.address) ? json.data.address_info.address : "";
                var realname =json.data.address_info.realname;
                var mobile =json.data.address_info.mobile;
                var id =json.data.address_info.id;
                var ress_info = province_name +"-"+city_name+"-"+area_name+"-"+address+"-"+
                                realname+"-"+mobile;
                var address_id =json.data.address_info.id;
                var url = $("#dispatch").val();
                var fansmanage_id = $("#fansmanage_id").val();//联盟主组织ID
                var weight = 1000;
                $.post(
                    url,
                    {'_token': _token,'fansmanage_id':fansmanage_id,'store_id':store_id,'weight':weight,'address_id':address_id},
                    function (json) {
                        console.log(json,"运费");
                        if (json.status == 1) {
                           var price = json.data.freight;
                           if (price) {
                               $("#dispatch_price").html("&yen;"+price);//赋值运费
                               $("#dispatch_hook").fadeIn("show");//存在运费显示运费标签             
                               var order_price = $("#order_num_price").data("price");//获取购物车总价格
                               var order_num_price = parseInt(price) + parseFloat(order_price);
                               $("#order_btn_price").html("&yen;"+order_num_price);//获取总计的价格
                               $("#order_num_price").html("&yen;"+order_num_price);//获取总计的价格
                               $("#address_info").text(ress_info);//赋值选择的地址信息
                               $("#shipping_type").val("1");//修改快递配送id(存)
                               $("#address_info_box").show();//显示收货地址列表
                               $("#selftake_info_box").hide();//隐藏自取信息列表
                               $("#address").hide();//隐藏收货地址按钮
                               $("#select_distribution").text('快递配送');//配送方式
                               //存选择的收获地址数据
                               $("#address_info_ch").attr({
                                   "data-address_id": id,
                                   "data-province_name": province_name,
                                   "data-city_name": city_name,
                                   "data-district_name": area_name,
                                   "data-address": address,
                                   "data-realname": realname,
                                   "data-mobile": mobile
                               });
                           }
                        } else if (json.status == 0) {
                            alert(json.msg);
                            return;
                        }
                    }
                );
            } else if (json.status == 0) {
                $.toast("数据找不到了");
            }
        }
    );
}
//查询用户默认收货地址信息
function address_user(){
    var fansmanage_id = $("#fansmanage_id").val();//联盟主组织ID
    var _token = $("#_token").val();
    var store_id = $("#store_id").val();//店铺ID
    var zerone_user_id = $("#zerone_user_id").val();//userID
    var shop_user_id = $("#shop_user_id").val();//用户店铺ID
    var address_url = "http://develop.01nnt.com/api/wechatApi/address";
    $.post(
        address_url,
        {'fansmanage_id': fansmanage_id, '_token': _token, 'store_id': store_id,'zerone_user_id':zerone_user_id},
        function (json) {
            console.log(json,"++++默认");
            if (json.status == 1) {
                var city_name = (json.data.address_info.city_name) ? json.data.address_info.city_name : "";
                var province_name = (json.data.address_info.province_name) ? json.data.address_info.province_name : "";
                var district_name = (json.data.address_info.district_name) ? json.data.address_info.district_name : "";
                var address = (json.data.address_info.address) ? json.data.address_info.address : "";
                var mobile = (json.data.address_info.mobile) ? json.data.address_info.mobile : "";
                var realname = (json.data.address_info.realname) ? json.data.address_info.realname : "";
                var address_info = province_name+"-"+city_name+"-"+district_name+"-"+address+"-"+mobile;
                var address_id = json.data.address_info.id;
                //获取运费
                var url = $("#dispatch").val();
                var weight = 1000;
                $.post(
                    url,
                    {'_token': _token,'fansmanage_id':fansmanage_id,'store_id':store_id,'weight':weight,'address_id':address_id},
                    function (json) {
                        console.log(json,"运费");
                        if (json.status == 1) {
                           var price = json.data.freight;
                           if (price) {
                               $("#dispatch_price").html("&yen;"+price);//赋值运费  
                               $("#dispatch_hook").fadeIn("show");//存在运费显示运费标签      
                               var order_price = $("#order_num_price").data("price");
                               var order_num_price = parseInt(price) + parseFloat(order_price);
                               $("#order_btn_price").html("&yen;"+order_num_price);
                               $("#order_num_price").html("&yen;"+order_num_price);
                               $("#address_info").text(address_info);
                              $("#shipping_type").val("1");//修改快递配送id(存)
                               $("#address_info_box").show();//显示收货地址列表
                               $("#selftake_info_box").hide();//隐藏自取信息列表
                               $("#address").hide();//隐藏收货地址按钮
                               //存选择的收获地址数据
                               $("#address_info_ch").attr({
                                   "data-address_id": address_id,
                                   "data-province_name": province_name,
                                   "data-city_name": city_name,
                                   "data-district_name": district_name,
                                   "data-address": address,
                                   "data-realname": realname,
                                   "data-mobile": mobile
                               });
                           }
                        } else if (json.status == 0) {
                            $("#address_info_box").show();//显示收货地址列表
                            alert(json.msg);
                        }
                    }
                );
            } else if (json.status == 0) {
                $("#selftake_info_box").hide();//隐藏自取信息列表
                $("#address").show().css('display','block');//显示收货地址按钮
                $("#address_info_box").hide();//隐藏添加收货地址按钮
                show('selectexpress');//现有默认收货地址弹出选择(快递自取)选项
                console.log(json.msg);
            }
            $("#select_distribution").text('快递配送');//配送方式
            hide("selectexpress");
        }
    );
}
//运费
// function dispatch(address_id){
//     var url = $("#dispatch").val();
//     var fansmanage_id = $("#fansmanage_id").val();//联盟主组织ID
//     var _token = $("#_token").val();
//     var store_id = $("#store_id").val();//店铺ID
//     var weight = 1000;
//     $.post(
//         url,
//         {'_token': _token,'fansmanage_id':fansmanage_id,'store_id':store_id,'weight':weight,'address_id':address_id},
//         function (json) {
//             console.log(json,"运费");
//             if (json.status == 1) {
//                var price = json.data.freight;
//                if (price) {
//                    $("#dispatch_hook").fadeIn("show");               
//                    $("#dispatch_price").html("&yen;"+price);
//                    var order_price = $("#order_num_price").data("price");
//                    var order_num_price = parseInt(price) + parseFloat(order_price);
//                    $("#order_btn_price").html("&yen;"+order_num_price);
//                    $("#order_num_price").html("&yen;"+order_num_price);
//                }
//             } else if (json.status == 0) {
//                 alert(json.msg);
//             }
//         }
//     );
// }
//自取信息列表查询
function selftake_list(){
    var _token = $("#_token").val();
    var zerone_user_id = $("#zerone_user_id").val();//userID
    var url = "http://develop.01nnt.com/api/wechatApi/selftake_list";
    $.post(
        url,
        {'_token': _token,'zerone_user_id':zerone_user_id},
        function (json) {
            console.log(json,"取货");
            if (json.status == 1) {
                var str ="";
                for (var i = 0; i < json.data.self_take_info.length; i++) {
                    var realname = (json.data.self_take_info[i].realname) ? json.data.self_take_info[i].realname : "";
                    var sex = (json.data.self_take_info[i].sex ==1 ) ? "先生" : "女士";
                    var mobile = (json.data.self_take_info[i].mobile) ? json.data.self_take_info[i].mobile : "";
                    var status = json.data.self_take_info[i].status;
                    var id =json.data.self_take_info[i].id;
                    str += selftake_list_box(realname,sex,mobile,status,id);
                }
                var $selftake_list = $("#selftake_list_box");
                $selftake_list.empty();
                $selftake_list.append(str);
                show('quhuoinfo_selftake');
            } else if (json.status == 0) {
                show('quhuoinfo_selftake');
                console.log(json.msg);
            }
            $("#dispatch_hook").hide(); //隐藏运费
             var order_price = $("#order_num_price").data("price");
             $("#order_btn_price").html("&yen;"+order_price);
             $("#order_num_price").html("&yen;"+order_price);
        }
    );
}
//查询自取默认地址
function selectSelftake(){
    $.showIndicator();
    var _token = $("#_token").val();
    var zerone_user_id = $("#zerone_user_id").val();//userID
    //查询用户默认自取信息
    var selftake_url = "http://develop.01nnt.com/api/wechatApi/selftake";
    $.post(
        selftake_url,
        {'_token': _token,'zerone_user_id':zerone_user_id},
        function (json) {
            console.log(json,"asd");
            if (json.status == 1) {
                var id = (json.data.selftake_info.id) ? json.data.selftake_info.id : "";
                var mobile = (json.data.selftake_info.mobile) ? json.data.selftake_info.mobile : "";
                var realname = (json.data.selftake_info.realname) ? json.data.selftake_info.realname : "";
                var sex = (json.data.selftake_info.sex) ? json.data.selftake_info.sex : "";
                var address_info = realname +"-"+mobile;
                $("#shipping_type").val("2");//修改到点自提id(存)
                $("#selftake_info").text(address_info);
                $("#address_info_box").hide();//隐藏收货地址列表
                $("#selftake_info_box").show();//显示自取信息列表
                $("#address").hide();//隐藏收货地址按钮
                $("#select_distribution").text('到店自取');//配送方式
                hide("selectexpress");//隐藏选择取货框
                //存到店自提信息
                $("#selftake_info_ch").attr({
                    "data-selftake_id": id,
                    "data-realname": realname,
                    "data-sex": sex,
                    "data-mobile": mobile
                });
            } else if (json.status == 0) {
                $("#address").hide();
                $("#addselftake").show().css('display','block');
                $("#select_distribution").text('到店自取');//配送方式
                hide("selectexpress");//隐藏选择取货框
                console.log(json.msg);
            }
            $("#dispatch_hook").hide(); //隐藏运费
            var order_price = $("#order_num_price").data("price");//获取购物车总价（减去运费价）
            if (order_price) {
                $("#order_btn_price").html("&yen;"+order_price);
                $("#order_num_price").html("&yen;"+order_price);
            }
             $.hideIndicator();
        }
    );
}
//收货地址列表拼接
function ress_list_box(ress_info,realname,mobile,status,address_id){
    var str = "";
    str += '<div class="row alert_list">'+
        '<div class="col-85 radio_css" onclick="select_ress('+address_id+',this)">';
        if(status && status == 1){
            str += '<input type="radio" id="userinfo'+address_id+'" name="dizhi" checked="checked"'+
                    'value="'+ress_info+realname+mobile+'" class="radio_address action">'+
                    '<label for="userinfo'+address_id+'">';
        }else{
            str += '<input type="radio" id="userinfo'+address_id+'" name="dizhi" value="'+ress_info+realname+mobile+'"'+
                    'class="radio_address"><label for="userinfo'+address_id+'">';
        }
        str += '<label for="userinfo'+address_id+'">'+ress_info+'</label>'+realname+'&nbsp;&nbsp;&nbsp;&nbsp;'+mobile+'</label>'+
        '</div>'+
        '<div class="col-15 right_height"><a href="javascript:;" class="update_address"></a></div>'+
    '</div>';
    return str;
}
//取货信息列表拼接
function selftake_list_box(realname,sex,mobile,status,id){
    var str = "";
    str += '<div class="row alert_list">'+
        '<div class="col-85 radio_css" onclick="select_selftake('+id+',this)">';
        if(status && status == 1){
            str += '<input type="radio" id="userinfo'+id+'" checked="checked" value="'+realname+"-"+mobile+'"'+
                    'name="selftake" class="radio_address action">';
        }else{
            str += '<input type="radio" id="userinfo'+id+'" name="selftake" value="'+realname+"-"+mobile+'"'+
            'class="radio_address">';
        }
        str += '<label for="userinfo'+id+'">'+realname+'（'+sex+'） '+mobile+'</label></div>'+
        '<div class="col-15"><a href="http://develop.01nnt.com/zerone/wechatRetail/selftake_edit?selftake_id='+id+'"'+
        'class="update_address" external></a></div>'+
    '</div>';
    return str;
}
//订单列表拼接
function order_list_box(goods_name,num,goods_price,id,goods_thumb,stock){
    var str = "";
    str += '<div class="row order_list_box">'+
        '<div class="col-60">'+goods_name+'</div>'+
        '<div class="col-15">&#215;'+num+'</div>'+
        '<div class="col-25">&yen;'+goods_price+'</div>'+
        '<input type="hidden" data-id="'+id+'" data-goods_name="'+goods_name+'"'+
        'data-num="'+num+'" data-goods_price="'+goods_price+'" data-goods_price="'+goods_price+'"'+
        'data-goods_thumb="'+goods_thumb+'" data-stock="'+stock+'" class="order_list_each" value="">'+
    '</div>'
    return str;
}
//收货地址选择
function select_ress(address_id,obj){
    $(":radio[name='dizhi']").removeAttr('checked');//找到所有单选框删除选择状态
    $(obj).find("input").attr("checked","checked");//赋值当前单选框状态
    $(":radio[name='dizhi']").removeClass('action');//到所有单选框inco删除action状态
    $(obj).find("input").addClass('action');//赋值当前单选框icon
    //var ress_info = $(obj).find("input").val();
    //$("#address_info").text(ress_info);//赋值上面的
    selectRessId(address_id);
    hide("quhuoinfo");
}
//自取信息选择
function select_selftake(address_id,obj){
    $(":radio[name='selftake']").removeAttr('checked');//找到所有单选框删除选择状态
    $(obj).find("input").attr("checked","checked");//赋值当前单选框状态
    $(":radio[name='selftake']").removeClass('action');//到所有单选框inco删除action状态
    $(obj).find("input").addClass('action');//赋值当前单选框icon
    var ress_info = $(obj).find("input").val();
    $("#selftake_info").text(ress_info);//赋值上面的
    hide('quhuoinfo_selftake');
}
//编辑自取信息
// function edit_selftake(id,e){
//     stopPropagation(e);
//     window.location = 'http://develop.01nnt.com/zerone/wechat/selftake_edit?selftake_id='+id;
// }
//配送选择
function selectexpress(obj,address){
    var $this = $(obj);
    $this.addClass("action").siblings().removeClass('action');
    //隐藏添加收货地址按钮
    if (address == 1) {//快递配送
        $("#ress_confirm").show();
        $("#peisong_confirm").hide();
    }else if(address == 2){//到店自提
        $("#ress_confirm").hide();
        $("#peisong_confirm").show();
    }
}
//备注
function remarks(){
    var $this = $("#remarks");
    $("#remarks_box").text($this.val());
    hide("alert");
}
function sbOrder(){
    $.showIndicator();
    var fansmanage_id = $("#fansmanage_id").val();//联盟主组织ID
    var _token = $("#_token").val();
    var store_id = $("#store_id").val();//店铺ID
    var zerone_user_id = $("#zerone_user_id").val();//userID
    var user_id = $("#shop_user_id").val();//用户店铺ID
    var goods_list = [];
    $(".order_list_each").each(function(){
        var $this = $(this);
        var goods_id = $this.attr("data-id");
        var goods_name = $this.attr("data-goods_name");
        var num = $this.attr("data-num");
        var goods_price = $this.attr("data-goods_price");
        var goods_thumb = $this.attr("data-goods_thumb");
        var stock = $this.attr("data-stock");
        goods_list.push({
            goods_id:goods_id,
            goods_name:goods_name,
            num:num,
            goods_price:goods_price,
            goods_thumb:goods_thumb,
            stock:stock
        })
    });
    var shipping_type = $("#shipping_type").val();
    var stock_type = 1;
    var remark = $("#remarks").val();
    var url = $("#order_submit").val();
    var data = {
        fansmanage_id:fansmanage_id,
        _token:_token,
        store_id:store_id,
        zerone_user_id:zerone_user_id,
        user_id:user_id,
        goods_list:goods_list,
        shipping_type:shipping_type,
        stock_type:stock_type,
        remark:remark,
    }
    var address_info =[];
    var self_take_info =[];
    if (shipping_type && shipping_type == 1) {
        //快递配送
        var $address_info_ch = $("#address_info_ch");
        var province_id = $address_info_ch.attr("data-province_id");
        var province_name = $address_info_ch.attr("data-province_name");
        var city_name = $address_info_ch.attr("data-city_name");
        var district_name = $address_info_ch.attr("data-district_name");
        var address = $address_info_ch.attr("data-address");
        var realname = $address_info_ch.attr("data-realname");
        var mobile = $address_info_ch.attr("data-mobile");
        address_info.push({
            province_id:province_id,
            province_name:province_name,
            city_name:city_name,
            district_name:district_name,
            address:address,
            realname:realname,
            mobile:mobile
        });
        data.address_info = address_info;
    }else if (shipping_type && shipping_type == 2) {
        //到店自提
        var $selftake_info_ch = $("#selftake_info_ch");
        var self_take_id = $selftake_info_ch.attr("data-self_take_id");
        var realname = $selftake_info_ch.attr("data-realname");
        var sex = $selftake_info_ch.attr("data-sex");
        var mobile = $selftake_info_ch.attr("data-mobile");
        self_take_info.push({
            self_take_id:self_take_id,
            realname:realname,
            sex:sex,
            mobile:mobile
        });
         data.self_take_info = self_take_info;
    }
    console.log(data);
    // $.post(
    //     url,
    //     data,
    //     function (json) {
    //         console.log(json,"提交");
    //         if (json.status == 1) {
    //         } else if (json.status == 0) {
    //            alert(json.msg);
    //         }
    //         $.hideIndicator();
    //     }
    // );
}
//获取url中的参数
function getUrlParam(name) {
 var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
 var r = window.location.search.substr(1).match(reg); //匹配目标参数
 if (r != null) return unescape(r[2]); return null; //返回参数值
}
//隐藏alert
$("#quhuoinfo").click(function(e){
    //stopPropagation(e);
    if(!$(e.target).is(".popup_alert_hook *") && !$(e.target).is(".popup_alert_hook")){
        $(".popup_alert_hook").removeClass('fadeInUp').addClass("fadeOutDown");
           setTimeout(function(){
              $(".popup_alert").css({display: 'none'});
         },250);
    }
});
//隐藏自取信息alert
$("#quhuoinfo_selftake").click(function(e){
    //stopPropagation(e);
    if(!$(e.target).is(".popup_alert_hook *") && !$(e.target).is(".popup_alert_hook")){
        $(".popup_alert_hook").removeClass('fadeInUp').addClass("fadeOutDown");
           setTimeout(function(){
              $(".popup_alert").css({display: 'none'});
         },250);
    }
});
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
function show(obj){
     $("#"+obj).css({display: 'flex'});
    $("#"+obj+" .popup_alert_hook").addClass('fadeInUp');
}
function hide(obj) {
    $("#"+obj).css({display: 'none'});
    $("#"+obj+" .popup_alert_hook").removeClass('fadeInUp').addClass("fadeOutDown");
}
