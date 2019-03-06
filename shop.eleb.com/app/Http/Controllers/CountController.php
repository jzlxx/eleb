<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CountController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }

    //一周订单量
    public function order_week()
    {
        $datas = [];
        for ($i = 0;$i <= 6;$i++){
            $k = date('Y-m-d',strtotime("-$i day"));
            $j = Order::where('created_at','like',"$k%")->where('shop_id','=',Auth::user()->shop_id)->count();
            $datas[$k] = $j;
        }
        return view('count.order_week',['datas'=>$datas]);
    }

    //三月订单量
    public function order_month()
    {
        $datas = [];
        for ($i = 0;$i <= 2;$i++){
            $k = date('Y-m',strtotime("-$i month"));
            $j = Order::where('created_at','like',"$k%")->where('shop_id','=',Auth::user()->shop_id)->count();
            $datas[$k] = $j;
        }
        return view('count.order_week',['datas'=>$datas]);
    }

    //一周菜品销量
    public function goods_week()
    {
//        $goods = Menu::where('shop_id','=',Auth::user()->shop_id)->get();
//        $datas = [];
//        $re = [];
//        foreach ($goods as $good){
//            $res = [];
//            $r = [];
//            $datas[] = $good->goods_name;
//            for ($i = 0;$i <= 6;$i++){
//                $k = date('Y-m-d',strtotime("-$i day"));
//                $j = OrderDetail::where('created_at','like',"$k%")->where('goods_id','=',$good->id)->get();
//                $res[] = $k;
//                $num = 0;
//                foreach ($j as $a){
//                    $num += $a->amount;
//                }
//                $r[] = $num;
//            }
//            $re[] = $r;
//        }
//        $datas = array_flip($datas);
//        foreach ($datas as &$vv){
//            $vv = $re[$vv];
//        }
////        dump($datas);
//        return view('count.goods_week',['datas'=>$datas,'res'=>$res]);







        $shop_id = Auth::user()->shop_id;//Auth::user()->shop_id;
        $time_start = date('Y-m-d 00:00:00',strtotime('-6 day'));
        $time_end = date('Y-m-d 23:59:59');
        $sql = "SELECT
	DATE(orders.created_at) AS date,order_details.goods_id,
	SUM(order_details.amount) AS total
FROM
	order_details
JOIN orders ON order_details.order_id = orders.id
WHERE
	 orders.created_at >= '{$time_start}' AND orders.created_at <= '{$time_end}'
AND shop_id = {$shop_id}
GROUP BY
	DATE(orders.created_at),order_details.goods_id";

        $rows = DB::select($sql);

//        dd($rows);

        $result = [];
        //获取当前商家的菜品列表
        $menus = Menu::where('shop_id',$shop_id)->select(['id','goods_name'])->get();
        $keyed = $menus->mapWithKeys(function ($item) {
            return [$item['id'] => $item['goods_name']];
        });
        $keyed2 = $menus->mapWithKeys(function ($item) {
            return [$item['id'] => 0];
        });
        $menus = $keyed->all();
//        dd($menus);

        $week=[];
        for ($i=0;$i<7;$i++){
            $week[] = date('Y-m-d',strtotime("-{$i} day"));
        }
        foreach ($menus as $id=>$name){
            foreach ($week as $day){
                $result[$id][$day] = 0;
            }
        }
        /**/
//        dd($result);

        foreach ($rows as $row){
            $result[$row->goods_id][$row->date]=$row->total;
        }


//        dd($result);
        $series = [];
        foreach ($result as $id=>$data){
            $serie = [
                'name'=> $menus[$id],
                'type'=>'line',
                //'stack'=> '销量',
                'data'=>array_values($data)
            ];
            $series[] = $serie;
        }
//        dd($series);

        return view('count.goods_week',compact('result','menus','week','series'));
    }

    //三月菜品销量
    public function goods_month()
    {

        $shop_id = Auth::user()->shop_id;//Auth::user()->shop_id;
        $time_start = date('Y-m-d 00:00:00',strtotime('-3 month'));
        $time_end = date('Y-m-d 23:59:59');
        $sql = "SELECT
	DATE(orders.created_at) AS date,order_details.goods_id,
	SUM(order_details.amount) AS total
FROM
	order_details
JOIN orders ON order_details.order_id = orders.id
WHERE
	 orders.created_at >= '{$time_start}' AND orders.created_at <= '{$time_end}'
AND shop_id = {$shop_id}
GROUP BY
	DATE(orders.created_at),order_details.goods_id";

        $rows = DB::select($sql);

//        dd($rows);

        $result = [];
        //获取当前商家的菜品列表
        $menus = Menu::where('shop_id',$shop_id)->select(['id','goods_name'])->get();
        $keyed = $menus->mapWithKeys(function ($item) {
            return [$item['id'] => $item['goods_name']];
        });
        $keyed2 = $menus->mapWithKeys(function ($item) {
            return [$item['id'] => 0];
        });
        $menus = $keyed->all();
//        dd($menus);

        $week=[];
        for ($i=0;$i<3;$i++){
            $week[] = date('Y-m',strtotime("-{$i} month"));
        }
        foreach ($menus as $id=>$name){
            foreach ($week as $day){
                $result[$id][$day] = 0;
            }
        }
        /**/
//        dd($result);

        foreach ($rows as $row){
            $time = strtotime($row->date);
            $dater = date('Y-m',$time);
            $result[$row->goods_id][$dater]+=$row->total;
        }


//        dd($result);
        $series = [];
        foreach ($result as $id=>$data){
            $serie = [
                'name'=> $menus[$id],
                'type'=>'line',
                //'stack'=> '销量',
                'data'=>array_values($data)
            ];
            $series[] = $serie;
        }
//        dd($series);

        return view('count.goods_week',compact('result','menus','week','series'));
    }


//    public function index(Request $request){
//        $keyword = $request->keyword;
//        $shop_id =auth()->user()->shop_id;
//        if($keyword==0){
//            $time_start = date('Y-m-d 00:00:00',strtotime('-6 day'));
//            $time_end = date('Y-m-d 23:59:59');
//        }else{
//            $time_start = date('Y-m-d 00:00:00',strtotime('-3 month'));
//            $time_end = date('Y-m-d 23:59:59');
//        }
//
//        $sql = "SELECT
//	DATE(orders.created_at) AS date,order_details.goods_id,
//	SUM(order_details.amount) AS total
//FROM
//	order_details
//JOIN orders ON order_details.order_id = orders.id
//WHERE
//	 orders.created_at >= '{$time_start}' AND orders.created_at <= '{$time_end}'
//AND shop_id = {$shop_id}
//GROUP BY
//	DATE(orders.created_at),order_details.goods_id";
//        $rows = DB::select($sql);
//        //构造7天统计格式
//        $result = [];
//        //获取当前商家的菜品列表
//        $menus = Menu::where('shop_id',$shop_id)->select(['id','goods_name'])->get();
//        $keyed = $menus->mapWithKeys(function ($item) {
//            return [$item['id'] => $item['goods_name']];
//        });
////        $keyword =0;
//        $menus = $keyed->all();
//        if ($keyword==0){
//            for ($i=0;$i<7;$i++){
//                $week[] = date('Y-m-d',strtotime("-{$i} day"));
//            }
//        }else{
//            for ($i=0;$i<3;$i++){
//                $week[] = date('Y-m',strtotime("-{$i} month"));
//            }
//        }
//
//        foreach ($menus as $id=>$name){
//            foreach ($week as $day){
//                $result[$id][$day] = 0;
//            }
//        }
//
//        foreach ($rows as $row){
//            if($keyword==1){
//                $time = strtotime($row->date);
//                $dater = date('Y-m',$time);
//                $result[$row->goods_id][$dater]=$row->total;
//            }else{
//                $result[$row->goods_id][$row->date]=$row->total;
//            }
//
//        }
//
//        $series = [];
//        foreach ($result as $id=>$data){
//            $serie = [
//                'name'=> $menus[$id],
//                'type'=>'line',
//                //'stack'=> '销量',
//                'data'=>array_values($data)
//            ];
//            $series[] = $serie;
//
//        }
//
//        return view('menuCount.index',compact('keyword','result','menus','week','series'));
//    }


}
