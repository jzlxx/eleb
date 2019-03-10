<?php

namespace App\Http\Controllers;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:管理员管理']);
    }

    public function index()
    {
//        $keyword = $request->keyword;
//        if ($keyword){
//            $shopcategories = Permission::where('name','like',"%$keyword%")->paginate(3);
//        }else{
//            $shopcategories = ShopCategory::paginate(3);
//        }
        $permissions = Permission::paginate(3);
        return view('permission.index',['permissions'=>$permissions]);
    }

    public function store(Request $request)
    {
        $this->validate($request,
            [
                'name'=>'required',
            ],
            [
                'name.required'=>'权限名不能为空',
            ]);
        $data = [
            'name'=>$request->name,
//            'guard_name'=>'web',
        ];
        Permission::create($data);
        return redirect()->route('permissions.index')->with('success','添加权限成功');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')->with('success','删除权限成功');
    }
}
