<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MenuCategoryController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $keyword = $request->keyword;
        if ($keyword){
            $menucategories = MenuCategory::where('name','like',"%$keyword%")->paginate(3);
        }else{
            $menucategories = MenuCategory::paginate(3);
        }
        return view('menucategory.index',['menucategories'=>$menucategories,'keyword'=>$keyword]);
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
                'description'=>'required',
                'is_selected'=>'required',
            ],
            [
                'name.required'=>'分类名不能为空',
                'description.required'=>'描述不能为空',
                'is_selected.required'=>'是否默认分类不能为空',
            ]);
        if ($request->is_selected == 1){
            DB::update('update menu_categories set is_selected = 0');
        }
        $data = [
            'name'=>$request->name,
            'description'=>$request->description,
            'type_accumulation'=>range('a','z')[rand(0,25)],
            'shop_id'=>Auth::user()->shop_id,
            'is_selected'=>$request->is_selected,
        ];
        MenuCategory::create($data);
        return redirect()->route('menucategories.index')->with('success','添加分类成功');
    }

    public function update(Request $request)
    {
        $this->validate($request,
            [
                'name'=>'required',
                'description'=>'required',
                'is_selected'=>'required',
            ],
            [
                'name.required'=>'分类名不能为空',
                'description.required'=>'描述不能为空',
                'is_selected.required'=>'是否默认分类不能为空',
            ]);
        $id = $request->id;
        $menucategory = MenuCategory::find($id);
        if ($request->is_selected == 1){
            DB::update('update menu_categories set is_selected = 0');
        }
        $data = [
            'name'=>$request->name,
            'description'=>$request->description,
            'is_selected'=>$request->is_selected,
        ];
        $menucategory->update($data);
        return redirect()->route('menucategories.index')->with('success','修改分类成功');
    }

    public function destroy(MenuCategory $menucategory)
    {
        $id = $menucategory->id;
        $menus = Menu::where('category_id','=',$id);
        if ($menus){
            return redirect()->route('menucategories.index')->with('danger','不是空菜品分类');
        }
        $menucategory->delete();
        return redirect()->route('menucategories.index')->with('success','删除分类成功');
    }

    public function ustatus(MenuCategory $menucategory)
    {
        DB::update('update menu_categories set is_selected = 0');
        $menucategory->is_selected = 1;
        $menucategory->save();
        return redirect()->route('menucategories.index')->with('success','修改状态成功');
    }
}
