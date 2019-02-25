<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\ShopCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    //
    public function register()
    {
        $shopcategories = ShopCategory::all();
        return view('register.register',['shopcategories'=>$shopcategories]);
    }

    public function store(Request $request)
    {
        $this->validate($request,
            [
                'shop_category_id'=>'required|integer',
                'shop_name'=>'required',
                'img_path'=>'required',
                'brand'=>'required',
                'on_time'=>'required',
                'fengniao'=>'required',
                'bao'=>'required',
                'piao'=>'required',
                'zhun'=>'required',
                'start_send'=>'required',
                'send_cost'=>'required',
                'notice'=>'required',
                'discount'=>'required',
                'name'=>'required',
                'email'=>'required',
                'password'=>'required',
            ],
            [
                'shop_category_id.required'=>'请输入商家分类id',
                'shop_category_id.integer'=>'商家分类id只能是整数',
                'shop_name.required'=>'请输入商家名称',
                'img_path.required'=>'请上传图片',
                'brand.required'=>'请选择是否是品牌',
                'fengniao.required'=>'请选择是否蜂鸟配送',
                'on_time.required'=>'请选择是否准时达',
                'zhun.required'=>'请选择是否准标记',
                'bao.required'=>'请选择是否保标记',
                'piao.required'=>'请选择是否票标记',
                'start_send.required'=>'请输入起送金额',
                'send_cost.required'=>'请输入配送金额',
                'notice.required'=>'请输入店公告',
                'discount.required'=>'请输入优惠信息',
                'name.required'=>'请输入用户名',
                'email.required'=>'请输入邮箱',
                'password.required'=>'请输入密码',
            ]
        );
        try{
            DB::transaction(function () use($request){
                $data_shop = [
                    'shop_category_id' => $request->shop_category_id,
                    'shop_name' => $request->shop_name,
                    'shop_img' => $request->img_path,
                    'brand' => $request->brand,
                    'on_time' => $request->on_time,
                    'fengniao' => $request->fengniao,
                    'bao' => $request->bao,
                    'piao' => $request->piao,
                    'zhun' => $request->zhun,
                    'start_send' => $request->start_send,
                    'send_cost' => $request->send_cost,
                    'notice' => $request->notice,
                    'discount' => $request->discount,
                    'shop_rating' => rand(0, 5),
                ];
                $shop = Shop::create($data_shop);
                $shop_id = $shop->id;

                $data_user = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'shop_id' => $shop_id,
                    'remember_token' => uniqid(),
                ];
                $user = User::create($data_user);
            });
            return redirect()->route('login')->with('success','注册成功');
        }catch (\Exception $e){
            return back()->withErrors(['注册失败'])->withInput();
        }
    }

    public function upload(Request $request)
    {
        $img = $request->file('file');
        //保存文件
        $path = Storage::url($img->store('public/shop'));
        return (['path'=>$path]);
    }
}
