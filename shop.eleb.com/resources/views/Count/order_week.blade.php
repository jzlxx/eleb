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
        @foreach($datas as $key=>$val)
                <th>{{ $key }}</th>
            @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
            @foreach($datas as $key=>$val)
                <td>{{ $val }}</td>
                @endforeach
            </tr>
        </tbody>
    </table>
    <div id="main" style="width: 1000px;height:400px;"></div>
    <script type="text/javascript">
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('main'));

        // 指定图表的配置项和数据
        var option = {
            color: ['#ff7bf2'],
            tooltip : {
                trigger: 'axis',
                axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                    type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                }
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis : [
                {
                    type : 'category',
                    data : {!! json_encode(array_keys($datas)) !!},
                    axisTick: {
                        alignWithLabel: true
                    }
                }
            ],
            yAxis : [
                {
                    type : 'value'
                }
            ],
            series : [
                {
                    name:'直接访问',
                    type:'bar',
                    barWidth: '60%',
                    data:{!! json_encode(array_values($datas)) !!}
                }
            ]
        };

        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
    </script>
</body>