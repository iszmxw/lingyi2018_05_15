<aside class="bg-black dk aside hidden-print" id="nav">
    <section class="vbox">
        <section class="w-f-md scrollable">
            <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="10px" data-railOpacity="0.2">
                <!-- nav -->
                <nav class="nav-primary  hidden-xs">
                    <ul class="nav" data-ride="collapse">
                        <li class="active">
                            <a href="index.html" class="auto">
                                <i class="fa fa-bar-chart-o  text-success"></i>
                                <span class="font-bold ">分店概况</span>
                            </a>
                        </li>
                        <li>
                            <a href="jacascript:;" class="auto">
                        <span class="pull-right text-muted">
                          <i class="fa fa-angle-left text"></i>
                          <i class="fa fa-angle-down text-active"></i>
                        </span>
                                <i class="icon-screen-desktop icon text-success">
                                </i>
                                <span class="font-bold">账户中心</span>
                            </a>
                            <ul class="nav dk text-sm">
                                <li>
                                    <a href="profile.html" class="auto">
                                        <i class="fa fa-angle-right text-xs text-info"></i>
                                        <span>账号信息</span>
                                    </a>
                                </li>
                                <li >
                                    <a href="password.html" class="auto">
                                        <i class="fa fa-angle-right text-xs text-info"></i>
                                        <span>登录密码修改</span>
                                    </a>
                                </li>
                                <li >
                                    <a href="safe_password.html" class="auto">
                                        <i class="fa fa-angle-right text-xs text-info"></i>
                                        <span>安全密码设置</span>
                                    </a>
                                </li>
                                <li >
                                    <a href="message_setting.html" class="auto">
                                        <i class="fa fa-angle-right text-xs text-info"></i>
                                        <span>消息接收设置</span>
                                    </a>
                                </li>
                                <li >
                                    <a href="operation_log.html" class="auto">
                                        <i class="fa fa-angle-right text-xs text-info"></i>
                                        <span>操作日志</span>
                                    </a>
                                </li>
                                <li >
                                    <a href="login_log.html" class="auto">
                                        <i class="fa fa-angle-right text-xs text-info"></i>
                                        <span>登录日志</span>
                                    </a>
                                </li>
                            </ul>
                        </li>


                        @foreach($menu_data as $key=>$val)
                            <li @if(in_array($route_name,explode(',',$val->menu_routes_bind))) class="active" @endif>
                                <a href="javascript:;" class="auto">
                                <span class="pull-right text-muted">
                                  <i class="fa fa-angle-left text"></i>
                                  <i class="fa fa-angle-down text-active"></i>
                                </span>
                                    <i class="{{ $val->icon_class }}">
                                    </i>
                                    <span>{{ $val->menu_name }}</span>
                                </a>
                                <ul class="nav dk text-sm">
                                    @foreach($son_menu_data[$val->id] as $k=>$v)
                                        <li @if($route_name == $v->menu_route) class="active" @endif>
                                            <a href="{{ url($v->menu_route) }}" class="auto">
                                                <i class="fa fa-angle-right text-info"></i>
                                                <span>{{ $v->menu_name }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                        <li>
                            <a href="{{ url('branch/quit') }}" class="auto">
                                <i class="icon-logout icon text-danger"></i>
                                <span>退出系统</span>
                            </a>
                        </li>
                    </ul>

                </nav>
                <!-- / nav -->
            </div>
        </section>


    </section>
</aside>