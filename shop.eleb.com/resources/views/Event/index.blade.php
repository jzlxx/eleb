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
        {{--<form class="layui-form layui-col-md12 x-so" method="get" action="{{ route('activities.index') }}">--}}
            {{--<input class="layui-input" placeholder="开始日" name="start" id="start">--}}
            {{--<input class="layui-input" placeholder="截止日" name="end" id="end">--}}
            {{--<div class="layui-input-inline">--}}
                {{--<select name="time">--}}
                    {{--<option value="">全部</option>--}}
                    {{--<option value="1">未开始</option>--}}
                    {{--<option value="2">进行中</option>--}}
                {{--</select>--}}
            {{--</div>--}}
            {{--<input class="layui-btn" type="submit"  lay-submit="" lay-filter="sreach" value="搜索"></input>--}}
        {{--</form>--}}
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
            <th>活动名</th>
            <th>详情</th>
            <th>报名结束时间</th>
            <th>开奖时间</th>
            <th>操作</th>
        </thead>
        <tbody>
        @foreach($events as $event)
            <?php
            $members = [];
            foreach ($event->eventprize as $eventp){
                $mem_id = $eventp->member_id;
                if ($mem_id){
                    $member = \App\Models\User::find($mem_id);
                    $members[] = $member;
                }
            }
            $members = json_encode($members);
            ?>
            @if(count($event->eventprize) != 0)
                <tr>
                    <td>{{ $event->id }}</td>
                    <td>{{ $event->title }}</td>
                    <td>{{ $event->content }}</td>
                    <td>{{ date('Y-m-d',$event->signup_end) }}</td>
                    <td>{{ $event->prize_date }}</td>
                    <td class="td-manage">
        {{--                <a class="btn btn-info" href="#" role="button" data-toggle="modal" data-target="#ShowModel" onclick="getShow({{ $activity }})">查看详情</a>--}}
                        @if(date('Y-m-d',$event->signup_end) >= date('Y-m-d'))
                            @if(\App\Models\EventMember::where('events_id','=',$event->id)->where('member_id','=',\Illuminate\Support\Facades\Auth::user()->id)->count() != 0)
                                @if(\App\Models\EventMember::where('events_id','=',$event->id)->count() < $event->signup_num)
                                    <a class="btn btn-info" href="{{ route('events.sign',[$event]) }}" role="button">报名参加</a>
                                    @else
                                    <a class="btn btn-default" href="" role="button" disabled>已报名</a>
                                @endif
                            @elseif(\App\Models\EventMember::where('events_id','=',$event->id)->where('member_id','=',\Illuminate\Support\Facades\Auth::user()->id)->count())
                                <a class="btn btn-default" href="" role="button" disabled>名额已满</a>
                            @endif
                            @else
                            <a class="btn btn-default" href="" role="button" disabled>报名结束</a>
                        @endif
                        @if($event->prize_date <= date('Y-m-d'))
                            <a class="btn btn-info" href="#" role="button" data-toggle="modal" data-target="#spModel" onclick="getSpm([{{ $event->eventprize }},{{ $members }}])">查看中奖名单</a>
                        @endif
                    </td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>
    {{ $events->links() }}
</div>

<div class="modal fade" id="spModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">中奖名单</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="" method="post" enctype="multipart/form-data" id="spm">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>
<!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
<script src="https://cdn.jsdelivr.net/npm/jquery@1.12.4/dist/jquery.min.js"></script>
<!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>
<script>
    function getSpm($data) {
        for (var i=0;i<$data[0].length;i++){
            if ($data[0][i]['member_id'] == $data[1][i]['id']){
                var spm = "<div class=\"form-group\">\n" +
                    "                   <label for=\"title\" class=\"col-sm-2 control-label\">中奖用户</label>\n" +
                    "                        <div class=\"col-sm-10\">\n" +
                    "                            <p>奖品："+$data[0][i]['name']+"&emsp;用户："+$data[1][i]['name']+"</p>" +
                    "                        </div>\n" +
                    "               </div>";
                $('#spm').append(spm);
            }
        }
    }
</script>
<script>
    $('#spModel').on('hidden.bs.modal', function (e) {
        window.location.reload();
    })
</script>
</body>