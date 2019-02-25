<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $data = [];
        $time = date("Y-m-d H:i:s");
        if ($request->time == 1){
            $data[] = ['start_time','>',$time];
        }
        if ($request->time == 2){
            $data[] = ['start_time','<',$time];
            $data[] = ['end_time','>',$time];
        }
        $activities = Activity::where('end_time','>',$time)->where($data)->paginate(3);
        return view('activity.index',['activities'=>$activities,'time'=>$request->time]);
    }
}
