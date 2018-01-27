<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>选择服务商 | 零壹服务商管理平台</title>

    <!-- Bootstrap core CSS -->
    <link href="{{asset('public/Proxy')}}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('public/Proxy')}}/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="{{asset('public/Proxy')}}/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="{{asset('public/Proxy')}}/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="stylesheet" href="{{asset('public/Proxy')}}/css/owl.carousel.css" type="text/css">
    <!-- Custom styles for this template -->
    <link href="{{asset('public/Proxy')}}/css/style.css" rel="stylesheet">
    <link href="{{asset('public/Proxy')}}/css/style-responsive.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="{{asset('public/Proxy')}}/js/html5shiv.js"></script>
    <script src="{{asset('public/Proxy')}}/js/respond.min.js"></script>
    <![endif]-->
</head>

<body>


<section class="">
    <div class="row state-overview" style="margin: 10px;">
        <div class="col-lg-12 col-sm-6">
            <!--breadcrumbs start -->
            <ul class="breadcrumb">
                <li><h3 style="margin-top: 10px;"><i class="icon-desktop"></i> 选择要进入的服务商组织</h3></li>
            </ul>
            <!--breadcrumbs end -->
        </div>
    </div>

    <div class="row state-overview" style="margin: 10px;">
        <div class="form-group">
            <div class="col-lg-12">
                <div class="input-group m-bot15 col-lg-3 ">
                    <span class="input-group-addon"><i class="icon-search"></i></span>
                    <input type="text" class="form-control " placeholder="服务商名称">

                </div>
                <div class="input-group m-bot15 col-lg-2 ">
                    <button type="button" class="btn btn-primary"><i class="icon-search"></i> 查询</button>
                </div>
            </div>
        </div>
        <div style="clear:both;"></div>
    </div>
    <!--state overview start-->
    <div class="row state-overview" style="margin: 10px;">
        @foreach($listOrg as $key=>$value)
        <div class="col-lg-3 col-sm-6">
            <a href="{{url('proxy/system/select_proxy')}}?organization_id={{ $value->id }}">
                <section class="panel">
                    <div class="symbol terques">
                        <i class="icon-arrow-right"></i>
                    </div>
                    <div class="value">
                        <b>{{$value->organization_name}}</b>
                        <p>{{$value->zone_name}}</p>
                    </div>
                </section>
            </a>
        </div>
        @endforeach
    </div>
    <!--state overview end-->
</section>

</body>
</html>
