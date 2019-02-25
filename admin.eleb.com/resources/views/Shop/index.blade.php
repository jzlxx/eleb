@include('layout._header')
@include('layout._boot')
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
        <form class="layui-form layui-col-md12 x-so" method="post" action="{{ route('shopcategories.index') }}">
            {{--<input class="layui-input" placeholder="开始日" name="start" id="start">--}}
            {{--<input class="layui-input" placeholder="截止日" name="end" id="end">--}}
            <input type="text" name="username"  placeholder="请输入商铺名" autocomplete="off" class="layui-input">
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
            <th>商铺名</th>
            <th>图片</th>
            <th>分类</th>
            <th>状态</th>
            <th>操作</th>
        </thead>
        <tbody>
        @foreach($shops as $shop)
        <tr>
            <td>{{ $shop->id }}</td>
            <td>{{ $shop->shop_name }}</td>
            <td><img src="{{ $shop->shop_img  }}" alt=""></td>
            <td>{{ $shop->ShopCategory->name }}</td>
            <td class="td-status">
                @if($shop->status == 0)
                    待审核
                    @else
                    已通过
                @endif
            </td>

            <td class="td-manage">
                <a class="btn btn-info" href="#" role="button" data-toggle="modal" data-target="#ShowModel" onclick="getShow({{ $shop }})">查看</a>
                <a class="btn btn-warning" href="#" role="button" data-toggle="modal" data-target="#EditModel" onclick="getEdit({{ $shop }})">修改</a>
                <a class="btn btn-warning" href="{{ route('shops.ustatus',[$shop]) }}" role="button" >
                    @if($shop->status == 0)
                        通过
                        @else
                        封停
                    @endif
                </a>
                <form action="{{ route('shops.destroy',[$shop]) }}" method="post" style="display: inline">
                    {{ csrf_field() }}
                    {{ method_field('delete') }}
                    <input type="submit" value="删除" class="btn btn-danger">
                </form>
            </td>
        </tr>
            @endforeach
        </tbody>
    </table>
    {{ $shops->appends(['keyword'=>$keyword])->links() }}

    <div class="modal fade" id="ShowModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
                    <h4 class="modal-title" id="myModalLabel">商铺详情</h4>
                </div>
                <div class="modal-body">
                    <div style=";margin: 50px 50px">
                        <form>
                        <div class="row">
                            <h2>店铺信息</h2>
                            <div class="form-group">
                                <label for="exampleInputEmail1">店铺名称</label>
                                <input type="text" class="form-control" id="shop_name1" name="shop_name" placeholder="请输入店铺名称" value="{{ old('shop_name') }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">店铺分类</label>
                                <select name="shop_category_id" id="shop_category_id1" class="form-control" disabled>
                                    <option value="">请选择店铺分类</option>
                                    @foreach($shopcategories as $shopcategory)
                                        <option value="{{ $shopcategory->id }}" @if(old('shop_category_id') == $shopcategory->id) selected @endif>{{ $shopcategory->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">店铺图片</label>
                                <img src="" alt="" id="shop_img1" style="width: 200px">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">起送金额</label>
                                <input type="text" class="form-control" id="start_send1" name="start_send" placeholder="请输入起送金额" value="{{ old('start_send') }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">配送费</label>
                                <input type="text" class="form-control" id="send_cost1" name="send_cost" placeholder="请输入配送费" value="{{ old('send_cost') }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">店公告</label>
                                <textarea name="notice" id="notice1" cols="67" rows="4" placeholder="请输入店公告" readonly>{{ old('notice') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">优惠信息</label>
                                <textarea name="discount" id="discount1" cols="67" rows="4" placeholder="请输入优惠信息" readonly>{{ old('discount') }}</textarea>
                            </div>
                            <h2>特点选择</h2>
                            <div class="form-group">
                                <label for="exampleInputEmail1">是否品牌</label>
                                <div>
                                    <label class="radio-inline">
                                        <input type="radio" class="" id="brand1" name="brand" value="1" disabled>是
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" class="" id="brand1" name="brand" value="0" disabled checked>否
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">是否准时送达</label>
                                <div>
                                    <label class="radio-inline">
                                        <input type="radio" class="" id="on_time1" name="on_time" value="1" disabled>是
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" class="" id="on_time1" name="on_time" value="0" disabled checked>否
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">是否蜂鸟配送</label>
                                <div>
                                    <label class="radio-inline">
                                        <input type="radio" class="" id="fengniao1" name="fengniao" value="1" disabled>是
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" class="" id="fengniao1" name="fengniao" value="0" disabled checked>否
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">是否保标记</label>
                                <div>
                                    <label class="radio-inline">
                                        <input type="radio" class="" id="bao1" name="bao" value="1" disabled>是
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" class="" id="bao1" name="bao" value="0" disabled checked>否
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">是否票标记</label>
                                <div>
                                    <label class="radio-inline">
                                        <input type="radio" class="" id="piao1" name="piao" value="1" disabled>是
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" class="" id="piao1" name="piao" value="0" disabled checked>否
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">是否准标记</label>
                                <div>
                                    <label class="radio-inline">
                                        <input type="radio" class="" id="zhun1" name="zhun" value="1" disabled>是
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" class="" id="zhun1" name="zhun" value="0" disabled checked>否
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="layui-btn layui-btn-small btn btn-default" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">关闭</a>
                    {{--<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>--}}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="EditModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
                    <h4 class="modal-title" id="myModalLabel">修改店铺信息</h4>
                </div>
                <div class="modal-body">
                        <div style=";margin: 50px 50px">
                            <form action="{{ route('shops.update') }}" method="post" enctype="multipart/form-data">

                                <input type="hidden" value="" id="EditId" name="id">
                                <div class="row">
                                    {{--<div class="col-xs-5">--}}
                                        <h2>店铺信息</h2>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">店铺名称</label>
                                            <input type="text" class="form-control" id="shop_name" name="shop_name" placeholder="请输入店铺名称" value="{{ old('shop_name') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">店铺分类</label>
                                            <select name="shop_category_id" id="shop_category_id" class="form-control">
                                                <option value="">请选择店铺分类</option>
                                                @foreach($shopcategories as $shopcategory)
                                                    <option value="{{ $shopcategory->id }}" @if(old('shop_category_id') == $shopcategory->id) selected @endif>{{ $shopcategory->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <style>
                                            #filePicker div:nth-child(2){width:100%!important;height:100%!important;}
                                        </style>
                                        <div class="form-group">
                                            <label for="exampleInputFile">店铺图片</label>
                                            <input type="hidden" id="img_path" name="img_path">
                                            <img src="" alt="" id="img" style="width: 200px;">
                                            <div id="uploader-demo">
                                                <!--用来存放item-->
                                                {{--<div id="fileList" class="uploader-list"></div>--}}
                                                <div id="filePicker">选择图片</div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">起送金额</label>
                                            <input type="text" class="form-control" id="start_send" name="start_send" placeholder="请输入起送金额" value="{{ old('start_send') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">配送费</label>
                                            <input type="text" class="form-control" id="send_cost" name="send_cost" placeholder="请输入配送费" value="{{ old('send_cost') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">店公告</label>
                                            <textarea name="notice" id="notice" cols="67" rows="4" placeholder="请输入店公告">{{ old('notice') }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">优惠信息</label>
                                            <textarea name="discount" id="discount" cols="67" rows="4" placeholder="请输入优惠信息">{{ old('discount') }}</textarea>
                                        </div>
                                    {{--</div>--}}
                                    {{--<div class="col-xs-2">--}}
                                        <h2>特点选择</h2>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">是否品牌</label>
                                            <div>
                                                <label class="radio-inline">
                                                    <input type="radio" class="" id="brand" name="brand" value="1">是
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" class="" id="brand" name="brand" value="0" checked>否
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">是否准时送达</label>
                                            <div>
                                                <label class="radio-inline">
                                                    <input type="radio" class="" id="on_time" name="on_time" value="1">是
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" class="" id="on_time" name="on_time" value="0" checked>否
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">是否蜂鸟配送</label>
                                            <div>
                                                <label class="radio-inline">
                                                    <input type="radio" class="" id="fengniao" name="fengniao" value="1">是
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" class="" id="fengniao" name="fengniao" value="0" checked>否
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">是否保标记</label>
                                            <div>
                                                <label class="radio-inline">
                                                    <input type="radio" class="" id="bao" name="bao" value="1">是
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" class="" id="bao" name="bao" value="0" checked>否
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">是否票标记</label>
                                            <div>
                                                <label class="radio-inline">
                                                    <input type="radio" class="" id="piao" name="piao" value="1">是
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" class="" id="piao" name="piao" value="0" checked>否
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">是否准标记</label>
                                            <div>
                                                <label class="radio-inline">
                                                    <input type="radio" class="" id="zhun" name="zhun" value="1">是
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" class="" id="zhun" name="zhun" value="0" checked>否
                                                </label>
                                            </div>
                                        </div>
                                    {{--</div>--}}
                                    {{--<div class="col-xs-5">--}}
                                        {{--<h2>账号信息</h2>--}}
                                        {{--<div class="form-group">--}}
                                            {{--<label for="exampleInputEmail1">用户名</label>--}}
                                            {{--<input type="text" class="form-control" id="" name="name" placeholder="请输入用户名" value="{{ old('name') }}">--}}
                                        {{--</div>--}}
                                        {{--<div class="form-group">--}}
                                            {{--<label for="exampleInputEmail1">邮箱</label>--}}
                                            {{--<input type="email" class="form-control" id="" name="email" placeholder="请输入邮箱" value="{{ old('email') }}">--}}
                                        {{--</div>--}}
                                        {{--<div class="form-group">--}}
                                            {{--<label for="exampleInputEmail1">密码</label>--}}
                                            {{--<input type="password" class="form-control" id="" name="password" placeholder="请输入密码">--}}
                                        {{--</div>--}}
                                        {{ csrf_field() }}
                                        <div>
                                            <button type="submit" class="btn btn-default">提交</button>
                                        </div>
                                    {{--</div>--}}
                                </div>
                            </form>
                        </div>
                </div>
                <div class="modal-footer">
                    <a class="layui-btn layui-btn-small btn btn-default" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">关闭</a>
                    {{--<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>--}}
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function getEdit($data){
        $('#EditId').val($data['id']);
        $('#shop_name').val($data['shop_name']);
        $("#shop_category_id option[value="+$data['shop_category_id']+"]").attr("selected", true);
        $('#start_send').val($data['start_send'])
        $('#send_cost').val($data['send_cost'])
        $('#notice').text($data['notice'])
        $('#discount').text($data['discount'])
        $("#brand[value="+$data['brand']+"]").attr("checked",true);
        $("#on_time[value="+$data['on_time']+"]").attr("checked",true);
        $("#fengniao[value="+$data['fengniao']+"]").attr("checked",true);
        $("#bao[value="+$data['bao']+"]").attr("checked",true);
        $("#piao[value="+$data['piao']+"]").attr("checked",true);
        $("#zhun[value="+$data['zhun']+"]").attr("checked",true);
    }

    function getShow($data){
        $('#shop_name1').val($data['shop_name']);
        $("#shop_category_id1 option[value="+$data['shop_category_id']+"]").attr("selected", true);
        $('#start_send1').val($data['start_send'])
        $('#send_cost1').val($data['send_cost'])
        $('#notice1').text($data['notice'])
        $('#discount1').text($data['discount'])
        $("#brand1[value="+$data['brand']+"]").attr("checked",true);
        $("#on_time1[value="+$data['on_time']+"]").attr("checked",true);
        $("#fengniao1[value="+$data['fengniao']+"]").attr("checked",true);
        $("#bao1[value="+$data['bao']+"]").attr("checked",true);
        $("#piao1[value="+$data['piao']+"]").attr("checked",true);
        $("#zhun1[value="+$data['zhun']+"]").attr("checked",true);
        $('#shop_img1').attr('src',$data['shop_img'])
    }
</script>
<script>
    $('#EditModel').on('hidden.bs.modal', function (e) {
        window.location.reload();
    })
</script>
<script>
    var uploader = WebUploader.create({

        // 选完文件后，是否自动上传。
        auto: true,

        // swf文件路径
        // swf: BASE_URL + '/js/Uploader.swf',

        // 文件接收服务端。
        server: '/shops/upload',

        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: '#filePicker',

        // 只允许选择图片文件。
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/*'
        },

        formData:{
            _token:'{{ csrf_token() }}'
        },

    });
    uploader.on('uploadSuccess',function (file,response) {
        $('#img').attr('src',response.path);
        $('#img_path').val(response.path);
    });
</script>
</body>