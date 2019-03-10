<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Member;
use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Shop;
use App\Models\User;
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
            Redis::setex($phoneNumber,300,$params[0]);
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
        $id = Auth::user()->id;
        Cart::where('user_id','=',$id)->delete();
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

    //添加订单
    public function addorder(Request $request)
    {
        $user_id = Auth::user()->id;
        if (!$user_id){
            [
                "status"=> "false",
                "message"=> "请先登录",
            ];
        }
        $address_id = $request->address_id;
        if (!$address_id){
            return [
                "status"=> "false",
                "message"=> "请选择收货地址",
            ];
        }
        $shop_id = Menu::find(Cart::where('user_id','=',$user_id)->first()->goods_id)->shop_id;
        $address = Address::find($address_id);
        $carts = Cart::where('user_id','=',$user_id)->get();
        $total = 0;
        foreach ($carts as $cart){
            $good = Menu::find($cart->goods_id);
            $total += $cart->amount * $good->goods_price;
        }
        $order_id = "";
        try {
            DB::transaction(function () use($request,$user_id,$shop_id,$address,$total,$carts,&$order_id) {
                $data_order = [
                    'user_id' => $user_id,
                    'shop_id' => $shop_id,
                    'sn' => date('Ymd').mt_rand(10000, 99999),
                    'province' => $address->province,
                    'city' => $address->city,
                    'county' => $address->county,
                    'address' => $address->address,
                    'tel' => $address->tel,
                    'name' => $address->name,
                    'total' => $total,
                    'status' => 0,
                    'out_trade_no' => uniqid(),
                ];
                $order = Order::create($data_order);

                $order_id = $order->id;
                foreach ($carts as $cart) {
                    $good = Menu::find($cart->goods_id);
                    $data_goods = [
                        'order_id' => $order_id,
                        'goods_id' => $cart->goods_id,
                        'amount' => $cart->amount,
                        'goods_name' => $good->goods_name,
                        'goods_img' => $good->goods_img,
                        'goods_price' => $good->goods_price,
                    ];
                    OrderDetail::create($data_goods);
                }
            });


            $user = User::where('shop_id','=',$shop_id)->first();
            $email = $user->email;
            $title = '用户下单通知';
            $content = '<p>	
                您有一个新的<span style="color: red">订单</span>！<br/>
                请注意查看。
               </p>';
            try{
                \Illuminate\Support\Facades\Mail::send('email.default',compact('title','content'),
                    function($message) use($email){
                        $to = $email;
                        $message->from(env('MAIL_USERNAME'))->to($to)->subject('用户下单通知');
                    });
            }catch (\Exception $e){
                return '邮件发送失败';
            }


            $appid = 1400189767; // 1400开头
            // 短信应用SDK AppKey
            $appkey = "821321ca2ecdfb2544e824961e2e1856";
            // 需要发送短信的手机号码
            $phoneNumber = Auth::user()->tel;
            //        //templateId7839对应的内容是"您的验证码是: {1}"
            // 短信模板ID，需要在短信应用中申请
            $templateId = 290652;  // NOTE: 这里的模板ID`7839`只是一个示例，真实的模板ID需要在短信控制台中申请
            $smsSign = "蒋自林的个人文字记录"; // NOTE: 这里的签名只是示例，请使用真实的已申请的签名，签名参数使用的是`签名内容`，而不是`签名ID`
            try {
                $ssender = new SmsSingleSender($appid, $appkey);
                $params = [];//数组具体的元素个数和模板中变量个数必须一致，例如事例中 templateId:5678对应一个变量，参数数组中元素个数也必须是一个
                $result = $ssender->sendWithParam("86", $phoneNumber, $templateId,
                    $params, $smsSign, "", "");  // 签名参数未提供或者为空时，会使用默认签名发送短信
                $rsp = json_decode($result);
            } catch(\Exception $e) {
                var_dump($e);
            }


            return [
                "status"=>"true",
                "message"=>"添加成功",
                "order_id"=>$order_id,
            ];
        }catch (\Exception $e){
            return [
                "status"=> "false",
                "message"=> "添加失败",
            ];
        }
    }
    
    //指定订单
    public function order(Request $request)
    {
        $order_id = $request->id;
        $order = Order::find($order_id);
        $shop = Shop::find($order->shop_id);
        $result = [];
        $result['id'] = $order->id;
        $result['shop_id'] = $order->shop_id;
        $result['order_code'] = $order->sn;
        $result['order_birth_time'] = $order->created_at->toArray()['formatted'];
        $order_status = "";
        switch ($order->status){
            case -1:
                $order_status = "已取消";
                break;
            case 0:
                $order_status = "待付款";
                break;
            case 1:
                $order_status = "待发货";
                break;
            case 2:
                $order_status = "待确认";
                break;
            case 3:
                $order_status = "完成";
                break;
        }
        $result['order_status'] = $order_status;
        $result['shop_name'] = $shop->shop_name;
        $result['shop_img'] = $shop->shop_img;
        $result['order_price'] = $order->total;
        $result['order_address'] = $order->address;
        $goods = OrderDetail::where('order_id','=',$order_id)->get();
        $result['goods_list'] = $goods;
        return $result;
    }

    //订单列表
    public function orderList()
    {

        $orders = Order::where('user_id','=',Auth::user()->id)->get();
        $res = [];
        foreach ($orders as $order){
            $shop = Shop::find($order->shop_id);
            $result = [];
            $result['id'] = $order->id;
            $result['shop_id'] = $order->shop_id;
            $result['order_code'] = $order->sn;
            $result['order_birth_time'] = $order->created_at->toArray()['formatted'];
            $order_status = "";
            switch ($order->status){
                case -1:
                    $order_status = "已取消";
                    break;
                case 0:
                    $order_status = "待付款";
                    break;
                case 1:
                    $order_status = "待发货";
                    break;
                case 2:
                    $order_status = "待确认";
                    break;
                case 3:
                    $order_status = "完成";
                    break;
            }
            $result['order_status'] = $order_status;
            $result['shop_name'] = $shop->shop_name;
            $result['shop_img'] = $shop->shop_img;
            $result['order_price'] = $order->total;
            $result['order_address'] = $order->address;
            $goods = OrderDetail::where('order_id','=',$order->id)->get();
            $result['goods_list'] = $goods;

            $res[] = $result;
        }
        return $res;
    }

    //修改密码
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'oldPassword' => 'required',
            'newPassword' => 'required',
        ]);
        if ($validator->fails()) {
            return [
                "status"=> "false",
                "message"=> implode(' ',$validator->errors()->all()),
            ];
        }
        $user = auth()->user();
        $result=Hash::check($request->oldPassword,$user->password);
        if($result){
            $user->update(
                ['password'=>Hash::make($request->newPassword)]
            );
            return [
                "status"=>"true",
                "message"=>"修改成功"
            ];
        }else{
            return [
                "status"=>"false",
                "message"=>"修改失败"
            ];
        }
    }

    //重置密码
    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tel' => 'required|numeric|digits_between:11,11',
            'sms' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return [
                "status"=> "false",
                "message"=> implode(' ',$validator->errors()->all()),
            ];
        }

        if ($request->sms == Redis::get($request->tel)) {
            Member::where('tel','=',$request->tel)->update(['password'=>Hash::make($request->password)]);
            return [
                "status"=>"true",
                "message"=>"重置成功"
            ];
        }else{
            return [
                "status"=>"false",
                "message"=>"验证码错误"
            ];
        }

    }
}
