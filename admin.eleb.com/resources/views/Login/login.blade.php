@include('layout._boot')
<div style="margin-top: 50px"></div>
<div class="container" style="width: 500px">
    <h1>管理员登录</h1>
    @include('layout._errors')
    @include('layout._notice')
    <form method="post" action="{{ route('login') }}" enctype="multipart/form-data">
        <div class="form-group">
            <label>用户名称</label>
            <input type="text" name="name" class="form-control" placeholder="请输入用户名">
        </div>
        <div class="form-group">
            <label>用户密码</label>
            <input type="password" name="password" class="form-control" placeholder="请输入密码">
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" value="1" name="rememberMe"> 记住账号
            </label>
        </div>
        <div class="form-group">
            <label>验证码</label>
            <input type="text" name="captcha" class="form-control" placeholder="在此输入验证码">
            <br>
            <img class="thumbnail captcha" width="470px" height="50px" src="{{ captcha_src('flat') }}" onclick="this.src='/captcha/flat?'+Math.random()" title="点击图片重新获取验证码">
        </div>
        {{ csrf_field() }}
        <button type="submit" class="btn btn-primary">点击登录</button>
    </form>
</div>