<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Member;
use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Qcloud\Sms\SmsSingleSender;

class ApiController extends Controller
{
    //商家列表
    public function businessList(Request $request)
    {

        $keyword = $request->keyword;
        if ($keyword){
            $shops = Shop::where('status','=',1)->where('shop_name','like',"%$keyword%")->get();
        }else{
            $shops = Shop::where('status','=',1)->get();
        }
        return $shops;
    }

    //获取指定商家
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
            $menus = Menu::where('category_id','=',$mc->id)->get();
            $mc['goods_list'] = $menus;
            foreach ($mc['goods_list'] as $menu){
                $menu['goods_id'] = $menu->id;
            }
        }
        $shop['commodity'] = $menu_categories;
        $shop['evaluate'] = $evaluate;
        return $shop;
    }

    //注册
    public function regist(Request $request)
    {
        $member = Member::where('tel','=',$request->tel)->get();
        if(count($member)){
            $data =  [
                "status"=> "false",
                "message"=> "号码已注册",
            ];
            return $data;
        }
        if ($request->sms == Redis::get($request->tel)) {
            $data = [
                'username' => $request->username,
                'tel' => $request->tel,
                'password' => Hash::make($request->password),
                'rememberToken' => uniqid(),
            ];
            Member::create($data);
            $res = [
                "status" => "true",
                "message" => "注册成功"
            ];
            return $res;
        }else{
            $res = [
                "status" => "false",
                "message" => "验证码错误"
            ];
            return $res;
        }
    }

    //短信验证
    public function sms(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tel' => 'required|numeric|digits_between:11,11',
        ]);

        if ($validator->fails()) {
            return [
                "status"=> "false",
                "message"=> implode(' ',$validator->errors()->all()),//$validator->errors()->first('tel')
            ];
        }

        $appid = 1400189767; // 1400开头

        // 短信应用SDK AppKey
        $appkey = "821321ca2ecdfb2544e824961e2e1856";

        // 需要发送短信的手机号码
        $phoneNumber = $request->tel;
        //        //templateId7839对应的内容是"您的验证码是: {1}"
        // 短信模板ID，需要在短信应用中申请
        $templateId = 285147;  // NOTE: 这里的模板ID`7839`只是一个示例，真实的模板ID需要在短信控制台中申请

        $smsSign = "蒋自林的个人文字记录"; // NOTE: 这里的签名只是示例，请使用真实的已申请的签名，签名参数使用的是`签名内容`，而不是`签名ID`

        try {
            $ssender = new SmsSingleSender($appid, $appkey);
            $params = [rand(1000,9999),5];//数组具体的元素个数和模板中变量个数必须一致，例如事例中 templateId:5678对应一个变量，参数数组中元素个数也必须是一个
            $result = $ssender->sendWithParam("86", $phoneNumber, $templateId,
                $params, $smsSign, "", "");  // 签名参数未提供或者为空时，会使用默认签名发送短信
            $rsp = json_decode($result);
        } catch(\Exception $e) {
            var_dump($e);
        }

        if ($rsp->errmsg == 'OK'){
            Redis::setex($phoneNumber,20,$params[0]);
            $res = [
                "status"=> "true",
                "message"=> "获取短信验证码成功"
            ];
            return $res;
        }else{
            $res = [
                "status"=> "false",
                "message"=> "获取短信验证码失败"
            ];
            return $res;
        }
    }

    //登录
    public function loginCheck(Request $request)
    {
        if (Auth::attempt([
            'username'=>$request->name,
            'password'=>$request->password,
        ])){
            $result = [
                "status"=>"true",
                "message"=>"登录成功",
                "user_id"=>Auth::user()->id,
                "username"=>Auth::user()->username,
            ];
        }else{
            $result = [
                "status"=>"false",
                "message"=>"登录失败",
            ];
        }
        return $result;
    }

    //新增地址
    public function addAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'tel' => 'required|numeric|digits_between:11,11',
            'provence' => 'required',
            'city' => 'required',
            'area' => 'required',
            'detail_address' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                "status"=> "false",
                "message"=> implode(' ',$validator->errors()->all()),//$validator->errors()->first('tel')
            ];
        }
        $data = [
            'user_id'=>Auth::user()->id,
            'province'=>$request->provence,
            'city'=>$request->city,
            'county'=>$request->area,
            'address'=>$request->detail_address,
            'tel'=>$request->tel,
            'name'=>$request->name,
        ];
        Address::create($data);
        return [
            "status"=> "true",
            "message"=> "添加成功"
        ];
    }
    
    //地址列表
    public function addressList()
    {
        $addresses = Address::where('user_id','=',Auth::user()->id)->get();
        foreach ($addresses as $address){
            $address['detail_address'] = $address->address;
            $address['provence'] = $address->province;
            $address['area'] = $address->county;
        }

        return $addresses;
    }

    //指定地址
    public function address(Request $request)
    {
        $address = Address::find($request->id);
        $address['detail_address'] = $address->address;
        $address['provence'] = $address->province;
        $address['area'] = $address->county;

        return $address;
    }

    //保存修改地址
    public function editAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'tel' => 'required|numeric|digits_between:11,11',
            'provence' => 'required',
            'city' => 'required',
            'area' => 'required',
            'detail_address' => 'required',
        ]);
        if ($validator->fails()) {
            return [
                "status"=> "false",
                "message"=> implode(' ',$validator->errors()->all()),//$validator->errors()->first('tel')
            ];
        }
        $address = Address::find($request->id);
        $data = [
            'province'=>$request->provence,
            'city'=>$request->city,
            'county'=>$request->area,
            'address'=>$request->detail_address,
            'tel'=>$request->tel,
            'name'=>$request->name,
        ];
        $address->update($data);

        return [
            "status"=> "true",
            "message"=> "修改成功"
        ];
    }
    
    //保存购物车
    public function addCart(Request $request)
    {
        $goodslist = $request->goodsList;
        $goodscount = $request->goodsCount;
        for($i = 0;$i < count($goodslist);$i++){
            $data = [
                'user_id'=>Auth::user()->id,
                'goods_id'=>$goodslist[$i],
                'amount'=>$goodscount[$i],
            ];
            Cart::create($data);
        }
        return [
            "status"=> "true",
            "message"=> "添加成功"
        ];
    }

    //获取购物车
    public function cart()
    {
        $id = Auth::user()->id;
        $carts = Cart::where('user_id','=',$id)->get();
        $totalCost = 0;
        foreach ($carts as $cart){
            $good= Menu::find($cart->goods_id);
            $cart['goods_name'] = $good->goods_name;
            $cart['goods_img'] = $good->goods_img;
            $cart['goods_price'] = $good->goods_price;
            $totalCost += $cart->amount * $cart->goods_price;
        }
        $result['goods_list'] = $carts;
        $result['totalCost'] = $totalCost;
        return $result;
    }
}
