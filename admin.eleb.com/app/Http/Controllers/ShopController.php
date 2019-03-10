<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\ShopCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:商家管理']);
    }

    public function index(Request $request)
    {
        $shopcategories = ShopCategory::all();
        $keyword = $request->keyword;
        if ($keyword){
            $shops = Shop::where('name','like',"%$keyword%")->paginate(3);
        }else{
            $shops = Shop::paginate(3);
        }
        return view('shop.index',['shops'=>$shops,'shopcategories'=>$shopcategories,'keyword'=>$keyword]);
    }

    public function update(Request $request)
    {
        $this->validate($request,
            [
                'shop_category_id'=>'required|integer',
                'shop_name'=>'required',
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
            ],
            [
                'shop_category_id.required'=>'请输入商家分类id',
                'shop_category_id.integer'=>'商家分类id只能是整数',
                'shop_name.required'=>'请输入商家名称',
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
            ]
        );
        $id = $request->id;
        $shop = Shop::find($id);
        $img = $request->img_path;
        if ($img!=null){
            $path = $img;
        }else{
            $path = $shop->shop_img;
        }
        $data_shop = [
            'shop_category_id'=>$request->shop_category_id,
            'shop_name'=>$request->shop_name,
            'shop_img'=>$path,
            'brand'=>$request->brand,
            'on_time'=>$request->on_time,
            'fengniao'=>$request->fengniao,
            'bao'=>$request->bao,
            'piao'=>$request->piao,
            'zhun'=>$request->zhun,
            'start_send'=>$request->start_send,
            'send_cost'=>$request->send_cost,
            'notice'=>$request->notice,
            'discount'=>$request->discount,
            'shop_rating'=>$shop->shop_rating,
        ];
        $shop->update($data_shop);
        return redirect()->route('shops.index')->with('success','修改商铺信息成功');
    }

    public function ustatus(Shop $shop)
    {
        if($shop->status == 0){
            $shop->status = 1;

            $id = $shop->id;
            $user = User::where('shop_id','=',$id)->first();
            $email = $user->email;

            $title = '商家注册审核通知';
            $content = '<p>	
                亲，您注册的商家已经<span style="color: red">通过</span>审核！<br/>
                请注意查看。
               </p>';
            try{
                \Illuminate\Support\Facades\Mail::send('email.default',compact('title','content'),
                    function($message) use($email){
                        $to = $email;
                        $message->from(env('MAIL_USERNAME'))->to($to)->subject('商家注册审核通知');
                    });
            }catch (\Exception $e){
                return '邮件发送失败';
            }
        }else{
            $shop->status = 0;
        }
        $shop->save();
        return redirect()->route('shops.index')->with('success','修改状态成功');
    }

    public function destroy(Shop $shop)
    {
        $id = $shop->id;
        DB::delete("delete from users where shop_id = $id");
        $shop->delete();
        return redirect()->route('shops.index')->with('success','删除商家成功');
    }

    public function upload(Request $request)
    {
        $img = $request->file('file');
        //保存文件
        $path = Storage::url($img->store('public/shop'));
        return (['path'=>$path]);
    }
}
