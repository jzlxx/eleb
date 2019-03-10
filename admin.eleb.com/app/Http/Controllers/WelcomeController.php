<?php

namespace App\Http\Controllers;

use App\Models\Nav;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class WelcomeController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $permissions = Permission::all();
        $navs = Nav::all();
        $unavs = Nav::where('pid','=',0)->get();
        return view('welcome.welcome',['navs'=>$navs,'unavs'=>$unavs,'permissions'=>$permissions]);
    }
}
