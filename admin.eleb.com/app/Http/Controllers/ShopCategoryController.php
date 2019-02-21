<?php

namespace App\Http\Controllers;

use App\Models\ShopCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShopCategoryController extends Controller
{
    //
    public function index(Request $request)
    {
        $keyword = $request->keyword;
        if ($keyword){
            $shopcategories = ShopCategory::where('name','like',"%$keyword%")->paginate(3);
        }else{
            $shopcategories = ShopCategory::paginate(3);
        }
        return view('shopcategory.index',['shopcategories'=>$shopcategories]);
    }

    public function create()
    {
        return view('shopcategory.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,
            [
            'name'=>'required',
            'img'=>'required|image'
            ],
            [
                'name.required'=>'分类名不能为空',
                'img.required'=>'图片不能为空',
                'img.image'=>'图片格式不正确',
            ]);
        $img = $request->file('img');
        //保存文件
        $path = $img->store('public/shopcategory');
        $data = [
            'name'=>$request->name,
            'img'=>$path,
        ];
        ShopCategory::create($data);
        return redirect()->route('shopcategories.index')->with('success','添加分类成功');
    }

    public function update(Request $request)
    {
        $this->validate($request,
            [
                'name'=>'required',
                'img'=>'image'
            ],
            [
                'name.required'=>'分类名不能为空',
                'img.image'=>'图片格式不正确',
            ]);
        $id = $request->id;
        $shopcategory = ShopCategory::find($id);
        $img = $request->file('img');
        if ($img!=null){
            $path = $img->store('public/shopcategory');
            Storage::delete($shopcategory->img);
        }else{
            $path = $shopcategory->img;
        }
        $data = [
            'name'=>$request->name,
            'img'=>$path,
        ];
        $shopcategory->update($data);
        return redirect()->route('shopcategories.index')->with('success','修改分类成功');
    }

    public function destroy(ShopCategory $shopcategory)
    {
        Storage::delete($shopcategory->img);
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
}
