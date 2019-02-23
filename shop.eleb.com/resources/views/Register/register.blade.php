@include('layout._boot')
<div class="container">
    <div style=";margin: 50px auto">
        @include('layout._errors')
        <form action="{{ route('register.store') }}" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-xs-5">
                    <h2>店铺信息</h2>
                    <div class="form-group">
                        <label for="exampleInputEmail1">店铺名称</label>
                        <input type="text" class="form-control" id="" name="shop_name" placeholder="请输入店铺名称" value="{{ old('shop_name') }}">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">店铺分类</label>
                        <select name="shop_category_id" id="" class="form-control">
                            <option value="">请选择店铺分类</option>
                             @foreach($shopcategories as $shopcategory)
                                 <option value="{{ $shopcategory->id }}" @if(old('shop_category_id') == $shopcategory->id) selected @endif>{{ $shopcategory->name }}</option>
                             @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">店铺图片</label>
                        <input type="file" id="" name="shop_img">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">起送金额</label>
                        <input type="text" class="form-control" id="" name="start_send" placeholder="请输入起送金额" value="{{ old('start_send') }}">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">配送费</label>
                        <input type="text" class="form-control" id="" name="send_cost" placeholder="请输入配送费" value="{{ old('send_cost') }}">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">店公告</label>
                        <textarea name="notice" id="" cols="80" rows="4" placeholder="请输入店公告">{{ old('notice') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">优惠信息</label>
                        <textarea name="discount" id="" cols="80" rows="4" placeholder="请输入优惠信息">{{ old('discount') }}</textarea>
                    </div>
                </div>
                <div class="col-xs-2">
                    <h2>特点选择</h2>
                    <div class="form-group">
                        <label for="exampleInputEmail1">是否品牌</label>
                        <div>
                            <label class="radio-inline">
                                <input type="radio" class="" id="" name="brand" value="1" @if(old('brand') == 1) checked @endif>是
                            </label>
                            <label class="radio-inline">
                                <input type="radio" class="" id="" name="brand" value="0" @if(old('brand') == 0) checked @endif>否
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">是否准时送达</label>
                        <div>
                            <label class="radio-inline">
                                <input type="radio" class="" id="" name="on_time" value="1" @if(old('on_time') == 1) checked @endif>是
                            </label>
                            <label class="radio-inline">
                                <input type="radio" class="" id="" name="on_time" value="0" @if(old('on_time') == 0) checked @endif>否
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">是否蜂鸟配送</label>
                        <div>
                            <label class="radio-inline">
                                <input type="radio" class="" id="" name="fengniao" value="1" @if(old('fengniao') == 1) checked @endif>是
                            </label>
                            <label class="radio-inline">
                                <input type="radio" class="" id="" name="fengniao" value="0" @if(old('fengniao') == 0) checked @endif>否
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">是否保标记</label>
                        <div>
                            <label class="radio-inline">
                                <input type="radio" class="" id="" name="bao" value="1" @if(old('bao') == 1) checked @endif>是
                            </label>
                            <label class="radio-inline">
                                <input type="radio" class="" id="" name="bao" value="0" @if(old('bao') == 0) checked @endif>否
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">是否票标记</label>
                        <div>
                            <label class="radio-inline">
                                <input type="radio" class="" id="" name="piao" value="1" @if(old('piao') == 1) checked @endif>是
                            </label>
                            <label class="radio-inline">
                                <input type="radio" class="" id="" name="piao" value="0" @if(old('piao') == 0) checked @endif>否
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">是否准标记</label>
                        <div>
                            <label class="radio-inline">
                                <input type="radio" class="" id="" name="zhun" value="1" @if(old('zhun') == 1) checked @endif>是
                            </label>
                            <label class="radio-inline">
                                <input type="radio" class="" id="" name="zhun" value="0" @if(old('zhun') == 0) checked @endif>否
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-xs-5">
                    <h2>账号信息</h2>
                    <div class="form-group">
                        <label for="exampleInputEmail1">用户名</label>
                        <input type="text" class="form-control" id="" name="name" placeholder="请输入用户名" value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">邮箱</label>
                        <input type="email" class="form-control" id="" name="email" placeholder="请输入邮箱" value="{{ old('email') }}">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">密码</label>
                        <input type="password" class="form-control" id="" name="password" placeholder="请输入密码">
                    </div>
                    {{ csrf_field() }}
                    <div>
                        <button type="submit" class="btn btn-default">注册</button>
                        <a href="{{ route('login') }}" class="btn btn-default">已有账号？前往登录</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
