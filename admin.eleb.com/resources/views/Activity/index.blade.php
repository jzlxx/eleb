@include('layout._header')
@include('vendor.ueditor.assets')
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
        <form class="layui-form layui-col-md12 x-so" method="get" action="{{ route('activities.index') }}">
            {{--<input class="layui-input" placeholder="开始日" name="start" id="start">--}}
            {{--<input class="layui-input" placeholder="截止日" name="end" id="end">--}}
            <div class="layui-input-inline">
                <select name="time">
                    <option value="">全部</option>
                    <option value="1">未开始</option>
                    <option value="2">进行中</option>
                    <option value="3">已结束</option>
                </select>
            </div>
            <input class="layui-btn" type="submit"  lay-submit="" lay-filter="sreach" value="搜索"></input>
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
            <th>活动名</th>
            <th>开始时间</th>
            <th>结束时间</th>
            <th>操作</th>
        </thead>
        <tbody>
        @foreach($activities as $activity)
        <tr>
            <td>{{ $activity->id }}</td>
            <td>{{ $activity->title }}</td>
            <td>{{ $activity->start_time }}</td>
            <td>{{ $activity->end_time }}</td>
            <td class="td-manage">
                <a class="btn btn-info" href="#" role="button" data-toggle="modal" data-target="#ShowModel" onclick="getShow({{ $activity }})">查看</a>
                <a class="btn btn-warning" href="#" role="button" data-toggle="modal" data-target="#EditModel" onclick="getEdit({{ $activity }})">修改</a>
                <form action="{{ route('activities.destroy',[$activity]) }}" method="post" style="display: inline">
                    {{ csrf_field() }}
                    {{ method_field('delete') }}
                    <input type="submit" value="删除" class="btn btn-danger">
                </form>
            </td>
        </tr>
            @endforeach
        </tbody>
    </table>
    {{ $activities->appends(['time'=>$time])->links() }}

    <div class="modal fade" id="ShowModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">活动详情</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="{{ route('activities.store') }}" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">活动名</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="stitle" name="title" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">开始时间</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="sstart_time" value="" name="start_time" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">结束时间</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="send_time" value="" name="end_time" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="img" class="col-sm-2 control-label">活动详情</label>
                            <div class="col-sm-10" id="scontent">

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
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="AddModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">添加活动</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="{{ route('activities.store') }}" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">活动名</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="" name="title" value="{{ old('name') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">开始时间</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" id="" value="{{ date('Y-m-d') }}" name="start_time" min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d',time()+60*60*24*30) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">结束时间</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" id="" value="{{ date('Y-m-d') }}" name="end_time" min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d',time()+60*60*24*30) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="img" class="col-sm-2 control-label">活动详情</label>
                            <div class="col-sm-10">
                                <script id="container" name="content" type="text/plain"></script>
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
                    <h4 class="modal-title" id="myModalLabel">修改活动</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="{{ route('activities.update') }}" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="EditId" value="">
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">活动名</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('name') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">开始时间</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" id="start_time" value="{{ date('Y-m-d') }}" name="start_time" min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d',time()+60*60*24*30) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">结束时间</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" id="end_time" value="{{ date('Y-m-d') }}" name="end_time" min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d',time()+60*60*24*30) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="img" class="col-sm-2 control-label">活动详情</label>
                            <div class="col-sm-10">
                                <script id="content" name="content" type="text/plain">

                                </script>
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
<script type="text/javascript">
    var ue = UE.getEditor('container');
    ue.ready(function() {
        ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
    });
</script>
<script type="text/javascript">
    var ue = UE.getEditor('content');
    ue.ready(function() {
        ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
    });
</script>
<script>
    function getEdit($data){
        $('#EditId').val($data['id']);
        $('#title').val($data['title']);
        // $('#content').html($data['content']);
        // $('#start_time').val($data['start_time']);
        // $('#end_time').val($data['end_time']);
    }

    function getShow($data){
        $('#stitle').val($data['title']);
        $('#scontent').html($data['content']);
        $('#sstart_time').val($data['start_time']);
        $('#send_time').val($data['end_time']);
    }
</script>
</body>