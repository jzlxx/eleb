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
        <form class="layui-form layui-col-md12 x-so" method="post" action="{{ route('admins.index') }}">
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
        <button class="layui-btn" data-toggle="modal" data-target="#AddModel"><i class="layui-icon"></i>添加</button>
        {{--<span class="x-right" style="line-height:40px">共有数据：88 条</span>--}}
    </xblock>
    <table class="layui-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>用户名</th>
            <th>邮箱</th>
            <th>操作</th>
        </thead>
        <tbody>
        @foreach($admins as $admin)
        <tr>
            <td>{{ $admin->id }}</td>
            <td>{{ $admin->name }}</td>
            <td>{{ $admin->email }}</td>
            <td class="td-manage">
                <a class="btn btn-info" href="#" role="button" data-toggle="modal" data-target="#ShowModel" onclick="getShow([{{ $admin }},{{ $admin->getRoleNames() }}])">查看</a>
                <a class="btn btn-warning" href="#" role="button" data-toggle="modal" data-target="#EditModel" onclick="getEdit([{{ $admin }},{{ $admin->getRoleNames() }},{{ $roles }}])">修改角色</a>
                <form action="{{ route('admins.destroy',[$admin]) }}" method="post" style="display: inline">
                    {{ csrf_field() }}
                    {{ method_field('delete') }}
                    <input type="submit" value="删除" class="btn btn-danger">
                </form>
            </td>
        </tr>
            @endforeach
        </tbody>
    </table>
    {{ $admins->appends(['keyword'=>$keyword])->links() }}


    <div class="modal fade" id="ShowModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">管理员详情</h4>
                </div>
                <div class="modal-body">
                    <div style=";margin: 50px 50px">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">用户名</label>
                                    <input type="text" class="form-control" id="sname" name="name"  readonly>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">邮箱</label>
                                    <input type="text" class="form-control" id="semail" name="email" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="title">角色</label>
                                    <br/>
                                    <div id="sRole">

                                    </div>
                                    {{--@foreach($roles as $role)--}}
                                        {{--<input type="checkbox"  value="{{ $role->name }}" name="role[]">{{ $role->name }}<br/>--}}
                                    {{--@endforeach--}}
                                </div>
                                {{--{{ csrf_field() }}--}}
                                {{--<div>--}}
                                    {{--<button type="submit" class="btn btn-default">提交</button>--}}
                                {{--</div>--}}
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


    <div class="modal fade" id="AddModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">添加管理员</h4>
                </div>
                <div class="modal-body">
                        <div style=";margin: 50px 50px">
                            <form action="{{ route('admins.store') }}" method="post" enctype="multipart/form-data">
                                {{--<input type="hidden" value="" id="EditId" name="id">--}}
                                <div class="row">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">用户名</label>
                                            <input type="text" class="form-control" id="" name="name" placeholder="请输入用户名" value="{{ old('name') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">邮箱</label>
                                            <input type="text" class="form-control" id="" name="email" placeholder="请输入邮箱" value="{{ old('email') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">密码</label>
                                            <input type="password" class="form-control" id="" name="password" placeholder="请输入密码" >
                                        </div>
                                        <div class="form-group">
                                            <label for="title">角色</label>
                                            <br/>
                                            @foreach($roles as $role)
                                                <input type="checkbox"  value="{{ $role->name }}" name="role[]">{{ $role->name }}<br/>
                                            @endforeach
                                        </div>
                                        {{ csrf_field() }}
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

    <div class="modal fade" id="EditModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">修改角色</h4>
                </div>
                <div class="modal-body">
                    <div style=";margin: 50px 50px">
                        <form action="{{ route('admins.rupdate') }}" method="post" enctype="multipart/form-data">
                            <input type="hidden" value="" id="EditId" name="id">
                            <div class="row">
                                {{--<div class="form-group">--}}
                                    {{--<label for="exampleInputEmail1">用户名</label>--}}
                                    {{--<input type="text" class="form-control" id="name" name="name" placeholder="请输入用户名" value="{{ old('name') }}">--}}
                                {{--</div>--}}
                                {{--<div class="form-group">--}}
                                    {{--<label for="exampleInputEmail1">邮箱</label>--}}
                                    {{--<input type="text" class="form-control" id="email" name="email" placeholder="请输入邮箱" value="{{ old('email') }}">--}}
                                {{--</div>--}}
                                {{--<div class="form-group">--}}
                                    {{--<label for="exampleInputEmail1">密码</label>--}}
                                    {{--<input type="password" class="form-control" id="password" name="password" placeholder="请输入密码" >--}}
                                {{--</div>--}}
                                <div class="form-group">
                                    <label for="title">角色</label>
                                    <br/>
                                    @foreach($roles as $role)
                                        <input type="checkbox" id="{{ $role->id }}" value="{{ $role->name }}" name="role[]">{{ $role->name }}<br/>
                                    @endforeach
                                </div>
                                {{ csrf_field() }}
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
        $('#EditId').val($data[0]['id']);
        // $('#name').val($data[0]['name']);
        // $('#email').val($data[0]['email']);
        // $('#password').val($data[0]['password']);
        for(var i =0;i < $data[2].length;i++){
            if($data[1].includes( $("#"+$data[2][i]['id']).val())){
                $("#"+$data[2][i]['id']).attr("checked",true);
            }
        }
    }

    function getShow($data){
        $('#sname').val($data[0]['name']);
        $('#semail').val($data[0]['email']);
        for(var i =0;i < $data[1].length;i++){
            var roleName = " <p>"+$data[1][i]+"</p>";
            $('#sRole').append(roleName);
        }
    }
</script>
<script>
    $('#EditModel,#ShowModel').on('hidden.bs.modal', function (e) {
        window.location.reload();
    })
</script>
</body>