<?php

namespace App\Http\Controllers;

use App\Models\Nav;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class NavController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:菜单管理']);
    }


    public function index(Request $request)
    {
        $permissions = Permission::all();
        $navs = Nav::orderBy('pid','asc')->paginate(5);
        $unavs = Nav::where('pid','=',0)->get();
        return view('nav.index',['navs'=>$navs,'unavs'=>$unavs,'permissions'=>$permissions]);
    }

    public function store(Request $request)
    {
        $this->validate($request,
            [
                'name'=>'required',
            ],
            [
                'name.required'=>'名称不能为空',
            ]);
        $data = [
            'name'=>$request->name,
            'url'=>$request->url,
            'pid'=>$request->pid,
            'permission_id'=>0,
        ];
        Nav::create($data);
        return redirect()->route('navs.index')->with('success','添加菜单成功');
    }

    public function update(Request $request)
    {
        $this->validate($request,
            [
                'name'=>'required',
            ],
            [
                'name.required'=>'名称不能为空',
            ]);
        $id = $request->id;
        $nav = Nav::find($id);
        $data = [
            'name'=>$request->name,
            'url'=>$request->url,
            'pid'=>$request->pid,
        ];
        $nav->update($data);
        return redirect()->route('navs.index')->with('success','修改菜单成功');
    }

    public function rupdate(Request $request)
    {
        $id = $request->id;
        $nav = Nav::find($id);
        $data = [
            'permission_id'=>$request->permission_id,
        ];
        $nav->update($data);
        return redirect()->route('navs.index')->with('success','修改权限成功');
    }

    public function destroy(Nav $nav)
    {
        if ($nav->pid == 0){
            $num = Nav::where('pid','=',$nav->id)->count();
            if ($num){
                return redirect()->route('navs.index')->with('warning','拥有二级菜单，无法删除');
            }
        }
        $nav->delete();
        return redirect()->route('navs.index')->with('success','删除菜单成功');
    }
}
