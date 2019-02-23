<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //
    public function create()
    {
        return view('login.login');
    }
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'password'=>'required',
            'captcha'=>'required|captcha'
        ],
            [
                'name.required'=>'用户名不能为空',
                'password.required'=>'密码不能为空',
                'captcha.required'=>'验证码不能为空',
                'captcha.captcha'=>'验证码不正确',
            ]
        );

        if (Auth::attempt([
            'name'=>$request->name,
            'password'=>$request->password,
            'status'=>1,
        ],$request->has('rememberMe'))){
            return redirect()->route('welcome.index')->with('success','登录成功');
        }else{
            return back()->with('danger','账号或者密码错误');
        }
    }

    public function destroy()
    {
        Auth::logout();
        return redirect()->route('login')->with('success','退出成功');
    }
}
