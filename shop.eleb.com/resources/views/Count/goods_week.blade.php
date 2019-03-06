@include('layout._header')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<!--引入CSS-->
<link rel="stylesheet" type="text/css" href="/webuploader/webuploader.css">
<!--引入JS-->
<script type="text/javascript" src="/webuploader/webuploader.js"></script>
<script src="https://cdn.bootcss.com/echarts/4.1.0-release/echarts.min.js"></script>

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
    </div>
    @include('layout._notice')
    @include('layout._errors')
    <xblock>
    </xblock>
    <table class="layui-table">
        <thead>
        <tr>
            <th>商品名称</th>
            @foreach($week as $key=>$value)
                <th>{{$value}}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($menus as $key=>$value)
            @foreach($result as $kk=>$vv)
                <tr>
                    @if($key==$kk)
                        <th>{{$value}}</th>
                        @foreach($vv as $vvv)
                            <th>{{$vvv}}</th>
                        @endforeach
                    @endif
                </tr>
            @endforeach
        @endforeach
        </tbody>
    </table>

    <div id="main" style="width: 1000px;height:400px;"></div>
    <script type="text/javascript">
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('main'));
        // 指定图表的配置项和数据
        option = {
            title: {
                text: '折线图堆叠'
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data:{!! json_encode(array_values($menus)) !!}
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            toolbox: {
                feature: {
                    saveAsImage: {}
                }
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                data: {!! json_encode($week) !!}
            },
            yAxis: {
                type: 'value'
            },
            series:{!! json_encode($series) !!}
        };


        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
    </script>
</body>