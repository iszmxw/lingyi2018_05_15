<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>零壹新科技程序管理平台</title>
    <link href="{{asset('public/Zerone/library/bootstrap')}}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('public/Zerone/library/font')}}/css/font-awesome.css" rel="stylesheet">
    <link href="{{asset('public/Zerone/library/sweetalert')}}/css/sweetalert.css" rel="stylesheet">

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
                <h2>“{{$listOrg->organization_name}}”人员结构</h2>
                <ol class="breadcrumb">
                    <li class="active">
                        <a href="JavaScript:;">分公司管理</a>
                    </li>
                    <li >
                        <strong>“{{$listOrg->organization_name}}”人员结构</strong>
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
                            <h5>“{{$listOrg->organization_name}}”人员架构</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="dd" id="nestable2">
                                <ol class="dd-list">
                                    <li class="dd-item" data-id="1">
                                        <div class="dd-handle">
                                            {{ $oneOrg['account']. '-'.$oneOrg['account_info']['realname'] }}
                                        </div>

                                        {!! $structure  !!}

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

    {{--<!-- Page-Level Scripts -->--}}
    <script src="{{asset('public/Zerone/library/jquery')}}/js/jquery-2.1.1.js"></script>
    <script src="{{asset('public/Zerone/library/bootstrap')}}/js/bootstrap.min.js"></script>
    <script src="{{asset('public/Zerone/library/metisMenu')}}/js/jquery.metisMenu.js"></script>
    <script src="{{asset('public/Zerone/library/slimscroll')}}/js/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="{{asset('public/Zerone')}}/js/inspinia.js"></script>
    <script src="{{asset('public/Zerone/library/pace')}}/js/pace.min.js"></script>

    <!-- Data picker -->
    <script src="{{asset('public/Zerone/library/nestable')}}/js/jquery.nestable.js"></script>




    <script>
        $(document).ready(function() {
            // activate Nestable for list 2
            $('#nestable2').nestable();

            $('#nestable-menu').on('click', function (e) {
                var target = $(e.target),
                    action = target.data('action');
                if (action === 'expand-all') {
                    $('.dd').nestable('expandAll');
                }
                if (action === 'collapse-all') {
                    $('.dd').nestable('collapseAll');
                }
            });
            $('#expand-all').click(function(){
                $('.dd').nestable('expandAll');
            });
            $('#collapse-all').click(function(){
                $('.dd').nestable('collapseAll');
            });
        });
    </script>

</div>
</body>

</html>
