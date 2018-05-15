<div class="sidebar-toggle-box">
    <div data-original-title="Toggle Navigation" data-placement="right" class="icon-reorder tooltips"></div>
</div>
<!--logo start-->
<a href="#" class="logo">ZER<span>O</span>NE</a>

<div class="top-nav ">
    <!--search & user info start-->
    <ul class="nav pull-right top-menu">
        @if($admin_data['is_super'] == 1)
        <li class="dropdown">
            <a class="dropdown-toggle" href="{{url('agent/switch_status')}}">
                <i class="icon-user"></i>
                <span class="username"> 切换分公司</span>
            </a>

        </li>
        @endif

        <li class="dropdown">
            <a class="dropdown-toggle" href="#">
                <i class="icon-user"></i>
                <span class="username"> {{ $admin_data['role_name'] }}-{{ $admin_data['account'] }}</span>
            </a>

        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" href="{{ url('agent/quit') }}">

                <span class="username">退出登录</span>
                <i class="icon-arrow-right"></i>
            </a>

        </li>
        <!-- user login dropdown end -->
    </ul>
    <!--search & user info end-->
</div>