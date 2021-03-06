@include('layout._header')
@include('layout._boot')
<body>
<div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">演示</a>
        <a>
          <cite>导航元素</cite></a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="x-body">
    <div class="layui-row">
        <form class="layui-form layui-col-md12 x-so" method="post" action="{{ route('users.index') }}">
            {{--<input class="layui-input" placeholder="开始日" name="start" id="start">--}}
            {{--<input class="layui-input" placeholder="截止日" name="end" id="end">--}}
            <input type="text" name="username"  placeholder="请输入用户名" autocomplete="off" class="layui-input">
            <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
        </form>
    </div>
    @include('layout._notice')
    @include('layout._errors')
    <xblock>
        {{--<button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>--}}
        {{--<button class="layui-btn" data-toggle="modal" data-target="#AddModel"><i class="layui-icon"></i>添加</button>--}}
        {{--<span class="x-right" style="line-height:40px">共有数据：88 条</span>--}}
    </xblock>
    <table class="layui-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>用户名</th>
            <th>邮箱</th>
            <th>状态</th>
            <th>操作</th>
        </thead>
        <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
                @if($user->status == 0)
                    封号
                @else
                    正常
                @endif
            </td>
            <td class="td-manage">
                {{--<a class="btn btn-info" href="#" role="button" data-toggle="modal" data-target="#ShowModel" onclick="getShow({{ $admin }})">查看</a>--}}
                <a class="btn btn-warning" href="#" role="button" data-toggle="modal" data-target="#EditModel" onclick="getEdit({{ $user }})">修改密码</a>
                <a class="btn btn-warning" href="{{ route('users.ustatus',[$user]) }}" role="button" >
                    @if($user->status == 0)
                        解封
                    @else
                        封停
                    @endif
                </a>
                {{--<form action="{{ route('admins.destroy',[$admin]) }}" method="post" style="display: inline">--}}
                    {{--{{ csrf_field() }}--}}
                    {{--{{ method_field('delete') }}--}}
                    {{--<input type="submit" value="删除" class="btn btn-danger">--}}
                {{--</form>--}}
            </td>
        </tr>
            @endforeach
        </tbody>
    </table>
    {{ $users->appends(['keyword'=>$keyword])->links() }}

    <div class="modal fade" id="EditModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">重置密码</h4>
                </div>
                <div class="modal-body">
                        <div style=";margin: 50px 50px">
                            <form action="{{ route('users.update') }}" method="post" enctype="multipart/form-data">
                                <input type="hidden" value="" id="EditId" name="id">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">密码</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="请输入密码" >
                                    </div>
                                    {{ csrf_field() }}
                                    {{ method_field('patch') }}
                                    <div>
                                        <button type="submit" class="btn btn-default">提交</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function getEdit($data){
        $('#EditId').val($data['id']);
    //     $('#name').val($data['name']);
    //     $('#email').val($data['email'])
    //     $('#password').val($data['password'])
    }

</script>
</body>