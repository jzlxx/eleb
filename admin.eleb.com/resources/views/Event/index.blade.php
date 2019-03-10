@include('layout._header')
{{--@include('vendor.ueditor.assets')--}}
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
            <th>报名开始时间</th>
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

            <?php
                $eventid = $event->id;
                $bmembersb = [];
                $bmembers = \App\Models\EventMember::where('events_id','=',$eventid)->get();
                foreach ($bmembers as $bmember){
                    $bmemberb = \App\Models\User::find($bmember->member_id);
                    $bmembersb[] = $bmemberb;
                }
                $bmembersb = json_encode($bmembersb);
            ?>
        <tr>
            <td>{{ $event->id }}</td>
            <td>{{ $event->title }}</td>
            <td>{{ date('Y-m-d',$event->signup_start) }}</td>
            <td>{{ date('Y-m-d',$event->signup_end) }}</td>
            <td>{{ $event->prize_date }}</td>
            <td class="td-manage">
                <a class="btn btn-info" href="#" role="button" data-toggle="modal" data-target="#ShowModel" onclick="getShow([{{ $event }},{{ $event->eventprize }},{{ $bmembersb }}])">查看</a>
                @if(date('Y-m-d',$event->signup_start) > date('Y-m-d'))
                    <a class="btn btn-warning" href="#" role="button" data-toggle="modal" data-target="#EditModel" onclick="getEdit({{ $event }})">修改</a>
                    <a class="btn btn-warning" href="#" role="button" data-toggle="modal" data-target="#AddPModel" onclick="getAddP({{ $event }})">添加奖品</a>
                    @if(count($event->eventprize))
                        <a class="btn btn-danger" href="#" role="button" data-toggle="modal" data-target="#DelModel" onclick="getDel({{ $event->eventprize }})">删除奖品</a>
                    @endif
                    <form action="{{ route('events.destroy',[$event]) }}" method="post" style="display: inline">
                        {{ csrf_field() }}
                        {{ method_field('delete') }}
                        <input type="submit" value="删除" class="btn btn-danger">
                    </form>
                @endif
                @if($event->prize_date <= date('Y-m-d') && count($event->eventprize) != 0 && $event->is_prize == 0)
                    <a class="btn btn-warning" href="{{ route('events.luck',[$event]) }}" role="button">开始抽奖</a>
                @endif
                @if($event->is_prize == 1)
                    <a class="btn btn-info" href="#" role="button" data-toggle="modal" data-target="#spModel" onclick="getSpm([{{ $event->eventprize }},{{ $members }}])">查看中奖名单</a>
                @endif
            </td>
        </tr>
            @endforeach
        </tbody>
    </table>
    {{ $events->links() }}

    <div class="modal fade" id="ShowModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">活动详情</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="" method="post" enctype="multipart/form-data" id="sForm">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
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

    <div class="modal fade" id="AddModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">添加活动</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="{{ route('events.store') }}" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">活动名</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="" name="title" value="{{ old('title') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">报名人数限制</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="" name="signup_num" value="{{ old('signup_num') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">报名开始时间</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" id="" value="{{ date('Y-m-d') }}" name="signup_start" min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d',time()+60*60*24*30) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">报名结束时间</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" id="" value="{{ date('Y-m-d') }}" name="signup_end" min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d',time()+60*60*24*30) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">开奖时间</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" id="" value="{{ date('Y-m-d') }}" name="prize_date" min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d',time()+60*60*24*30) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="img" class="col-sm-2 control-label">活动详情</label>
                            <div class="col-sm-10">
                                <textarea name="content" class="form-control" id="" cols="30" rows="10" style="resize: none"></textarea>
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


    <div class="modal fade" id="AddPModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">添加奖品</h4>
                </div>
                <form class="form-horizontal" action="{{route('eventprizes.store')}}" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" id="events_id" name="events_id">
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">奖品名</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control"  name="name" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">奖品详情</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" rows="5" style="resize: none" name="description"></textarea>
                            </div>
                        </div>
                    </div>
                    {{ csrf_field()}}
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-default">提交</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="DelModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">删除奖品</h4>
                </div>
                <form class="form-horizontal" action="{{route('eventprizes.destory')}}" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">奖品</label>
                            <div class="col-sm-10" id="prizes">

                            </div>
                        </div>
                    </div>
                    {{ csrf_field()}}
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-default">提交</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    </div>
                </form>
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
                    <form class="form-horizontal" action="{{ route('events.update') }}" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="EditId" value="">
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">活动名</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('name') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">报名人数限制</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="signup_num" name="signup_num" value="{{ old('signup_num') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">报名开始时间</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" id="signup_start" value="{{ date('Y-m-d') }}" name="signup_start" min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d',time()+60*60*24*30) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">报名结束时间</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" id="signup_end" value="{{ date('Y-m-d') }}" name="signup_end" min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d',time()+60*60*24*30) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">开奖时间</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" id="prize_date" value="{{ date('Y-m-d') }}" name="prize_date" min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d',time()+60*60*24*30) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="img" class="col-sm-2 control-label">活动详情</label>
                            <div class="col-sm-10">
                                <textarea name="content" class="form-control" id="content" cols="30" rows="10"></textarea>
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
        $('#EditId').val($data['id']);
        $('#title').val($data['title']);
        $('#signup_num').val($data['signup_num']);
        var s_day = new Date($data['signup_start']*1000).getDate();
        if (s_day < 10){
            s_day = '0'+s_day
        }
        var s_month = new Date($data['signup_start']*1000).getMonth()+1;
        if (s_month < 10){
            s_month = '0'+s_month
        }
        var s_year = new Date($data['signup_start']*1000).getFullYear();
        var s_date = s_year+'-'+s_month+'-'+s_day;
        $('#signup_start').val(s_date);

        var e_day = new Date($data['signup_end']*1000).getDate();
        if (e_day < 10){
            e_day = '0'+e_day
        }
        var e_month = new Date($data['signup_end']*1000).getMonth()+1;
        if (e_month < 10){
            e_month = '0'+e_month
        }
        var e_year = new Date($data['signup_end']*1000).getFullYear();
        var e_date = e_year+'-'+e_month+'-'+e_day;
        $('#signup_end').val(e_date);
        $('#prize_date').val($data['prize_date']);
        $('#content').text($data['content']);
    }


    function getAddP($data){
        $('#events_id').val($data['id']);
    }


    function getDel($data){
        for (var i=0;i<$data.length;i++){
            var input = "<input type='checkbox' name='eventprize[]' value='"+$data[i]['id']+"'>"+$data[i]['name']+"<br/>";
            $('#prizes').append(input);
        }
    }


    function getShow($data){
        var stitle = "<div class=\"form-group\">\n" +
            "           <label for=\"title\" class=\"col-sm-2 control-label\">活动名</label>\n" +
            "           <div class=\"col-sm-10\">\n" +
            "               <p>"+$data[0]['title']+"</p>" +
            "           </div>\n" +
            "        </div>";
        $('#sForm').append(stitle);

        var ssignup_num = "<div class=\"form-group\">\n" +
            "                   <label for=\"title\" class=\"col-sm-2 control-label\">报名人数限制</label>\n" +
            "                        <div class=\"col-sm-10\">\n" +
            "                            <p>"+$data[0]['signup_num']+"</p>" +
            "                        </div>\n" +
            "               </div>";
        $('#sForm').append(ssignup_num);

        var s_day = new Date($data[0]['signup_start']*1000).getDate();
        if (s_day < 10){
            s_day = '0'+s_day
        }
        var s_month = new Date($data[0]['signup_start']*1000).getMonth()+1;
        if (s_month < 10){
            s_month = '0'+s_month
        }
        var s_year = new Date($data[0]['signup_start']*1000).getFullYear();
        var s_date = s_year+'-'+s_month+'-'+s_day;
        var ssignup_start = "<div class=\"form-group\">\n" +
            "                   <label for=\"title\" class=\"col-sm-2 control-label\">报名开始时间</label>\n" +
            "                        <div class=\"col-sm-10\">\n" +
            "                            <p>"+s_date+"</p>" +
            "                        </div>\n" +
            "               </div>";
        $('#sForm').append(ssignup_start);

        var e_day = new Date($data[0]['signup_end']*1000).getDate();
        if (e_day < 10){
            e_day = '0'+e_day
        }
        var e_month = new Date($data[0]['signup_end']*1000).getMonth()+1;
        if (e_month < 10){
            e_month = '0'+e_month
        }
        var e_year = new Date($data[0]['signup_end']*1000).getFullYear();
        var e_date = e_year+'-'+e_month+'-'+e_day;
        $('#signup_end').val(e_date);
        var ssignup_end = "<div class=\"form-group\">\n" +
            "                   <label for=\"title\" class=\"col-sm-2 control-label\">报名结束时间</label>\n" +
            "                        <div class=\"col-sm-10\">\n" +
            "                            <p>"+e_date+"</p>" +
            "                        </div>\n" +
            "               </div>";
        $('#sForm').append(ssignup_end);

        var sprize_date = "<div class=\"form-group\">\n" +
            "                   <label for=\"title\" class=\"col-sm-2 control-label\">开奖时间</label>\n" +
            "                        <div class=\"col-sm-10\">\n" +
            "                            <p>"+$data[0]['prize_date']+"</p>" +
            "                        </div>\n" +
            "               </div>";
        $('#sForm').append(sprize_date);

        var scontent = "<div class=\"form-group\">\n" +
            "                   <label for=\"title\" class=\"col-sm-2 control-label\">活动详情</label>\n" +
            "                        <div class=\"col-sm-10\">\n" +
            "                            <p>"+$data[0]['content']+"</p>" +
            "                        </div>\n" +
            "               </div>";
        $('#sForm').append(scontent);

        var sprize = "<div class=\"form-group\">\n" +
            "                   <label for=\"title\" class=\"col-sm-2 control-label\">活动奖品</label>\n" +
            "                        <div class=\"col-sm-10\" id=\"sprizes\">\n" +
            "                        </div>\n" +
            "               </div>";
        $('#sForm').append(sprize);
        for (var i=0;i<$data[1].length;i++){
            var prize = "<p>"+$data[1][i]['name']+"</p>";
            $('#sprizes').append(prize);
        }

        var smember = "<div class=\"form-group\">\n" +
            "                   <label for=\"title\" class=\"col-sm-2 control-label\">参与用户</label>\n" +
            "                        <div class=\"col-sm-10\" id=\"smembers\">\n" +
            "                        </div>\n" +
            "               </div>";
        $('#sForm').append(smember);
        for (var j=0;j<$data[2].length;j++){
            var member = "<p>"+$data[2][j]['name']+"</p>";
            $('#smembers').append(member);
        }

    }

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
    $('#EditModel,#DelModel,#AddPModel,#AddModel,#spModel,#ShowModel').on('hidden.bs.modal', function (e) {
        window.location.reload();
    })
</script>
</body>