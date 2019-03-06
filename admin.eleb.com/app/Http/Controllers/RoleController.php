<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    //
    public function index()
    {
        $permissions = Permission::all();
        $roles = Role::paginate(3);
        return view('role.index',['roles'=>$roles,'permissions'=>$permissions]);
    }

    public function store(Request $request)
    {
        $this->validate($request,
            [
                'name'=>'required',
                'permission'=>'required',
            ],
            [
                'name.required'=>'角色名不能为空',
                'permission.required'=>'权限不能为空',
            ]);
        $data = [
            'name'=>$request->name,
        ];
        $role = Role::create($data);
        $role->syncPermissions($request->permission);
        return redirect()->route('roles.index')->with('success','添加角色成功');
    }

    public function update(Request $request)
    {
        $this->validate($request,
            [
                'name'=>'required',
                'permission'=>'required',
            ],
            [
                'name.required'=>'角色名不能为空',
                'permission.required'=>'权限不能为空',
            ]);
        $role = Role::find($request->id);
        $role->name = $request->name;
        $role->save();
        $role->syncPermissions($request->permission);
        return redirect()->route('roles.index')->with('success','修改角色成功');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success','删除角色成功');
    }
}
