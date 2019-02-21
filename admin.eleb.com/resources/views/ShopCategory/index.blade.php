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
        <form class="layui-form layui-col-md12 x-so" method="post" action="{{ route('shopcategories.index') }}">
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
            <th>图片</th>
            <th>状态</th>
            <th>操作</th>
        </thead>
        <tbody>
        @foreach($shopcategories as $shopcategory)
        <tr>
            <td>{{ $shopcategory->id }}</td>
            <td>{{ $shopcategory->name }}</td>
            <td><img src="{{ $shopcategory->img() }}" alt=""></td>
            <td class="td-status">
                @if($shopcategory->status == 0)
                    隐藏
                    @else
                    显示
                @endif
            </td>

            <td class="td-manage">
                <a class="btn btn-info" href="#" role="button" >查看</a>
                <a class="btn btn-warning" href="#" role="button" data-toggle="modal" data-target="#EditModel" onclick="getEdit({{ $shopcategory }})">修改</a>
                <a class="btn btn-warning" href="{{ route('shopcategories.ustatus',[$shopcategory]) }}" role="button" >
                    @if($shopcategory->status == 0)
                        显示
                        @else
                        隐藏
                    @endif
                </a>
                <form action="{{ route('shopcategories.destroy',[$shopcategory]) }}" method="post" style="display: inline">
                    {{ csrf_field() }}
                    {{ method_field('delete') }}
                    <input type="submit" value="删除" class="btn btn-danger">
                </form>
            </td>
        </tr>
            @endforeach
        </tbody>
    </table>

    <div class="modal fade" id="AddModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">添加分类</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="{{ route('shopcategories.store') }}" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">分类名</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="title" name="name" value="{{ old('name') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="img" class="col-sm-2 control-label">图片</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control" id="img" name="img">
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
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="EditModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">修改分类</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="{{ route('shopcategories.update') }}" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="EditId" value="">
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">分类名</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="EditName" name="name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="img" class="col-sm-2 control-label">图片</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control"  name="img">
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
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
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
        console.log($data);
        $('#EditName').val($data['name']);
        $('#EditId').val($data['id']);
    }
</script>
</body>