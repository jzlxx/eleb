<?php

namespace App\Http\Controllers;

use App\Models\ShopCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShopCategoryController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:商家管理']);
    }

    public function index(Request $request)
    {
        $keyword = $request->keyword;
        if ($keyword){
            $shopcategories = ShopCategory::where('name','like',"%$keyword%")->paginate(3);
        }else{
            $shopcategories = ShopCategory::paginate(3);
        }
        return view('shopcategory.index',['shopcategories'=>$shopcategories,'keyword'=>$keyword]);
    }


    public function store(Request $request)
    {
        $this->validate($request,
            [
            'name'=>'required',
            'img_path'=>'required'
            ],
            [
                'name.required'=>'分类名不能为空',
                'img_path.required'=>'图片不能为空',
            ]);
        $data = [
            'name'=>$request->name,
            'img'=>$request->img_path,
        ];
        ShopCategory::create($data);
        return redirect()->route('shopcategories.index')->with('success','添加分类成功');
    }

    public function update(Request $request)
    {
        $this->validate($request,
            [
                'name'=>'required',
            ],
            [
                'name.required'=>'分类名不能为空',
            ]);
        $id = $request->id;
        $shopcategory = ShopCategory::find($id);
        $img = $request->img_path;
        if ($img!=null){
            $path = $img;
        }else{
            $path = $shopcategory->img;
        }
        $data = [
            'name'=>$request->name,
            'img'=>$path
        ];
        $shopcategory->update($data);
        return redirect()->route('shopcategories.index')->with('success','修改分类成功');
    }

    public function destroy(ShopCategory $shopcategory)
    {
        $shopcategory->delete();
        return redirect()->route('shopcategories.index')->with('success','删除分类成功');
    }

    public function ustatus(ShopCategory $shopcategory)
    {
        if($shopcategory->status == 0){
            $shopcategory->status = 1;
        }else{
            $shopcategory->status = 0;
        }
        $shopcategory->save();
        return redirect()->route('shopcategories.index')->with('success','修改状态成功');
    }

    public function upload(Request $request)
    {
        $img = $request->file('file');
        //保存文件
        $path = Storage::url($img->store('public/shopcategory'));
        return (['path'=>$path]);
    }
}
