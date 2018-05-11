<aside class="bg-black dk aside hidden-print" id="nav">
    <section class="vbox">
        <section class="w-f-md scrollable">
            <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="10px" data-railOpacity="0.2">
                <!-- nav -->
                <nav class="nav-primary  hidden-xs">
                    <ul class="nav" data-ride="collapse">
                        <li @if($route_name == 'retail') class="active" @endif>
                            <a href="{{ url('retail') }}" class="auto">
                                <i class="fa fa-bar-chart-o  text-success"></i>
                                <span>零售系统</span>
                            </a>
                        </li>
                        @foreach($menu_data as $key=>$val)
                            @if(!empty($val['menu_route']))
                                <li @if($route_name==$val['menu_route']) class="active" @endif>
                                <a href="{{ url($val['menu_route']) }}" class="auto">
                                    <i class="{{ $val['icon_class'] }}"></i>
                                    <span class="font-bold ">{{ $val['menu_name'] }}</span>
                                </a>
                                </li>
                            @else
                            <li @if(in_array($route_name,explode(',',$val['menu_routes_bind']))) class="active" @endif>
                                <a href="javascript:;" class="auto">
                                    <span class="pull-right text-muted">
                                      <i class="fa fa-angle-left text"></i>
                                      <i class="fa fa-angle-down text-active"></i>
                                    </span>
                                    <i class="{{ $val['icon_class'] }}">
                                    </i>
                                    <span class="font-bold">{{ $val['menu_name'] }}</span>
                                </a>
                                <ul class="nav dk text-sm">
                                    @foreach($son_menu_data[$val['id']] as $k=>$v)
                                    <li @if($route_name == $v['menu_route']) class="active" @endif>
                                        <a href="{{ url($v['menu_route']) }}" class="auto">
                                            <i class="fa fa-angle-right text-xs text-info"></i>
                                            <span>{{ $v['menu_name'] }}</span>
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                            @endif
                        @endforeach
                        <li>
                            <a href="{{ url('retail/quit') }}" class="auto">
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