<?php

namespace App\Http\Controllers;


use App\Models\Menu;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        //全部菜品
        $rows = Menu::where('shop_id','=',Auth::user()->shop_id);

        //分类
        if($request->category_id){
            $rows->where('category_id','=',$request->category_id);
        }
        //名称
        if($request->keyword){
            $rows->where('goods_name','like',"%{$request->keyword}%");
        }
        //最小价格
        if($request->start){
            $rows->where('goods_price','>=',$request->start);
        }
        //最大价格
        if($request->end){
            $rows->where('goods_price','<=',$request->end);
        }
        $menus = $rows->paginate(3);
        //菜品分类列表
        $menucategories = MenuCategory::all();
        return view('menu.index',['menus'=>$menus,'menucategories'=>$menucategories,'keyword'=>$request->keyword,'category_id'=>$request->category_id,'start'=>$request->start,'end'=>$request->end]);
    }

    public function create()
    {
        return view('shopcategory.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'goods_name'=>'required',
            'goods_price'=>'required|numeric',
            'description'=>'required',
            'tips'=>'required',
            'img_path'=>'required',
            'category_id'=>'required',
        ],[
            'goods_name.required'=>'菜品名不能为空',
            'goods_price.required'=>'价格不能为空',
            'goods_price.numeric'=>'价格只能为整数',
            'description.required'=>'描述不能为空',
            'tips.required'=>'提示不能为空',
            'category_id.required'=>'分类不能为空',
            'img_path.required'=>'图片不能为空',
        ]);
//        $img = $request->file('goods_img');
//        //保存文件
//        $path = url(Storage::url($img->store('public/goods')));
        $data = [
            'goods_name'=>$request->goods_name,
            'goods_price'=>$request->goods_price,
            'description'=>$request->description,
            'tips'=>$request->tips,
            'goods_img'=>$request->img_path,
            'category_id'=>$request->category_id,
            'shop_id'=>Auth::user()->shop_id,
            'month_sales'=>rand(1,100),
            'rating_count'=>rand(1,50),
            'satisfy_count'=>rand(1,50),
            'satisfy_rate'=>rand(1,50),
            'rating'=>rand(1,10),
        ];
        Menu::create($data);
        return redirect()->route('menus.index')->with('success','添加菜品成功');
    }

    public function update(Request $request)
    {
        $this->validate($request,[
            'goods_name'=>'required',
            'goods_price'=>'required|numeric',
            'description'=>'required',
            'tips'=>'required',
            'category_id'=>'required',
        ],[
            'goods_name.required'=>'菜品名不能为空',
            'goods_price.required'=>'价格不能为空',
            'goods_price.numeric'=>'价格只能为整数',
            'description.required'=>'描述不能为空',
            'tips.required'=>'提示不能为空',
            'category_id.required'=>'分类不能为空',
        ]);
        $id = $request->id;
        $menu = Menu::find($id);
        $img = $request->img_path;
        if ($img!=null){
            $path = $img;
        }else{
            $path = $menu->goods_img;
        }
        $data = [
            'goods_name'=>$request->goods_name,
            'goods_price'=>$request->goods_price,
            'description'=>$request->description,
            'tips'=>$request->tips,
            'goods_img'=>$path,
            'category_id'=>$request->category_id,
        ];
        $menu->update($data);
        return redirect()->route('menus.index')->with('success','修改菜品成功');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('menus.index')->with('success','删除菜品成功');
    }

    public function ustatus(Menu $menu)
    {
        if ($menu->status == 1){
            $menu->status = 0;
        }else{
            $menu->status = 1;
        }
        $menu->save();
        return redirect()->route('menus.index')->with('success','修改状态成功');
    }

    //接收文件
    public function upload(Request $request)
    {
        $img = $request->file('file');
        //保存文件
        $path = Storage::url($img->store('public/goods'));
        return (['path'=>$path]);
    }
}
