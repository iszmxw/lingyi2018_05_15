$(function () {
    var fansmanage_id = $("#fansmanage_id").val();//联盟主组织ID
    var _token = $("#_token").val();
    var store_id = $("#store_id").val();//店铺ID
    //获取goods分类列表
    var class_url = "http://develop.01nnt.com/api/wechatApi/category";
    $.post(
        class_url,
        {'fansmanage_id': fansmanage_id, '_token': _token, 'store_id': store_id},
        function (json) {
            if (json.status == 1) {
                var str = "<li class='action category0' data-id='" + 0 + "' onclick='category_list(0);'><a href='javascript:;'>全部</a></li>";
                for (var i = json.data.categorylist.length - 1; i >= 0; i--) {
                    str += "<li class='category" + json.data.categorylist[i].id + "'" +
                        "data-id='" + json.data.categorylist[i].id + "'" +
                        " onclick='category_list(" + json.data.categorylist[i].id + ");'>" +
                        "<a href='javascript:;' external>" + json.data.categorylist[i].name + "</a></li>";
                }
                //赋值分类列表
                var $goods_cs_lt = $("#goods_cs_lt");
                $goods_cs_lt.empty();
                $goods_cs_lt.append(str);
                //赋值弹出框的分类列表
                var $goods_cs_lt_alert = $("#goods_cs_lt_alert");
                $goods_cs_lt_alert.empty();
                $goods_cs_lt_alert.append(str);
            } else if (json.status == 0) {
                console.log(json.msg);
            }
        }
    );

    //查询商品列表和购物车列表(category(默认为0 全部,keyword_val搜索值默认为空))
    // selectgoods(1,"");
    category_list(0);
});


//var $limit = 0, $category = 1, $keyword_val = '';

var dropload = $('.goodslist').dropload({
    scrollArea: window,
    autoLoad: false,
    // 下拉刷新模块显示内容
    // domUp: {
    //     domClass: 'dropload-up',
    //     // 下拉过程显示内容
    //     domRefresh: '<div class="dropload-refresh">↓下拉刷新</div>',
    //     // 下拉到一定程度显示提示内容
    //     domUpdate: '<div class="dropload-update">↑释放更新</div>',
    //     // 释放后显示内容
    //     domLoad: '<div class="dropload-load"><span class="loading"></span>加载中...</div>'
    // },
    domDown: {
        domClass: 'dropload-down',
        // 滑动到底部显示内容
        domRefresh: '<div class="dropload-refresh">↑上拉加载更多</div>',
        // 内容加载过程中显示内容
        domLoad: '<div class="dropload-load"><span class="loading"></span>加载中...</div>',
        // 没有更多内容-显示提示
        domNoData: '<div class="dropload-noData">没有更多商品了喔</div>'
    },
    loadDownFn: function (me) {
        console.log("1321");
        var category_id = "";
        $("#goods_cs_lt_alert li").each(function (index, el) {
            if ($(this).hasClass('action')) {
                category_id = $(this).data('id');
            }
        });
        var keyword_val = $("#search").val();
        var limit = $("#limit").val();//分页
        selectgoods(category_id, keyword_val, limit);
        $("#limit").val(parseInt(limit) + 1 );
    },
    // loadUpFn: function (me) {
    //     $limit++;
    //     selectgoods($category, $keyword_val, $limit, me);
    // },
    threshold: 50
});
//查询商品列表和购物车列表
function selectgoods(category, keyword_val, limit) {
    //获取购物车商品
    var fansmanage_id = $("#fansmanage_id").val();//联盟主组织ID
    var _token = $("#_token").val();
    var store_id = $("#store_id").val();//店铺ID
    var total_price = 0;//购物车总价格
    var cart_list_url = "http://develop.01nnt.com/api/wechatApi/shopping_cart_list";
    var shop_user_id = $("#shop_user_id").val();//用户店铺ID
    var zerone_user_id = $("#zerone_user_id").val();//用户零壹ID
    var limit = $("#limit").val();//分页
    var category_id = category;//分类ID
    var keyword = keyword_val;
    $.post(
        cart_list_url,
        {
            'fansmanage_id': fansmanage_id,
            '_token': _token,
            'store_id': store_id,
            'user_id': shop_user_id,
            'zerone_user_id': zerone_user_id
        },
        function (json) {
            if (json.status == 1) {
                var str = "";
                var cart_num = [];
                for (var i = 0; i < json.data.goods_list.length; i++) {
                    str += cart_list_box(json.data.goods_list[i].goods_name, json.data.goods_list[i].goods_price,
                        json.data.goods_list[i].num, json.data.goods_list[i].goods_id, json.data.goods_list[i].stock,
                        json.data.goods_list[i].goods_thumb);
                    //计算购物车总价格
                    total_price += parseFloat(json.data.goods_list[i].goods_price) * parseInt(json.data.goods_list[i].num);
                    //记录购物车列表数量,渲染商品列表赋值商品列表存在购物车的数量
                    cart_num[json.data.goods_list[i].goods_id] = json.data.goods_list[i].num;
                }
                //购物车总价格
                var _this = $("#cart_price");
                _this.attr('data-totalprice', total_price);//记录总价格的值
                _this.html("金额总计<em>&yen;" + total_price + "</em>");
                //购物车总数
                var total = json.data.total;
                var _this1 = $("#goods_totalnum");
                _this1.attr('data-totalnum', total);
                _this1.text(total);
                //购物车弹出状态的total(两个)
                var _this2 = $("#total");
                _this2.attr('data-totalnum', total);
                _this2.text(total);
                //购物车列表渲染
                var $cart_list = $("#cart_list");
                $cart_list.empty();
                $cart_list.append(str);
            } else if (json.status == 0) {
                console.log(json.msg);
            }
            //获取商品列表
            var goodslist_url = "http://develop.01nnt.com/api/wechatApi/goods_list";
            $.post(
                goodslist_url,
                {
                    'fansmanage_id': fansmanage_id, '_token': _token, 'store_id': store_id,
                    'category_id': category_id, 'keyword': keyword, 'limit': limit
                },
                function (json) {
                    var str = "";
                    if (json.status == 1) {

                        for (var i = 0; i < json.data.goodslist.length; i++) {
                            // console.log(cart_num);
                            // console.log(cart_num[json.data.goodslist[i].id]);
                            //判断列表与购物车的id存在就读取购物车的数量
                            if (cart_num && cart_num[json.data.goodslist[i].id]) {
                                json.data.goodslist[i].number = cart_num[json.data.goodslist[i].id];
                            }
                            str += goods_list_box(json.data.goodslist[i].name, json.data.goodslist[i].details,
                                json.data.goodslist[i].stock, json.data.goodslist[i].price, json.data.goodslist[i].thumb[0].thumb,
                                json.data.goodslist[i].number, json.data.goodslist[i].id);
                        }


                        var $goodslist = $("#goodslist");
                        //$goodslist.empty();
                        $goodslist.append(str);


                    } else if (json.status == 0) {
                        // 没有数据
                        console.log(json.msg);
                        // 锁定
                        //me.lock();
                        // 无数据
                        dropload.noData();
                    }
                    // 每次数据插入，必须重置
                    dropload.resetload();
                }
            ).error(function () {
                dropload.resetload();
            })

        }
    );
}

//添加商品购物车
function cart_add(obj) {
    $.showIndicator();
    var url = "http://develop.01nnt.com/api/wechatApi/shopping_cart_add";
    var $this = $(obj);
    var goods_id = $this.data("goodsid");
    var goods_name = $this.data("goodsname");
    var stock = $this.data("goodsstock");
    var goods_thumb = $this.data("goodsthumb");
    var goods_price = $this.data("goodsprice");
    var fansmanage_id = $("#fansmanage_id").val();//联盟主组织ID
    var _token = $("#_token").val();
    var store_id = $("#store_id").val();//店铺ID
    var zerone_user_id = $("#zerone_user_id").val();//用户零壹ID
    var user_id = $("#shop_user_id").val();//用户店铺ID
    var data = {
        fansmanage_id: fansmanage_id,
        zerone_user_id: zerone_user_id,
        user_id: user_id,
        store_id: store_id,
        goods_id: goods_id,
        goods_price: goods_price,
        goods_name: goods_name,
        goods_thumb: goods_thumb,
        num: 1,
        stock: stock,
        _token: _token
    };
    $.post(
        url,
        data,
        function (json) {
            console.log(json);
            if (json.status == 1) {
                //删除点击加号按钮的当前状态
                $(".cart_border").removeClass('action');
                //添加点击加号按钮的当前状态
                $this.parent().addClass('action');
                //等于数量1的情况下显示数量和减号按钮
                if (json.data.num == 1) {
                    $this.parent().addClass('cart_border');
                    $this.parent().children('a').removeClass('gs_hide').addClass('gs_show');
                }
                //设置点击数量
                $(".goods_id" + json.data.goods_id).text(json.data.num);
                //购物车总价格
                totalprice(json.data.goods_price, true);
                //购物车总数
                totalnum(1, true);
                $.hideIndicator();
            } else if (json.status == 0) {
                console.log(json.msg);
            }
        }
    );
}

//减少商品购物车
function cart_reduce(obj, status) {
    //status 判断事件是在购物车里面执行
    $.showIndicator();
    var url = "http://develop.01nnt.com/api/wechatApi/shopping_cart_reduce";
    var $this = $(obj);
    var goods_id = $this.data("goodsid");
    var goods_name = $this.data("goodsname");
    var stock = $this.data("goodsstock");
    var goods_thumb = $this.data("goodsthumb");
    var goods_price = $this.data("goodsprice");
    var fansmanage_id = $("#fansmanage_id").val();//联盟主组织ID
    var _token = $("#_token").val();
    var store_id = $("#store_id").val();//店铺ID
    var zerone_user_id = $("#zerone_user_id").val();//用户零壹ID
    var user_id = $("#shop_user_id").val();//用户店铺ID
    var data = {
        fansmanage_id: fansmanage_id,
        zerone_user_id: zerone_user_id,
        user_id: user_id,
        store_id: store_id,
        goods_id: goods_id,
        goods_price: goods_price,
        goods_name: goods_name,
        goods_thumb: goods_thumb,
        num: 1,
        stock: stock,
        _token: _token
    };
    $.post(
        url,
        data,
        function (json) {
            if (json.status == 1) {
                //数量小于的情况下显示数量和减号按钮
                if (json.data.num == 0) {
                    //数量为0隐藏减号和数量按钮
                    $this.removeClass('gs_show').addClass('gs_hide');
                    $this.next().removeClass('gs_show').addClass('gs_hide');
                    //在购物车点击减号按钮的情况下隐藏商品列表减号和数量按钮
                    $(".goods_id" + json.data.goods_id).removeClass('gs_show').addClass('gs_hide');
                    $(".goods_id" + json.data.goods_id).prev().removeClass('gs_show').addClass('gs_hide');
                    $(".goods_id" + json.data.goods_id).parent().removeClass('cart_border').addClass('action');
                }
                //购物车减到0的时候remove li
                if (json.data.num == 0 && status) {
                    $this.closest('li').remove();
                }
                //设置点击数量
                $(".goods_id" + json.data.goods_id).text(json.data.num);
                //购物车总价格
                totalprice(json.data.goods_price, false);
                //购物车总数
                totalnum(1, false);
                $.hideIndicator();
            } else if (json.status == 0) {
                console.log(json.msg);
            }
        }
    );
}

//获取分类查询
function category_list(category_id) {
    var keyword_val = $("#search").val();
    var limit = $("#limit").val();//分页
    $("#limit").val("1");//选择分类，分页重置
    limit = 1;
    var $goodslist = $("#goodslist");
    $goodslist.empty();
    selectgoods(category_id, keyword_val,limit);
    $(".category" + category_id).siblings().removeClass('action');
    $(".category" + category_id).addClass('action');
    hidegoodsclass('goodsclass');
}

//商品搜索
function search_click() {
    var category_id = "";
    $("#goods_cs_lt_alert li").each(function (index, el) {
        if ($(this).hasClass('action')) {
            category_id = $(this).data('id');
        }
    });
    var keyword_val = $("#search").val();
    var limit = $("#limit").val();//分页
    $("#limit").val("1");//选择分类，分页重置
    limit = 1;
    var $goodslist = $("#goodslist");
    $goodslist.empty();
    //selectgoods(category_id, keyword_val,limit);
}

//清空购物车
function cart_empty() {
    $.showIndicator();
    var url = "http://develop.01nnt.com/api/wechatApi/shopping_cart_empty";
    var fansmanage_id = $("#fansmanage_id").val();//联盟主组织ID
    var _token = $("#_token").val();
    var store_id = $("#store_id").val();//店铺ID
    var zerone_user_id = $("#zerone_user_id").val();//用户零壹ID
    var user_id = $("#shop_user_id").val();//用户店铺ID
    var data = {
        fansmanage_id: fansmanage_id,
        zerone_user_id: zerone_user_id,
        user_id: user_id,
        store_id: store_id,
        _token: _token
    };
    $.post(
        url,
        data,
        function (json) {
            if (json.status == 1) {
                //隐藏购物车的减号按钮
                $(".delect_cart_btn").each(function (index, el) {
                    var $this = $(this);
                    $this.removeClass("gs_show").addClass('gs_hide');
                    if ($this.parent().hasClass('cart_border')) {
                        $this.parent().removeClass('cart_border').addClass('action');
                    }
                });
                //清空商品列表的数量
                $(".delect_cart_inpt").each(function (index, el) {
                    var $this = $(this);
                    $this.removeClass("gs_show").addClass('gs_hide');
                    $this.text("0");
                    if ($this.parent().hasClass('cart_border')) {
                        $this.parent().removeClass('cart_border').addClass('action');
                    }
                });
                //清空购物车总数
                $("#goods_totalnum").text("0");
                $("#goods_totalnum").attr('data-totalnum', '0');
                //清空价格
                var _this = $("#cart_price");
                _this.attr('data-totalprice', "0");
                _this.html("您还未选购商品哦~");
                //隐藏提示框
                hide('alert');
                //隐藏购物车
                $("#cart").click();
                $.hideIndicator();
                $.toast("清空成功");
            } else if (json.status == 0) {
                console.log(json.msg);
            }
        }
    );
}

//购物车总价格
function totalprice(price, status) {
    //del判断是减少商品还是添加,true为添加
    var $this = $("#cart_price");
    var old_price = $this.data("totalprice");
    var total = (status == true) ? parseFloat(price) + parseFloat(old_price) : parseFloat(old_price) - parseFloat(price);
    //记录总价格的值
    $this.attr('data-totalprice', total);
    $this.html("金额总计<em>&yen;" + total + "</em>");
}

//购物车总数
function totalnum(count, status) {
    //del判断是减少商品还是添加,true为添加
    var $this = $("#goods_totalnum");
    var old_num = $this.data("totalnum");
    var total = (status == true) ? parseInt(count) + parseInt(old_num) : parseInt(old_num) - parseInt(count);
    //记录总价格的值
    $this.attr('data-totalnum', total);
    $this.text(total);
    //购物车弹出状态的total(两个)
    $("#total").attr('data-totalnum', total);
    $("#total").text(total);
}

//购物车列表
function cart_list_box(name, price, num, goods_id, stock, thumb) {
    str = '<li>' +
        '<span>' + name + '</span>' +
        '<span>&yen;' + price + '</span>' +
        '<div class="cart_alert_btn">' +
        '<div class="goods_btn cart_border">' +
        '<a href="javascript:;" class="cart_box delect_cart_btn"' +
        'data-goodsid="' + goods_id + '"' +
        'data-goodsname="' + name + '"' +
        'data-goodsstock="' + stock + '"' +
        'data-goodsthumb="http://develop.01nnt.com/' + thumb + '"' +
        'data-goodsprice="' + price + '" onclick="cart_reduce(this,true)">-</a>' +
        '<a href="javascript:;" class="cart_box delect_cart_inpt goods_id' + goods_id + '">' + num + '</a>' +
        '<a href="javascript:;" class="cart_box add_cart_btn"' +
        'data-goodsid="' + goods_id + '"' +
        'data-goodsname="' + name + '"' +
        'data-goodsstock="' + stock + '"' +
        'data-goodsthumb="http://develop.01nnt.com/' + thumb + '"' +
        'data-goodsprice="' + price + '" onclick="cart_add(this)">+</a>' +
        '</div>' +
        '</div>' +
        '</li>';
    return str;
}
//跳转提交订单
function online_order(){
    var url = "http://develop.01nnt.com/zerone/wechatRetail/online_order";
    var totalnum = $("#goods_totalnum").text();
    if(totalnum > 0){
        window.location.href = url;
    }else{
        $.toast("您还没选购商品喔~~");
    }
}
//商品列表
function goods_list_box(name, details, stock, price, thumb, number, goods_id) {
    var str = "";
    str += '<div class="gl_item">' +
        '<div class="gl_item_fl">' +
        '<div class="goods_img">' +
        '<img src="http://develop.01nnt.com/' + thumb + '">' +
        '</div>' +
        '<div class="goods_right">' +
        '<div class="goods_right_item">' +
        '<section class="gri_center">' +
        '<h3 class="goods_tltie"><span>' + name + '</span></h3>' +
        '</section>' +
        '<section class="gri_center">' +
        '<p class="goods_details">' + details + '</p>' +
        '</section>' +
        '<section class="gri_center">' +
        '<p class="goods_distance">' +
        '<span>库存：</span>' +
        '<span>' + stock + '</span>' +
        '</p>' +
        '</section>' +
        '<section class="gri_center gri_center_juzhong">' +
        '<div class="goods_price">' +
        '<span>&yen;' + price + '</span>' +
        '</div>';
    //购物车存在商品数量显示数量和减号
    if (number > 0) {
        str += '<div class="goods_btn cart_border">' +
            '<a href="javascript:;" class="cart_box delect_cart_btn gs_show"' +
            'data-goodsid="' + goods_id + '"' +
            'data-goodsname="' + name + '"' +
            'data-goodsstock="' + stock + '"' +
            'data-goodsthumb="http://develop.01nnt.com/' + thumb + '"' +
            'data-goodsprice="' + price + '" onclick="cart_reduce(this)">-</a>' +
            '<a href="javascript:;" class="cart_box delect_cart_inpt gs_show goods_id' + goods_id + '">' + number + '</a>';
    } else {
        str += '<div class="goods_btn action">' +
            '<a href="javascript:;" class="cart_box delect_cart_btn gs_hide"' +
            'data-goodsid="' + goods_id + '"' +
            'data-goodsname="' + name + '"' +
            'data-goodsstock="' + stock + '"' +
            'data-goodsthumb="http://develop.01nnt.com/' + thumb + '"' +
            'data-goodsprice="' + price + '" onclick="cart_reduce(this)">-</a>' +
            '<a href="javascript:;" class="cart_box delect_cart_inpt gs_hide goods_id' + goods_id + '"">' + number + '</a>';
    }


    str += '<a href="javascript:;" class="cart_box add_cart_btn"' +
        'data-goodsid="' + goods_id + '"' +
        'data-goodsname="' + name + '"' +
        'data-goodsstock="' + stock + '"' +
        'data-goodsthumb="http://develop.01nnt.com/' + thumb + '"' +
        'data-goodsprice="' + price + '" onclick="cart_add(this)">+</a>' +
        '</div>' +
        '</section>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>'
    return str;
}

//隐藏alert
// $("#alert").click(function(e){
//     stopPropagation(e);
//     if(!$(e.target).is(".popup_alert_hook *") && !$(e.target).is(".popup_alert_hook")){
//         $(".popup_alert_hook").removeClass('fadeInUp').addClass("fadeOutDown");
//            setTimeout(function(){
//               $(".popup_alert").css({display: 'none'});
//          },250);
//     }
// });
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

function show(obj) {
    $("#" + obj).css({display: 'flex'});
    $("#" + obj + " .popup_alert_hook").addClass('fadeInUp');
}

function hide(obj) {
    $("#" + obj).css({display: 'none'});
    $("#" + obj + " .popup_alert_hook").removeClass('fadeInUp').addClass("fadeOutDown");
}

function showcart(obj, em) {
    var goods_totalnum = $("#goods_totalnum").text();
    if (goods_totalnum <= 0) {
        $.toast("请先选购商品");
        return;
    }
    $.showIndicator();
    //获取购物车商品
    var total_price = 0;//购物车总价格
    var fansmanage_id = $("#fansmanage_id").val();//联盟主组织ID
    var _token = $("#_token").val();
    var store_id = $("#store_id").val();//店铺ID
    var cart_list_url = "http://develop.01nnt.com/api/wechatApi/shopping_cart_list";
    var shop_user_id = $("#shop_user_id").val();//用户店铺ID
    var zerone_user_id = $("#zerone_user_id").val();//用户零壹ID
    $.post(
        cart_list_url,
        {
            'fansmanage_id': fansmanage_id,
            '_token': _token,
            'store_id': store_id,
            'user_id': shop_user_id,
            'zerone_user_id': zerone_user_id
        },
        function (json) {
            $.hideIndicator();
            if (json.status == 1) {
                var str = "";
                for (var i = 0; i < json.data.goods_list.length; i++) {
                    str += cart_list_box(json.data.goods_list[i].goods_name, json.data.goods_list[i].goods_price,
                        json.data.goods_list[i].num, json.data.goods_list[i].goods_id, json.data.goods_list[i].stock,
                        json.data.goods_list[i].goods_thumb);
                    //计算购物车总价格
                    total_price += parseFloat(json.data.goods_list[i].goods_price) * parseInt(json.data.goods_list[i].num);
                }
                //购物车总价格
                //记录总价格的值
                var _this = $("#cart_price");
                _this.attr('data-totalprice', total_price);
                _this.html("金额总计<em>&yen;" + total_price + "</em>");
                //购物车总数
                var total = json.data.total;
                var _this1 = $("#goods_totalnum");
                _this1.attr('data-totalnum', total);
                _this1.text(total);
                //购物车弹出状态的total(两个)
                var _this2 = $("#total");
                _this2.attr('data-totalnum', total);
                _this2.text(total);
                //购物车列表渲染
                var $cart_list = $("#cart_list");
                $cart_list.empty();
                $cart_list.append(str);
                $(em).hide();//隐藏掉在下边的购物车按钮
                $("#" + obj).css({display: 'flex'});
                $("#" + obj + " .popup_alert_hook").addClass('fadeInUp');
            } else if (json.status == 0) {
                console.log(json.msg);
            }
        }
    );
}

function goodsclass(obj) {
    $("#" + obj).css({display: 'flex'});
    $("#" + obj + " .popup_alert_hook").removeClass('fadeOutUp').addClass('fadeInDown');
}

function hidegoodsclass(obj) {
    $("#" + obj + " .popup_alert_hook").removeClass('fadeInDown').addClass('fadeOutUp');
    setTimeout(function () {
        $("#" + obj).css({display: 'none'});
    }, 250);
}

function hidecart(obj, em) {
    $(em).hidden();
    $("#" + obj).css({display: 'none'});
    $("#" + obj + " .popup_alert_hook").removeClass('fadeInUp').addClass("fadeOutDown");
}

$("#cart").click(function (e) {
    //stopPropagation(e);
    if (!$(e.target).is(".popup_alert_hook *") && !$(e.target).is(".popup_alert_hook")) {
        $(".popup_alert_hook").removeClass('fadeInUp').addClass("fadeOutDown");
        setTimeout(function () {
            $(".popup_alert").css({display: 'none'});
        }, 250);
        $("#cart_btn_b").show();
    }
})
