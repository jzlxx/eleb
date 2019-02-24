@include('layout._header')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <form class="layui-form layui-col-md12 x-so" method="post" action="{{ route('menucategories.index') }}">
            {{--<input class="layui-input" placeholder="开始日" name="start" id="start">--}}
            {{--<input class="layui-input" placeholder="截止日" name="end" id="end">--}}
            <input type="text" name="username"  placeholder="请输入分类名" autocomplete="off" class="layui-input">
            <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
        </form>
    </div>
    @include('layout._notice')
    <xblock>
        {{--<button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>--}}
        <button class="layui-btn" data-toggle="modal" data-target="#AddModel"><i class="layui-icon"></i>添加</button>
        {{--<span class="x-right" style="line-height:40px">共有数据：88 条</span>--}}
    </xblock>
    <table class="layui-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>分类名</th>
            <th>编号</th>
            <th>是否默认</th>
            <th>操作</th>
        </thead>
        <tbody>
        @foreach($menucategories as $menucategory)
        <tr>
            <td>{{ $menucategory->id }}</td>
            <td>{{ $menucategory->name }}</td>
            <td>{{ $menucategory->type_accumulation }}</td>
            <td class="td-status">
                @if($menucategory->is_selected == 0)
                    否
                    @else
                    是
                @endif
            </td>

            <td class="td-manage">
                <a class="btn btn-info" href="#" role="button" data-toggle="modal" data-target="#ShowModel" onclick="getShow({{ $menucategory }})">查看</a>
                <a class="btn btn-warning" href="#" role="button" data-toggle="modal" data-target="#EditModel" onclick="getEdit({{ $menucategory }})">修改</a>
                <a class="btn btn-warning" href="{{ route('menucategories.ustatus',[$menucategory]) }}" role="button" >
                    @if($menucategory->is_selected == 0)
                        设为默认
                        @else
                        取消默认
                    @endif
                </a>
                <form action="{{ route('menucategories.destroy',[$menucategory]) }}" method="post" style="display: inline">
                    {{ csrf_field() }}
                    {{ method_field('delete') }}
                    <input type="submit" value="删除" class="btn btn-danger">
                </form>
            </td>
        </tr>
            @endforeach
        </tbody>
    </table>
    {{ $menucategories->appends(['keyword'=>$keyword])->links() }}

    <div class="modal fade" id="ShowModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
                    <h4 class="modal-title" id="myModalLabel">分类详情</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="{{ route('menucategories.store') }}" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">分类名</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="stitle" name="name" value="" placeholder="请输入分类名" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">菜品编号</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="stype_accumulation" name="" value=""  readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">描述</label>
                            <div class="col-sm-10">
                                <textarea name="description" id="sdescription" cols="30" rows="10" class="form-control" placeholder="请输入描述" readonly></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">默认分类</label>
                            <div class="col-sm-10">
                                <select name="is_selected" id="sis_selected" class="form-control" disabled>
                                    <option value="1">是</option>
                                    <option value="0">否</option>
                                </select>
                            </div>
                        </div>
                        {{--{{ csrf_field() }}--}}
                        {{--<div class="form-group">--}}
                            {{--<div class="col-sm-offset-2 col-sm-10">--}}
                                {{--<button type="submit" class="btn btn-default">提交</button>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    </form>
                </div>
                <div class="modal-footer">
                    <a class="layui-btn layui-btn-small btn btn-default" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">关闭</a>
                    {{--<button type="button" class="btn btn-default" data-dismiss="modal" >关闭</button>--}}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="AddModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">添加分类</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="{{ route('menucategories.store') }}" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">分类名</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="title" name="name" value="{{ old('name') }}" placeholder="请输入分类名">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">描述</label>
                            <div class="col-sm-10">
                                <textarea name="description" id="" cols="30" rows="10" class="form-control" placeholder="请输入描述">{{ old('description') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">默认分类</label>
                            <div class="col-sm-10">
                                <select name="is_selected" id="" class="form-control">
                                    <option value="1">是</option>
                                    <option value="0">否</option>
                                </select>
                            </div>
                        </div>
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-default">提交</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" >关闭</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="EditModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
                    <h4 class="modal-title" id="myModalLabel">修改分类</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="{{ route('menucategories.update') }}" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="EditId" value="">
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">分类名</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="EditName" name="name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">描述</label>
                            <div class="col-sm-10">
                                <textarea name="description" id="EditDescription" cols="30" rows="10" class="form-control" placeholder="请输入描述"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">默认分类</label>
                            <div class="col-sm-10">
                                <select name="is_selected" id="is_selected" class="form-control">
                                    <option value="1">是</option>
                                    <option value="0">否</option>
                                </select>
                            </div>
                        </div>
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-default">提交</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <a class="layui-btn layui-btn-small btn btn-default" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">关闭</a>
                    {{--<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>--}}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
<script src="https://cdn.jsdelivr.net/npm/jquery@1.12.4/dist/jquery.min.js"></script>
<!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>
<script>
    function getEdit($data){
        $('#EditName').val($data['name']);
        $('#EditId').val($data['id']);
        $('#EditDescription').val($data['description']);
        $("#is_selected option[value="+$data['is_selected']+"]").attr("selected",true);
    }

    function getShow($data){
        $('#stitle').val($data['name']);
        $('#sdescription').val($data['description']);
        $('#stype_accumulation').val($data['type_accumulation']);
        $("#sis_selected option[value="+$data['is_selected']+"]").attr("selected",true);
    }
</script>
</body>