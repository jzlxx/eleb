<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:管理员管理']);
    }

    public function index(Request $request)
    {
        $roles = Role::all();
        $keyword = $request->keyword;
        if ($keyword){
            $admins = Admin::where('name','like',"%$keyword%")->paginate(3);
        }else{
            $admins = Admin::paginate(3);
        }
        return view('admin.index',['admins'=>$admins,'keyword'=>$keyword,'roles'=>$roles]);
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
        $admin = Admin::create($data);
        $admin->syncRoles($request->role);
        return redirect()->route('admins.index')->with('success','添加管理员成功');
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();
        return redirect()->route('admins.index')->with('success','删除管理员成功');
    }

    public function pwd()
    {
        return view('admin.pwd');
    }

    public function pupdate(Request $request){
        $this->validate($request,[
            'oldpassword'=>'required',
            'password'=>'required|confirmed',
            'password_confirmation'=>'required|same:password'//不为空,两次密码是否相同,
        ],[
            'oldpassword.required'=>"原密码不能为空",
            'password.required'=>"新密码不能为空",
            'password_confirmation.required'=>"确认密码不能为空",
            'password.confirmed'=>"密码与确认密码不相同",
            'password_confirmation.same'=>"请正确核对两次密码是否相同 ",
        ]);
        $user = auth()->user();
        $result=Hash::check($request->oldpassword,$user->password);
        if($result){
            $user->update(
                ['password'=>Hash::make($request->password)]
            );
            Auth::logout();
            return redirect()->route('login')->with('success','修改成功，重新登录');
        }else{
            return back()->with('danger',"修改密码失败");
        }
    }


    public function rupdate(Request $request)
    {
        $admin = Admin::find($request->id);
        $admin->syncRoles($request->role);
        return redirect()->route('admins.index')->with('success','修改角色成功');
    }
}
