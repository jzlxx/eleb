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
        <form class="layui-form layui-col-md12 x-so" action="{{route('menus.index')}}" method="get">
            <input type="text" class="layui-input" placeholder="最低价格" name="start" value="">
            <input type="text" class="layui-input" placeholder="最高价格" name="end" value="">
            <input type="text" name="keyword"  placeholder="请输入菜品名" class="layui-input" value="">
            <div class="layui-input-inline">
                <select name="category_id">
                    <option value="">全部</option>
                    @foreach($menucategories as $menucategory)
                        <option value="{{$menucategory->id}}" @if($menucategory->id == $category_id) selected @elseif($menucategory->selected == 1) selected @endif>{{$menucategory->name}}</option>
                    @endforeach
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
            <th>菜品名</th>
            <th>图片</th>
            <th>分类</th>
            <th>价格</th>
            <th>状态</th>
            <th>操作</th>
        </thead>
        <tbody>
        @foreach($menus as $menu)
        <tr>
            <td>{{ $menu->id }}</td>
            <td>{{ $menu->goods_name }}</td>
            <td><img src="{{ $menu->goods_img }}" alt=""></td>
            <td>{{ $menu->Menucategory->name }}</td>
            <td>{{ $menu->goods_price }}</td>
            <td class="td-status">
                @if($menu->status == 0)
                    已下架
                @else
                    上架中
                @endif
            </td>
            <td class="td-manage">
                <a class="btn btn-info" href="#" role="button" data-toggle="modal" data-target="#ShowModel" onclick="getShow({{ $menu }})">查看</a>
                <a class="btn btn-warning" href="#" role="button" data-toggle="modal" data-target="#EditModel" onclick="getEdit({{ $menu }})">修改</a>
                <a class="btn btn-warning" href="{{ route('menus.ustatus',[$menu]) }}" role="button" >
                    @if($menu->status == 0)
                        上架
                        @else
                        下架
                    @endif
                </a>
                <form action="{{ route('menus.destroy',[$menu]) }}" method="post" style="display: inline">
                    {{ csrf_field() }}
                    {{ method_field('delete') }}
                    <input type="submit" value="删除" class="btn btn-danger">
                </form>
            </td>
        </tr>
            @endforeach
        </tbody>
    </table>
    {{ $menus->appends(['keyword'=>$keyword,'category_id'=>$category_id,'start'=>$start,'end'=>$end])->links() }}

    <div class="modal fade" id="ShowModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
                    <h4 class="modal-title" id="myModalLabel">分类详情</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">菜品名</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="sgoods_name" name="goods_name" value=""  readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">图片</label>
                            <div class="col-sm-10">
                                <img src="" alt="" id="sgoods_img" style="width: 200px;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">评分</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="srating" name="" value=""  readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">菜品分类</label>
                            <div class="col-sm-10">
                                <select name="category_id" id="scategory_id" class="form-control" disabled>
                                    <option value="">请选择分类</option>
                                    @foreach($menucategories as $menucategory)
                                        <option value="{{$menucategory->id}}">{{$menucategory->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">菜品价格</label>
                            <div class="col-sm-10">
                                <input type="number" min="0" name="goods_price" id="sgoods_price" class="form-control" value="{{ old('goods_price') }}" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">菜品描述</label>
                            <div class="col-sm-10">
                                <textarea name="description" id="sdescription" cols="30" rows="10" class="form-control" placeholder="请输入描述" readonly></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">提示信息</label>
                            <div class="col-sm-10">
                                <textarea name="tips"  id="stips" cols="30" rows="10" class="form-control" placeholder="请输入提示信息" readonly></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">月销量</label>
                            <div class="col-sm-10">
                                <input type="number" min="0" name="" id="smonth_sales" class="form-control" value="{{ old('goods_price') }}" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">评分数量</label>
                            <div class="col-sm-10">
                                <input type="text" min="0" name="goods_price" id="srating_count" class="form-control" value="{{ old('goods_price') }}" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">满意度数量</label>
                            <div class="col-sm-10">
                                <input type="text" min="0" name="" id="ssatisfy_count" class="form-control" value="{{ old('goods_price') }}" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">	满意度评分</label>
                            <div class="col-sm-10">
                                <input type="text" min="0" name="" id="ssatisfy_rate" class="form-control" value="{{ old('goods_price') }}" readonly>
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

    <div class="modal fade" id="AddModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">添加菜品</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="{{ route('menus.store') }}" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">菜品名</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="" name="goods_name" value="{{ old('goods_name') }}" placeholder="请输入菜品名">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">菜品图片</label>
                            <div class="col-sm-10">
                                <input type="file" name="goods_img">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">菜品分类</label>
                            <div class="col-sm-10">
                                <select name="category_id" id="" class="form-control">
                                    <option value="">请选择分类</option>
                                    @foreach($menucategories as $menucategory)
                                    <option value="{{$menucategory->id}}"  @if(old('category_id')==1) selected @endif>{{$menucategory->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">菜品价格</label>
                            <div class="col-sm-10">
                                <input type="number" min="0" name="goods_price" class="form-control" value="{{ old('goods_price') }}" placeholder="请输入价格">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">菜品描述</label>
                            <div class="col-sm-10">
                                <textarea name="description" id="" cols="30" rows="10" class="form-control" placeholder="请输入描述"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">提示信息</label>
                            <div class="col-sm-10">
                                <textarea name="tips"  id="" cols="30" rows="10" class="form-control" placeholder="请输入提示信息" ></textarea>
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
                    <h4 class="modal-title" id="myModalLabel">修改菜品</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="{{ route('menus.update') }}" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="EditId" value="">
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">菜品名</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="goods_name" name="goods_name" value="{{ old('goods_name') }}" placeholder="请输入菜品名">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">菜品图片</label>
                            <div class="col-sm-10">
                                <input type="file" name="goods_img">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">菜品分类</label>
                            <div class="col-sm-10">
                                <select name="category_id" id="category_id" class="form-control">
                                    <option value="">请选择分类</option>
                                    @foreach($menucategories as $menucategory)
                                        <option value="{{$menucategory->id}}"  @if(old('category_id')==1) selected @endif>{{$menucategory->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">菜品价格</label>
                            <div class="col-sm-10">
                                <input type="number" id="goods_price" min="0" name="goods_price" class="form-control" value="{{ old('goods_price') }}" placeholder="请输入价格">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">菜品描述</label>
                            <div class="col-sm-10">
                                <textarea name="description" id="description" cols="30" rows="10" class="form-control" placeholder="请输入描述"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">提示信息</label>
                            <div class="col-sm-10">
                                <textarea name="tips"  id="tips" cols="30" rows="10" class="form-control" placeholder="请输入提示信息" ></textarea>
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
        $('#EditId').val($data['id']);
        $('#goods_name').val($data['goods_name']);
        $("#category_id option[value="+$data['category_id']+"]").attr("selected",true);
        $('#goods_price').val($data['goods_price']);
        $('#description').text($data['description']);
        $('#tips').text($data['tips']);
    }

    function getShow($data){
        $('#sgoods_name').val($data['goods_name']);
        $("#scategory_id option[value="+$data['category_id']+"]").attr("selected",true);
        $('#sgoods_price').val($data['goods_price']);
        $('#sdescription').text($data['description']);
        $('#stips').text($data['tips']);
        $('#sgoods_img').attr('src',$data['goods_img']);
        $('#srating').val($data['rating']);
        $('#smonth_sales').val($data['month_sales']);
        $('#srating_count').val($data['rating_count']);
        $('#ssatisfy_rate').val($data['satisfy_rate']);
        $('#ssatisfy_count').val($data['satisfy_count']);
    }
</script>
</body>