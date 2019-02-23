<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //

    public function pwd()
    {
        return view('user.pwd');
    }

    public function pupdate(Request $request){
        $this->validate($request,[
            'oldpassword'=>'required',
            'password'=>'required|confirmed',
            'password_confirmation'=>'required|same:password'//不为空,两次密码是否相同,
        ],[
            'oldpassword.required'=>"原密码不能为空",
            'password.required'=>"新密码不能为空",
            'password_confirmation.required'=>"重复密码不能为空",
            'password.confirmed'=>"密码与确认密码不匹配",
            'password_confirmation.same'=>"请正确核对两次密码是否一致",
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
}
