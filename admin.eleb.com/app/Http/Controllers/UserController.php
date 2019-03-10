<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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
            $users = User::where('name','like',"%$keyword%")->paginate(3);
        }else{
            $users = User::paginate(3);
        }
        return view('user.index',['users'=>$users,'keyword'=>$keyword]);
    }

    public function update(Request $request)
    {
        $this->validate($request,
            [
                'password'=>'required'
            ],
            [
                'password.required'=>'密码不能为空'
            ]
        );
        $id = $request->id;
        $user = User::find($id);
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->route('users.index')->with('success','重置密码成功');
    }

    public function ustatus(User $user)
    {
        if($user->status == 0){
            $user->status = 1;
        }else{
            $user->status = 0;
        }
        $user->save();
        return redirect()->route('users.index')->with('success','修改状态成功');
    }
}
