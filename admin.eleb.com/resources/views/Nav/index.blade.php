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
        {{--<form class="layui-form layui-col-md12 x-so" method="post" action="{{ route('admins.index') }}">--}}
            {{--<input class="layui-input" placeholder="开始日" name="start" id="start">--}}
            {{--<input class="layui-input" placeholder="截止日" name="end" id="end">--}}
            {{--<input type="text" name="username"  placeholder="请输入用户名" autocomplete="off" class="layui-input">--}}
            {{--<button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>--}}
        {{--</form>--}}
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
            <th>名称</th>
            <th>路由</th>
            <th>上级列表</th>
            <th>操作</th>
        </thead>
        <tbody>
        @foreach($navs as $nav)
        <tr>
            <td>{{ $nav->id }}</td>
            <td>{{ $nav->name }}</td>
            <td>
                @if($nav->url == null)
                    无
                    @else
                    {{ $nav->url }}
                @endif
            </td>
            <td>
                @if($nav->pid == 0)
                    顶级菜单
                @else
                    @foreach($unavs as $n)
                        @if($nav->pid == $n->id)
                            {{ $n->name }}
                        @endif
                    @endforeach
                @endif
            </td>
            <td class="td-manage">
                {{--<a class="btn btn-info" href="#" role="button" data-toggle="modal" data-target="#ShowModel" onclick="getShow([{{ $admin }},{{ $admin->getRoleNames() }}])">查看</a>--}}
                <a class="btn btn-warning" href="#" role="button" data-toggle="modal" data-target="#EditModel" onclick="getEdit({{ $nav }})">修改菜单</a>
                @if($nav->pid == 0)
                    <a class="btn btn-warning" href="#" role="button" data-toggle="modal" data-target="#RoleModel" onclick="getRole({{ $nav }})">修改权限</a>
                @endif
                <form action="{{ route('navs.destroy',[$nav]) }}" method="post" style="display: inline">
                    {{ csrf_field() }}
                    {{ method_field('delete') }}
                    <input type="submit" value="删除" class="btn btn-danger">
                </form>
            </td>
        </tr>
            @endforeach
        </tbody>
    </table>
    {{ $navs->links() }}



    <div class="modal fade" id="AddModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">添加菜单</h4>
                </div>
                <div class="modal-body">
                        <div style=";margin: 50px 50px">
                            <form action="{{ route('navs.store') }}" method="post" enctype="multipart/form-data">
                                {{--<input type="hidden" value="" id="EditId" name="id">--}}
                                <div class="row">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">名称</label>
                                            <input type="text" class="form-control" id="" name="name" placeholder="请输入名称" value="{{ old('name') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">路由</label>
                                            <input type="text" class="form-control" id="" name="url" placeholder="请输入路由" value="{{ old('email') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">上级列表</label>
                                            <select name="pid" id="" class="form-control">
                                                <option value="0">请选择上级列表</option>
                                                @foreach($unavs as $n)
                                                    <option value="{{ $n->id }}">{{ $n->name }}</option>
                                                    @endforeach
                                            </select>
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
                    <h4 class="modal-title" id="myModalLabel">修改菜单</h4>
                </div>
                <div class="modal-body">
                    <div style=";margin: 50px 50px">
                        <form action="{{ route('navs.update') }}" method="post" enctype="multipart/form-data">
                            <input type="hidden" value="" id="EditId" name="id">
                            <div class="form-group">
                                <label for="exampleInputEmail1">名称</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="请输入名称" value="{{ old('name') }}">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">路由</label>
                                <input type="text" class="form-control" id="url" name="url" placeholder="请输入路由" value="{{ old('email') }}">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">上级列表</label>
                                <select name="pid" id="pid" class="form-control">
                                    <option value="0">请选择上级列表</option>
                                    @foreach($unavs as $n)
                                        <option value="{{ $n->id }}">{{ $n->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{ csrf_field() }}
                            <div>
                                <button type="submit" class="btn btn-default">提交</button>
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


    <div class="modal fade" id="RoleModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">修改权限</h4>
                </div>
                <div class="modal-body">
                    <div style=";margin: 50px 50px">
                        <form action="{{ route('navs.rupdate') }}" method="post" enctype="multipart/form-data">
                            <input type="hidden" value="" id="rId" name="id">
                            <div class="form-group">
                                <label for="exampleInputEmail1">权限</label>
                                <select name="permission_id" id="permission_id" class="form-control">
                                    <option value="0">请选择权限</option>
                                    @foreach($permissions as $permission)
                                        <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{ csrf_field() }}
                            <div>
                                <button type="submit" class="btn btn-default">提交</button>
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
        $('#name').val($data['name']);
        $('#url').val($data['url']);
        if ($data['pid'] == 0){
            $("#pid").attr('disabled',true);
        }else{
            $("#pid option[value="+$data['pid']+"]").attr('selected',true);
        }
    }

    function getRole($data){
        $('#rId').val($data['id']);
        $("#permission_id option[value="+$data['permission_id']+"]").attr('selected',true);
    }
</script>
<script>
    $('#EditModel,#ShowModel,#RoleModel').on('hidden.bs.modal', function (e) {
        window.location.reload();
    })
</script>
</body>