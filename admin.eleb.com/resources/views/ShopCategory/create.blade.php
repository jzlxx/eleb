<!DOCTYPE html>
<html>
  
  <head>
    <meta charset="UTF-8">
    <title>欢迎页面-X-admin2.0</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
      <link rel="shortcut icon" href={{ URL::asset("/favicon.ico")}} type="image/x-icon" />
      <link rel="stylesheet" href="{{ URL::asset('css/font.css') }}">
      <link rel="stylesheet" href="{{ URL::asset('css/xadmin.css') }}">
      <script type="text/javascript" src="{{ URL::asset('https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js') }}"></script>
      <script type="text/javascript" src="{{ URL::asset('lib/layui/layui.js') }}"></script>
      <script type="text/javascript" src="{{ URL::asset('js/xadmin.js') }}"></script>
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
      <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
      <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  
  <body>
    <div class="x-body">
        <form class="layui-form" action="{{ route('shopcategories.store') }}" enctype="multipart/form-data" method="post">
          <div class="layui-form-item">
              <label for="username" class="layui-form-label">
                  <span class="x-red">*</span>分类名
              </label>
              <div class="layui-input-inline">
                  <input type="text" id="name" name="name"
                  autocomplete="off" class="layui-input">
              </div>
          </div>
            <div class="layui-form-item">
                <label for="username" class="layui-form-label">
                    <span class="x-red">*</span>图片
                </label>
                <div class="layui-input-inline">
                    <input type="file" id="img" name="img"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
          <div class="layui-form-item">
              <label for="L_repass" class="layui-form-label">
              </label>
              <input type="submit" class="layui-btn"  value="提交">

          </div>
      </form>
    </div>
    <script>

    </script>
  </body>

</html>