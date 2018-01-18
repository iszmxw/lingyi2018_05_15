<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>零壹新科技程序管理平台</title>
    <link href="{{asset('public/Zerone/library/bootstrap')}}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('public/Zerone/library/font')}}/css/font-awesome.css" rel="stylesheet">
    <link href="{{asset('public/Zerone')}}/css/animate.css" rel="stylesheet">
    <link href="{{asset('public/Zerone')}}/css/style.css" rel="stylesheet">

</head>

<body class="">

<div id="wrapper">

    @include('Zerone/Public/Nav')

    <div id="page-wrapper" class="gray-bg">
        @include('Zerone/Public/Header')
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-sm-4">
                <h2>“刘记鸡煲王（总店）”人员结构</h2>
                <ol class="breadcrumb">
                    <li class="active">
                        <a href="JavaScript:;">店铺管理</a>
                    </li>
                    <li >
                        <strong>“刘记鸡煲王（总店）”人员结构</strong>
                    </li>
                </ol>
            </div>

        </div>

        <div class="wrapper wrapper-content animated fadeInRight ecommerce">


            <div class="ibox-content m-b-sm border-bottom">

                <div class="row">
                    <div class="col-sm-1">
                        <div class="form-group">
                            <label class="control-label" for="amount"> &nbsp;</label>
                            <button type="button" onclick="location.href='storelist.html'" class="block btn btn-info"><i class="fa fa-reply"></i>&nbsp;&nbsp;返回列表</button>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group">
                            <label class="control-label" for="amount"> &nbsp;</label>
                            <button type="button" id="expand-all" class="block btn btn-primary"><i class="fa fa-plus"></i>&nbsp;&nbsp;展开所有</button>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group">
                            <label class="control-label" for="amount"> &nbsp;</label>
                            <button type="button" id="collapse-all" class="block btn btn-primary"><i class="fa fa-minus"></i>&nbsp;&nbsp;合并所有</button>
                        </div>
                    </div>


                </div>

            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>“刘记鸡煲王（总店）”人员架构</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="dd" id="nestable2">
                                <ol class="dd-list">
                                    <li class="dd-item" data-id="1">
                                        <div class="dd-handle">
                                            <span class="label label-primary"><i class="fa fa-shopping-cart"></i></span> 刘记鸡煲王（总店）
                                        </div>
                                        <ol class="dd-list">
                                            <li class="dd-item" data-id="2">
                                                <div class="dd-handle">
                                                    <span class="pull-right"> 添加时间 2017-12-08 12:00:00</span>
                                                    <span class="label label-info"><i class="fa fa-user"></i></span> 张三-店铺负责人[zos_13123456789，店长，13123456789 ]
                                                </div>
                                                <ol class="dd-list">
                                                    <li class="dd-item" data-id="3">
                                                        <div class="dd-handle">
                                                            <span class="pull-right"> 添加时间 2017-12-08 12:00:00 </span>
                                                            <span class="label label-info"><i class="fa fa-user"></i></span> 王五-店长[zos_13123456789，店长，13123456789 ]
                                                        </div>
                                                        <ol class="dd-list">
                                                            <li class="dd-item" data-id="4">
                                                                <div class="dd-handle">
                                                                    <span class="pull-right"> 添加时间 2017-12-08 12:00:00 </span>
                                                                    <span class="label label-info"><i class="fa fa-user"></i></span> 王五-店员[zos_13123456789，店长，13123456789 ]
                                                                </div>
                                                            </li>
                                                            <li class="dd-item" data-id="4">
                                                                <div class="dd-handle">
                                                                    <span class="pull-right"> 添加时间 2017-12-08 12:00:00 </span>
                                                                    <span class="label label-info"><i class="fa fa-user"></i></span> 王五-店员[zos_13123456789，店长，13123456789 ]
                                                                </div>
                                                            </li>
                                                            <li class="dd-item" data-id="4">
                                                                <div class="dd-handle">
                                                                    <span class="pull-right"> 添加时间 2017-12-08 12:00:00 </span>
                                                                    <span class="label label-info"><i class="fa fa-user"></i></span> 王五-店员[zos_13123456789，店长，13123456789 ]
                                                                </div>
                                                            </li>
                                                        </ol>
                                                    </li>

                                                </ol>
                                            </li>

                                        </ol>
                                    </li>
                                    <li class="dd-item" data-id="1">
                                        <div class="dd-handle">
                                            <span class="label label-primary"><i class="fa fa-shopping-cart"></i></span> 刘记鸡煲王（龙岗店）
                                        </div>
                                        <ol class="dd-list">
                                            <li class="dd-item" data-id="2">
                                                <div class="dd-handle">
                                                    <span class="pull-right"> 添加时间 2017-12-08 12:00:00</span>
                                                    <span class="label label-info"><i class="fa fa-user"></i></span> 张三-店铺负责人[zos_13123456789，店长，13123456789 ]
                                                </div>
                                                <ol class="dd-list">
                                                    <li class="dd-item" data-id="3">
                                                        <div class="dd-handle">
                                                            <span class="pull-right"> 添加时间 2017-12-08 12:00:00 </span>
                                                            <span class="label label-info"><i class="fa fa-user"></i></span> 王五-店长[zos_13123456789，店长，13123456789 ]
                                                        </div>
                                                        <ol class="dd-list">
                                                            <li class="dd-item" data-id="4">
                                                                <div class="dd-handle">
                                                                    <span class="pull-right"> 添加时间 2017-12-08 12:00:00 </span>
                                                                    <span class="label label-info"><i class="fa fa-user"></i></span> 王五-店员[zos_13123456789，店长，13123456789 ]
                                                                </div>
                                                            </li>
                                                            <li class="dd-item" data-id="4">
                                                                <div class="dd-handle">
                                                                    <span class="pull-right"> 添加时间 2017-12-08 12:00:00 </span>
                                                                    <span class="label label-info"><i class="fa fa-user"></i></span> 王五-店员[zos_13123456789，店长，13123456789 ]
                                                                </div>
                                                            </li>
                                                            <li class="dd-item" data-id="4">
                                                                <div class="dd-handle">
                                                                    <span class="pull-right"> 添加时间 2017-12-08 12:00:00 </span>
                                                                    <span class="label label-info"><i class="fa fa-user"></i></span> 王五-店员[zos_13123456789，店长，13123456789 ]
                                                                </div>
                                                            </li>
                                                        </ol>
                                                    </li>

                                                </ol>
                                            </li>

                                        </ol>
                                    </li>
                                    <li class="dd-item" data-id="1">
                                        <div class="dd-handle">
                                            <span class="label label-primary"><i class="fa fa-shopping-cart"></i></span> 刘记鸡煲王（宝能店）
                                        </div>
                                        <ol class="dd-list">
                                            <li class="dd-item" data-id="2">
                                                <div class="dd-handle">
                                                    <span class="pull-right"> 添加时间 2017-12-08 12:00:00</span>
                                                    <span class="label label-info"><i class="fa fa-user"></i></span> 张三-店铺负责人[zos_13123456789，店长，13123456789 ]
                                                </div>
                                                <ol class="dd-list">
                                                    <li class="dd-item" data-id="3">
                                                        <div class="dd-handle">
                                                            <span class="pull-right"> 添加时间 2017-12-08 12:00:00 </span>
                                                            <span class="label label-info"><i class="fa fa-user"></i></span> 王五-店长[zos_13123456789，店长，13123456789 ]
                                                        </div>
                                                        <ol class="dd-list">
                                                            <li class="dd-item" data-id="4">
                                                                <div class="dd-handle">
                                                                    <span class="pull-right"> 添加时间 2017-12-08 12:00:00 </span>
                                                                    <span class="label label-info"><i class="fa fa-user"></i></span> 王五-店员[zos_13123456789，店长，13123456789 ]
                                                                </div>
                                                            </li>
                                                            <li class="dd-item" data-id="4">
                                                                <div class="dd-handle">
                                                                    <span class="pull-right"> 添加时间 2017-12-08 12:00:00 </span>
                                                                    <span class="label label-info"><i class="fa fa-user"></i></span> 王五-店员[zos_13123456789，店长，13123456789 ]
                                                                </div>
                                                            </li>
                                                            <li class="dd-item" data-id="4">
                                                                <div class="dd-handle">
                                                                    <span class="pull-right"> 添加时间 2017-12-08 12:00:00 </span>
                                                                    <span class="label label-info"><i class="fa fa-user"></i></span> 王五-店员[zos_13123456789，店长，13123456789 ]
                                                                </div>
                                                            </li>
                                                        </ol>
                                                    </li>

                                                </ol>
                                            </li>

                                        </ol>
                                    </li>
                                </ol>
                            </div>


                        </div>

                    </div>
                </div>
            </div>
        </div>
        @include('Zerone/Public/Footer')
    </div>
</div>
{{--<!-- Page-Level Scripts -->--}}
<script src="{{asset('public/Zerone/library/jquery')}}/js/jquery-2.1.1.js"></script>
<script src="{{asset('public/Zerone/library/bootstrap')}}/js/bootstrap.min.js"></script>
<script src="{{asset('public/Zerone/library/metisMenu')}}/js/jquery.metisMenu.js"></script>
<script src="{{asset('public/Zerone/library/slimscroll')}}/js/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="{{asset('public/Zerone')}}/js/inspinia.js"></script>
<script src="{{asset('public/Zerone/library/pace')}}/js/pace.min.js"></script>

<script>
    $(document).ready(function() {
        $('.chosen-select').chosen({width:"100%",no_results_text:'对不起，没有找到结果！关键词：'});
        var elem = document.querySelector('.js-switch');
        var switchery = new Switchery(elem, { color: '#1AB394' });
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
        $('.footable').footable();

        $('#date_added').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true
        });

        $('#date_modified').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true
        });
        $("#editBtn").click(function(){
            $('#myModal').modal();
        });
        $('#lockBtn').click(function(){
            $('#myModal3').modal();
        });
        $('#removeBtn').click(function(){
            $('#myModal3').modal();
        });
        $('.saveBtn').click(function(){
            swal({
                title: "温馨提示",
                text: "操作成功",
                type: "success"
            },function(){
                window.location.reload();
            });
        });
    });
</script>

</body>

</html>
