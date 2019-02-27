<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    //商家列表
    public function businessList(Request $request)
    {

        $keyword = $request->keyword ?? "";
        if ($keyword){
            $shops = Shop::where('shop_name','like',"%$keyword%")->get();
        }else{
            $shops = Shop::all();
        }
        return $shops;
    }
    //指定商家
    public function business(Request $request)
    {
        $id = $request->id;
        $shop = Shop::find($id);
        $menu_categories = MenuCategory::where('shop_id','=',$id)->get();
        $evaluate = [
            [
                "user_id"=> 12344,
                "username"=> "w******k",
                "user_img"=> "/images/slider-pic4.jpeg",
                "time"=> "2017-2-22",
                "evaluate_code"=> 1,
                "send_time"=> 30,
                "evaluate_details"=> "不怎么好吃"
            ],
            [
                "user_id"=> 12344,
                "username"=> "w******k",
                "user_img"=> "/images/slider-pic4.jpeg",
                "time"=> "2017-2-22",
                "evaluate_code"=> 4.5,
                "send_time"=> 30,
                "evaluate_details"=> "很好吃"
            ],
            [
                "user_id"=> 12344,
                "username"=> "w******k",
                "user_img"=> "/images/slider-pic4.jpeg",
                "time"=> "2017-2-22",
                "evaluate_code"=> 5,
                "send_time"=> 30,
                "evaluate_details"=> "很好吃"
            ],
            [
                "user_id"=> 12344,
                "username"=> "w******k",
                "user_img"=> "/images/slider-pic4.jpeg",
                "time"=> "2017-2-22",
                "evaluate_code"=> 4.7,
                "send_time"=> 30,
                "evaluate_details"=> "很好吃"
            ],
        ];
        foreach ($menu_categories as $mc){
            $mc['goods_list'] = Menu::where('category_id','=',$mc->id)->get();
        }
        $shop['commodity'] = $menu_categories;
        $shop['evaluate'] = $evaluate;
        return $shop;
    }
}
