<header class="bg-white-only header header-md navbar navbar-fixed-top-xs">
    <div class="navbar-header aside bg-success dk">
        <a class="btn btn-link visible-xs" data-toggle="class:nav-off-screen,open" data-target="#nav,html">
            <i class="icon-list"></i>
        </a>
        <a href="{{asset('branch')}}" class="navbar-brand text-lt">
            <i class="fa fa-cloud"></i>

            <span class="hidden-nav-xs m-l-sm">ZERONE</span>
        </a>
        <a class="btn btn-link visible-xs" data-toggle="dropdown" data-target=".user">
            <i class="icon-settings"></i>
        </a>
    </div>
    <ul class="nav navbar-nav hidden-xs">
        <li>
            <a href="#nav,.navbar-header" data-toggle="class:nav-xs,nav-xs" class="text-muted">
                <i class="fa fa-indent text"></i>
                <i class="fa fa-dedent text-active"></i>
            </a>
        </li>
    </ul>

    <div class="navbar-right ">
        <ul class="nav navbar-nav m-n hidden-xs nav-user user">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle bg clear" data-toggle="dropdown">
                    <i class="icon icon-user"></i>
                    欢迎登录！{{ $admin_data['role_name'] }}-{{ $admin_data['account'] }}<b class="caret"></b>
                </a>
                <ul class="dropdown-menu animated fadeInRight">
                    <li>
                        <span class="arrow top"></span>
                        <a href="#"></a>
                    </li>
                    <li>
                        <a href="{{ url('/retail/account/profile') }}">账号信息</a>
                    </li>

                    <li>
                        <a href="{{ url('/retail/account/password') }}">修改密码</a>
                    </li>
                    @if($admin_data['is_super'] == 1)
                        <li>
                            <a href="{{ url('/retail/retail_switch') }}" >切换店铺</a>
                        </li>
                    @endif
                    <li class="divider"></li>
                    <li>
                        <a href="{{ url('/retail/quit') }}" >退出登录</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</header>