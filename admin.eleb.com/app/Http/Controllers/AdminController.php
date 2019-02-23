<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
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
            $admins = Admin::where('name','like',"%$keyword%")->paginate(3);
        }else{
            $admins = Admin::paginate(3);
        }
        return view('admin.index',['admins'=>$admins,'keyword'=>$keyword]);
    }

    public function store(Request $request)
    {
        $this->validate($request,
            [
                'name'=>'required',
                'email'=>'required',
                'password'=>'required',
            ],
            [
                'name.required'=>'用户名不能为空',
                'email.required'=>'邮箱不能为空',
                'password.required'=>'密码不能为空',
            ]
        );
        $data = [
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'remember_token'=>uniqid(),
        ];
        Admin::create($data);
        return redirect()->route('admins.index')->with('success','添加管理员成功');
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();
        return redirect()->route('admins.index')->with('success','删除管理员成功');
    }
}
