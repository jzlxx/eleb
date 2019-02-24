<div class="logo"><a href="{{ route('welcome.index') }}">X-admin v2.0</a></div>
<div class="left_open">
    <i title="展开左侧栏" class="iconfont">&#xe699;</i>
</div>
<ul class="layui-nav left fast-add" lay-filter="">
    <li class="layui-nav-item">
        <a href="javascript:;">+新增</a>
        <dl class="layui-nav-child"> <!-- 二级菜单 -->
            <dd><a onclick="x_admin_show('资讯','http://www.baidu.com')"><i class="iconfont">&#xe6a2;</i>资讯</a></dd>
            <dd><a onclick="x_admin_show('图片','http://www.baidu.com')"><i class="iconfont">&#xe6a8;</i>图片</a></dd>
            <dd><a onclick="x_admin_show('用户','http://www.baidu.com')"><i class="iconfont">&#xe6b8;</i>用户</a></dd>
        </dl>
    </li>
</ul>
<ul class="layui-nav right sub-menu" lay-filter="">
    <li class="layui-nav-item">
        <a href="javascript:;">{{ auth()->user()->name }}</a>
        <dl class="layui-nav-child"> <!-- 二级菜单 -->
            <dd><a href="#" >个人信息</a></dd>
            <dd><a href="{{ route('users.pwd') }}">修改密码</a></dd>
            <dd><a href="{{ route('logout') }}">退出</a></dd>

    </li>
    <li class="layui-nav-item to-index"><a href="/">前台首页</a></li>
</ul>
        