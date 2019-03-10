<div class="left-nav">
    <div id="side-nav">
        <ul id="nav">
            @foreach($permissions as $permission)
                @foreach($unavs as $n)
                    @if($permission->id == $n->permission_id && \Illuminate\Support\Facades\Auth::user()->can($permission->name))
                        <li>
                            <a href="javascript:;">
                                <cite>{{ $n->name }}</cite>
                                <i class="iconfont nav_right">&#xe697;</i>
                            </a>
                            <ul class="sub-menu">
                                @foreach($navs as $nav)
                                    @if($nav->pid == $n->id)
                                        <li>
                                            <a _href='{{ route("$nav->url") }}'>
                                                <i class="iconfont">&#xe6a7;</i>
                                                <cite>{{ $nav->name }}</cite>
                                            </a>
                                        </li >
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @endforeach
            @endforeach
        </ul>
    </div>
</div>