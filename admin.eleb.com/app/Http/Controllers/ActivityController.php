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
        $this->middleware(['permission:活动管理']);
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
        if ($request->time == 3){
            $data[] = ['end_time','<',$time];
        }
        $activities = Activity::where($data)->paginate(3);
        return view('activity.index',['activities'=>$activities,'time'=>$request->time]);
    }


    public function store(Request $request)
    {
        $this->validate($request,
            [
                'title'=>'required',
                'content'=>'required',
                'start_time'=>'required',
                'end_time'=>'required|after:start_time',
            ],
            [
                'title.required'=>'标题不能为空',
                'content.required'=>'详情不能为空',
                'start_time.required'=>'开始时间不能为空',
                'end_time.required'=>'结束时间不能为空',
                'end_time.after'=>'结束时间不能早于开始时间',

            ]);
        $data = [
            'title'=>$request->title,
            'content'=>$request->content,
            'start_time'=>$request->start_time,
            'end_time'=>$request->end_time,
        ];
        Activity::create($data);
        return redirect()->route('activities.index')->with('success','添加活动成功');
    }

    public function update(Request $request)
    {
        $this->validate($request,
            [
                'title'=>'required',
                'content'=>'required',
                'start_time'=>'required',
                'end_time'=>'required|after:start_time',
            ],
            [
                'title.required'=>'标题不能为空',
                'content.required'=>'详情不能为空',
                'start_time.required'=>'开始时间不能为空',
                'end_time.required'=>'结束时间不能为空',
                'end_time.after'=>'结束时间不能早于开始时间',

            ]);
        $id = $request->id;
        $activity = Activity::find($id);
        $data = [
            'title'=>$request->title,
            'content'=>$request->content,
            'start_time'=>$request->start_time,
            'end_time'=>$request->end_time,
        ];
        $activity->update($data);
        return redirect()->route('activities.index')->with('success','修改活动成功');
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();
        return redirect()->route('activities.index')->with('success','删除活动成功');
    }

}
