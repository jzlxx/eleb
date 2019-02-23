@include('layout._boot')
<div style="margin-top: 50px"></div>
<div class="container" style="width: 500px">
    <h1>修改密码</h1>
    @include('layout._errors')
    @include('layout._notice')
    <form method="post" action="{{ route('admins.pupdate') }}" enctype="multipart/form-data">
        <div class="form-group">
            <label>旧密码</label>
            <input type="password" name="oldpassword" class="form-control" placeholder="请输入原密码">
        </div>
        <div class="form-group">
            <label>新密码</label>
            <input type="password" name="password" class="form-control" placeholder="请输入新密码">
        </div>
        <div class="form-group">
            <label>新密码</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="请确认密码">
        </div>
        {{ csrf_field() }}
        <button type="submit" class="btn btn-primary">提交</button>
        <a href="{{ route('welcome.index') }}" class="btn btn-default">返回</a>
    </form>
</div>