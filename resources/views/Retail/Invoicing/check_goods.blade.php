<!DOCTYPE html>
<html lang="en" class="app">
<head>
    <meta charset="utf-8"/>
    <title>零壹云管理平台 | 零售版店铺管理系统</title>
    <link rel="stylesheet" href="{{asset('public/Branch')}}/library/jPlayer/jplayer.flat.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Branch')}}/css/bootstrap.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Branch')}}/css/animate.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Branch')}}/css/font-awesome.min.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Branch')}}/css/simple-line-icons.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Branch')}}/css/font.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Branch')}}/css/app.css" type="text/css"/>
    <link href="{{asset('public/Branch')}}/library/sweetalert/sweetalert.css" rel="stylesheet"/>
    <!--[if lt IE 9]>
    <script src="{{asset('public/Branch')}}/library/ie/html5shiv.js"></script>
    <script src="{{asset('public/Branch')}}/library/ie/respond.min.js"></script>
    <script src="{{asset('public/Branch')}}/library/ie/excanvas.js"></script>
    <![endif]-->
</head>
<body class="">
<section class="vbox">
    {{--头部--}}
    @include('Retail/Public/Header')
    {{--头部--}}
    <section>
        <section class="hbox stretch">
            <!-- .aside -->
        @include('Retail/Public/Nav')
        <!-- /.aside -->
            <section id="content">
                <section class="vbox">
                    <section class="scrollable padder">
                        <div class="m-b-md">
                            <h3 class="m-b-none">进出开单</h3>
                        </div>

                        <section class="panel panel-default">
                            <header class="panel-heading">
                                退供应商货开单
                            </header>
                            <div class="row wrapper">
                                <form class="form-horizontal" method="get">
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-s-md btn" onclick="window.location='purchase_goods'">供应商到货开单</button>
                                        <button type="button" class="btn btn-s-md btn" onclick="window.location='return_goods'">&nbsp;&nbsp;退供应商货开单</button>
                                        <button type="button" class="btn btn-s-md btn" onclick="window.location='loss_goods'">&nbsp;&nbsp;报损开单</button>
                                        <button type="button" class="btn btn-s-md btn-success" onclick="window.location='check_goods'">&nbsp;&nbsp;盘点开单</button>
                                    </div>
                                </form>
                            </div>

                            <div class="line line-border b-b pull-in"></div>

                            <div class="col-sm-12">
                                <form class="form-horizontal" method="get">


                                    <label class="col-sm-1 control-label">供应商</label>

                                    <div class="col-sm-1">
                                        <input class="input-sm form-control" size="16" type="text" value=""
                                               placeholder="供应商id">
                                    </div>


                                    <div class="col-sm-2">
                                        <input class="input-sm form-control" size="16" type="text" value=""
                                               placeholder="公司名称或联系人姓名">
                                    </div>

                                    <div class="col-sm-2">
                                        <input class="input-sm form-control" size="16" type="text" value=""
                                               placeholder="联系人手机">
                                    </div>


                                    <div class="col-sm-1">

                                        <button type="button" class="btn btn-s-md btn-info"><i class="fa fa-search"></i>&nbsp;&nbsp;搜索
                                        </button>
                                    </div>
                                </form>
                            </div>


                            <div style="clear:both"></div>
                            <div class="line line-border b-b pull-in"></div>

                            <div class="col-sm-12">
                                <form class="form-horizontal" method="get">


                                    <label class="col-sm-1 control-label">商&nbsp; &nbsp; &nbsp;品</label>


                                    <div class="col-sm-2">
                                        <select name="account" class="form-control m-b">
                                            <option value="0">请选择一级分类</option>
                                            <option value="5970">美妆</option>
                                            <option value="5974">面膜</option>
                                            <option value="5977">跨境</option>
                                            <option value="5963">护肤</option>
                                            <option value="6360">香水</option>
                                            <option value="5990">个护</option>
                                            <option value="5981">内衣</option>
                                            <option value="5985">养生</option>
                                        </select>
                                    </div>


                                    <div class="col-sm-2">
                                        <input class="input-sm form-control" size="16" type="text" value=""
                                               placeholder="关键字或条码">
                                    </div>
                                    <div class="col-sm-1">

                                        <button type="button" class="btn btn-s-md btn-info"><i class="fa fa-search"></i>&nbsp;&nbsp;搜索
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div style="clear:both"></div>
                            <div class="line line-border b-b pull-in"></div>
                            <div class="tab-pane">

                                <div class="col-lg-7">
                                    <section class="panel panel-default">
                                        <header class="panel-heading font-bold">
                                            选择商品
                                        </header>

                                        <table class="table table-striped table-bordered ">
                                            <thead>
                                            <tr>
                                                <th>商品ID</th>
                                                <th>商品标题</th>
                                                <th>商品价格</th>


                                                <th>操作</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr id="1">
                                                <td class="id">1</td>
                                                <td class="name">性感女人香水</td>
                                                <td class="price">88.00</td>
                                                <td>
                                                    <button onclick="goodsSelect(1);" class="btn btn-info btn-xs" type="button">
                                                        <i class="fa fa-plus"></i>&nbsp;&nbsp;选择
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr id="2">
                                                <td class="id">2</td>
                                                <td class="name">性感女人香水</td>
                                                <td class="price">88.00</td>
                                                <td>
                                                    <button onclick="goodsSelect(2);" class="btn btn-info btn-xs" type="button">
                                                        <i class="fa fa-plus"></i>&nbsp;&nbsp;选择
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr id="3">
                                                <td class="id">3</td>
                                                <td class="name">性感女人香水</td>
                                                <td class="price">88.00</td>
                                                <td>
                                                    <button onclick="goodsSelect(3);" class="btn btn-info btn-xs" type="button">
                                                        <i class="fa fa-plus"></i>&nbsp;&nbsp;选择
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr id="4">
                                                <td class="id">4</td>
                                                <td class="name">性感女人香水</td>
                                                <td class="price">88.00</td>
                                                <td>
                                                    <button onclick="goodsSelect(4);" class="btn btn-info btn-xs" type="button">
                                                        <i class="fa fa-plus"></i>&nbsp;&nbsp;选择
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr id="5">
                                                <td class="id">5</td>
                                                <td class="name">性感女人香水</td>
                                                <td class="price">88.00</td>
                                                <td>
                                                    <button onclick="goodsSelect(5);" class="btn btn-info btn-xs" type="button">
                                                        <i class="fa fa-plus"></i>&nbsp;&nbsp;选择
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr id="6">
                                                <td class="id">6</td>
                                                <td class="name">性感女人香水</td>
                                                <td class="price">88.00</td>
                                                <td>
                                                    <button onclick="goodsSelect(6);" class="btn btn-info btn-xs" type="button">
                                                        <i class="fa fa-plus"></i>&nbsp;&nbsp;选择
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr id="7">
                                                <td class="id">7</td>
                                                <td class="name">性感女人香水</td>
                                                <td class="price">88.00</td>
                                                <td>
                                                    <button onclick="goodsSelect(7);" class="btn btn-info btn-xs" type="button">
                                                        <i class="fa fa-plus"></i>&nbsp;&nbsp;选择
                                                    </button>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <div style="clear: both;"></div>
                                    </section>
                                </div>

                                <div class="col-lg-5">
                                    <section class="panel panel-default">
                                        <header class="panel-heading font-bold">
                                            开单商品列表 操作人员：刘新文
                                        </header>
                                        <div class="panel-body">
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th><label class="label label-info">总计件数：</label></th>
                                                    <th><label class="label label-info">总计金额</label></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <label class="label label-danger" id="totalnumber">0</label>件
                                                    </td>
                                                    <td>
                                                        <label class="label label-danger" id="totalmoney">0</label>元
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <table class="table table-striped goods-table2">
                                                <thead>
                                                <tr>
                                                    <th>商品ID</th>
                                                    <th>商品标题</th>
                                                    <th>商品价格</th>


                                                    <th>操作</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                {{--<tr id="hs1_0">--}}
                                                    {{--<td>1</td>--}}
                                                    {{--<td>性感女人香水</td>--}}
                                                    {{--<td>--}}
                                                        {{--88.00--}}
                                                    {{--</td>--}}
                                                    {{--<td>--}}
                                                        {{--<button type="button" class="btn btn-danger btn-xs" onclick="goodsSub(1,0)"><i class="fa fa-minus"></i></button>--}}
                                                        {{--<input type="text" id="input1_0" onchange="update_num(1,0)" class="text-center" value="1000" size="4">--}}
                                                        {{--<button type="button" class="btn btn-success btn-xs" onclick="goodsAdd(1,0)"><i class="fa fa-plus"></i></button>--}}
                                                        {{--<button type="button" class="btn btn-danger btn-xs" onclick="goodsCancel(4461,0)">删除</button>--}}
                                                    {{--</td>--}}
                                                {{--</tr>--}}
                                                </tbody>
                                            </table>
                                        </div>
                                    </section>
                                </div>
                                <div style="clear: both;"></div>
                            </div>
                            <footer class="panel-footer">
                                <div class="row">

                                    <div class="col-sm-12 col-sm-offset-6">

                                        <button type="button" class="btn btn-success" id="addBtn">确认提交</button>
                                    </div>
                                </div>
                            </footer>
                        </section>
                    </section>
                </section>
            </section>
        </section>
    </section>
</section>


<script src="{{asset('public/Branch')}}/js/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="{{asset('public/Branch')}}/js/bootstrap.js"></script>
<!-- App -->
<script src="{{asset('public/Branch')}}/js/app.js"></script>
<script src="{{asset('public/Branch')}}/library/slimscroll/jquery.slimscroll.min.js"></script>
<script src="{{asset('public/Branch')}}/js/app.plugin.js"></script>
<script src="{{asset('public/Branch')}}/library/file-input/bootstrap-filestyle.min.js"></script>
<script type="text/javascript" src="{{asset('public/Branch')}}/library/jPlayer/jquery.jplayer.min.js"></script>
<script type="text/javascript" src="{{asset('public/Branch')}}/library/jPlayer/add-on/jplayer.playlist.min.js"></script>
<script type="text/javascript" src="{{asset('public/Branch')}}/library/jPlayer/demo.js"></script>
<script type="text/javascript" src="{{asset('public/Branch')}}/library/sweetalert/sweetalert.min.js"></script>
<script type="text/javascript">
    //编辑店铺信息
    function EditStore() {
        var formData = new FormData($("#store_edit")[0]);
        $.ajax({
            url: '{{ url('retail/ajax/store_edit_check') }}',
            type: 'post',
            data: formData,
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            success: function (json) {
                if (json.status == -1) {
                    window.location.reload();
                } else if (json.status == 1) {
                    swal({
                        title: "提示信息",
                        text: json.data,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "确定",
                    }, function () {
                        window.location.reload();
                    });
                } else {
                    swal({
                        title: "提示信息",
                        text: json.data,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "确定"
                    });
                }
            },
            error: function (json) {
                console.log(json);
            }
        });
    }
</script>

<script type="text/javascript">

    var ordersObj = {	//生成订单
        clerk: {},//店员
        supply: {},//顾客
        goods: []//商品
    };


    var selectedopnames=[];




    function canculate(){
        var totalnumber=0;
        var totalmoney=0;
        ordersObj.goods.map(function(a_goods, index) {
            totalnumber+=a_goods.number;
            totalmoney+=parseFloat(a_goods.number*a_goods.price);
        })
        totalmoney=totalmoney.toFixed(2);
        $("#totalnumber").html(totalnumber);
        $("#totalmoney").html(totalmoney);
    }

    //选择商品
    function goodsSelect(id) {

        var name = $('#' + id + ' .name').html();       //商品标题
        var price = $('#' + id + ' .price').html();     //商品价格

        var optionname = $('#' + id + ' .option option:selected').text();   //商品规格
        var optionid = $('#' + id + ' .option').val();                      //商品规格

        //如果没有规格，直接隐藏
        if(optionid ==undefined || optionid==null || optionid=='') {
            optionid =0;
            $('#'+id).hide();
        }else {
            optionid=parseInt(optionid);
            var thisoption = {
                optionid: optionid,
                optionname: optionname
            }
            selectedopnames.push(thisoption); //先存起来
            $('#dxop'+optionid).remove();//删除
            var selectid=id+'select';
            var objSelect=document.getElementById(selectid);
            if(objSelect.length==0){
                $('#'+id).hide();
            }
        }

        var hasGoods = false;
        ordersObj.goods.map(function(a_goods, index) { //订单中有该商品
            if(a_goods.id == id && a_goods.optionid == optionid) {
                hasGoods = true;
                ordersObj.goods[index].number += 1;
                var goodsNumber = ordersObj.goods[index].number;
                $('#hs'+ id+'_'+optionid + ' .goods-number-input').val(goodsNumber);
                return;  //跳出map
            }
        })
        if(hasGoods==false) { //订单中没有该商品
            price=parseFloat(price);
            var goods = {
                id: id,
                number: 1,
                optionid: optionid,
                price:price
            }
            ordersObj.goods.push(goods);
            $('.goods-table2 tbody').append('<tr id="hs'+id+'_'+optionid+'"><td>'+id+'</td><td class="search-goods-name">'+name+'</td><td>'+price+'</td><td class="search-goods-action"><button type="button" class="btn btn-danger btn-xs goods-number-sub" onclick="goodsSub('+id+','+optionid+')"><i class="fa fa-minus"></i></button><input id="input'+id+'_'+optionid+'" onchange="update_num('+id+','+optionid+')" type="text" class="text-center goods-number-input" value="" size="4"/><button type="button" class="btn btn-success btn-xs goods-number-add" onclick="goodsAdd('+id+','+optionid+')"><i class="fa fa-plus"></i></button><button type="button" class="btn btn-danger btn-xs" onclick="goodsCancel('+id+','+optionid+')">删除</button></td></tr>');
            $('#input'+id+'_'+optionid).val('1');
        }
        canculate();
    }

    //增加商品
    function goodsAdd(id,optionid) {

        ordersObj.goods.map(function(a_goods, index) { //订单中有该商品
            if(a_goods.id == id && a_goods.optionid == optionid) {
                ordersObj.goods[index].number =parseInt(ordersObj.goods[index].number) + 1;
                var goodsNumber = ordersObj.goods[index].number;
                $('#input'+id+'_'+optionid).val(goodsNumber);
                //$('#hs'+id+'_'+optionid + ' .goods-number-input').val(goodsNumber);
                return;  //跳出map
            }
        })
        canculate();
    }

    //手动输入修改商品
    function update_num(id,optionid){
        ordersObj.goods.map(function(a_goods, index) {
            if(a_goods.id == id && a_goods.optionid == optionid) {
                //var newnum=$('#hs'+id+'_'+optionid + ' .goods-number-input').val();
                var newnum=$('#input'+id+'_'+optionid).val();
                ordersObj.goods[index].number =parseInt(newnum);
                return; //跳出map
            }
        })
        canculate();
    }

    //减少商品
    function goodsSub(id,optionid) {
        ordersObj.goods.map(function(a_goods, index) {
            if(a_goods.id == id && a_goods.optionid == optionid) {
                if(a_goods.number == 1) {
                    return;
                }else {
                    ordersObj.goods[index].number =parseInt(ordersObj.goods[index].number) - 1;
                    $('#input'+id+'_'+optionid).val(ordersObj.goods[index].number);
                    return; //跳出map
                }
            }
        })
        canculate();
    }

    //移除商品
    function goodsCancel(id,optionid) {
        $('#hs'+id+'_'+optionid).remove();
        $('#'+id).show();

        if(optionid>0){
            var dxoption='dxop'+optionid;
            var objOption=document.getElementById(dxoption);
            if(!objOption){
                var selectid=id+'select';
                var objSelect=document.getElementById(selectid);

                var op=document.createElement("option");      // 新建OPTION (op)
                op.id = 'dxop'+optionid;
                op.value = optionid;

                for(i=0;i<selectedopnames.length;i++){
                    if(selectedopnames[i].optionid==optionid){
                        var op_names = selectedopnames[i].optionname;
                        break;
                    }
                }
                op.innerHTML = op_names;
                objSelect.appendChild(op);
            }
        }

        ordersObj.goods.map(function(a_goods, index) {
            if(a_goods.id == id && a_goods.optionid == optionid) {
                ordersObj.goods.splice(index, 1);
                return;
            }
        });
        canculate();
    }

</script>

</body>
</html>