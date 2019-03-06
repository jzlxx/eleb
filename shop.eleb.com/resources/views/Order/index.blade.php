@include('layout._header')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<!--引入CSS-->
<link rel="stylesheet" type="text/css" href="/webuploader/webuploader.css">
<!--引入JS-->
<script type="text/javascript" src="/webuploader/webuploader.js"></script>

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
        {{--<form class="layui-form layui-col-md12 x-so" action="{{route('order.index')}}" method="get">--}}
            {{--<input type="text" class="layui-input" placeholder="最低价格" name="start" value="">--}}
            {{--<input type="text" class="layui-input" placeholder="最高价格" name="end" value="">--}}
            {{--<input type="text" name="keyword"  placeholder="请输入菜品名" class="layui-input" value="">--}}
            {{--<div class="layui-input-inline">--}}
                {{--<select name="category_id">--}}
                    {{--<option value="">全部</option>--}}
                    {{--@foreach($menucategories as $menucategory)--}}
                        {{--<option value="{{$menucategory->id}}" @if($menucategory->id == $category_id) selected @elseif($menucategory->selected == 1) selected @endif>{{$menucategory->name}}</option>--}}
                    {{--@endforeach--}}
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
            <th>订单编号</th>
            <th>联系方式</th>
            <th>收货人姓名</th>
            <th>状态</th>
            <th>操作</th>
        </thead>
        <tbody>
        @foreach($orders as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->sn }}</td>
            <td>{{ $order->tel }}</td>
            <td>{{ $order->name }}</td>
            <td class="td-status">
                @if($order->status == 0)
                    待支付
                @elseif($order->status == 1)
                    待发货
                @elseif($order->status == 2)
                    待确认
                @elseif($order->status == 3)
                    完成
                @elseif($order->status == -1)
                    已取消
                @endif
            </td>
            <td class="td-manage">
                <a class="btn btn-info" href="#" role="button" data-toggle="modal" data-target="#ShowModel" onclick="getShow([{{ $order }},{{ $order->OrderDetail }}])">查看</a>
                {{--<a class="btn btn-warning" href="#" role="button" data-toggle="modal" data-target="#EditModel" onclick="getEdit({{ $menu }})">修改</a>--}}
                @if($order->status == 0)
                <a class="btn btn-default" href="#" role="button" disabled="disabled">待支付</a>
                    @elseif($order->status == 1)
                    <a class="btn btn-warning" href="{{route('orders.fstatus',[$order])}}" role="button">发&emsp;货</a>
                @elseif($order->status == 2)
                    <a class="btn btn-default" href="#" disabled="disabled" role="button">待确认</a>
                @elseif($order->status == -1)
                    <a class="btn btn-info" href="#" disabled="disabled" role="button">已取消</a>
                @endif
                @if($order->status != -1)
                <a class="btn btn-danger" href="{{route('orders.ustatus',[$order])}}" role="button">取消订单</a>
                    @endif
            </td>
        </tr>
            @endforeach
        </tbody>
    </table>
    {{ $orders->links() }}

    <div class="modal fade" id="ShowModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
                    <h4 class="modal-title" id="myModalLabel">订单详情</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">订单编号</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="sn" name="goods_name" value=""  readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">收货地址</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="address" name="" value=""  readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">收货人姓名</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" name="" value=""  readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">联系方式</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="tel" name="" value=""  readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">商品信息</label>
                            <div class="col-sm-10">
                                <p id="goods"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">下单时间</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="time" name="" value=""  readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">订单价格</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="total" name="" value=""  readonly>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <a class="layui-btn layui-btn-small btn btn-default" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">关闭</a>
                    {{--<button type="button" class="btn btn-default" data-dismiss="modal" >关闭</button>--}}
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
    function getShow($data){
        $('#sn').val($data[0]['sn']);
        $('#address').val($data[0]['address']);
        $('#name').val($data[0]['name']);
        $('#tel').val($data[0]['tel']);
        for ( var i = 0;i < $data[1].length;i++){
            var good_name = " <p>"+$data[1][i]['goods_name']+"</p>";
            var good_img = "<img src=\""+$data[1][i]['goods_img']+"\" alt=\"\" style=\"width: 100px\">";
            var good_num = " <p>数量："+$data[1][i]['amount']+"</p>";
            $('#goods').append(good_name);
            $('#goods').append(good_img);
            $('#goods').append(good_num);
        }
        $('#time').val($data[0]['created_at']);
        $('#total').val($data[0]['total']);
    }
</script>
<script>
    $('#ShowModel').on('hidden.bs.modal', function (e) {
        window.location.reload();
    })
</script>
</body>