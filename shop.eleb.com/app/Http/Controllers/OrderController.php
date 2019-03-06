<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function index()
    {
        $orders = Order::where('shop_id','=',Auth::user()->shop_id)->paginate(3);
        return view('order.index',['orders'=>$orders]);

    }

    //取消订单
    public function ustatus(Order $order)
    {
        $order->status = -1;
        $order->save();
        return redirect()->route('orders.index')->with('success','取消订单成功');
    }

    //发货
    public function fstatus(Order $order)
    {
        $order->status = 2;
        $order->save();
        return redirect()->route('orders.index')->with('success','发货成功');
    }
}
