<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventPrize;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:试用活动管理']);
    }

    public function index(Request $request)
    {
        $events = Event::paginate(3);
        return view('event.index',['events'=>$events]);
    }

    public function store(Request $request)
    {
        $this->validate($request,
            [
                'title'=>'required',
                'content'=>'required',
                'signup_start'=>'required',
                'signup_end'=>'required|after:signup_start',
                'prize_date'=>'required|after:signup_end',
                'signup_num'=>'required',
            ],
            [
                'title.required'=>'标题不能为空',
                'content.required'=>'详情不能为空',
                'signup_start.required'=>'报名开始时间不能为空',
                'signup_end.required'=>'报名结束时间不能为空',
                'prize_date.required'=>'开奖时间不能为空',
                'signup_num.required'=>'报名人数限制不能为空',
                'signup_end.after'=>'报名结束时间不能早于开始时间',
                'prize_date.after'=>'开奖时间不能早于报名结束时间',

            ]);
        $data = [
            'title'=>$request->title,
            'content'=>$request->content,
            'signup_start'=>strtotime($request->signup_start),
            'signup_end'=>strtotime($request->signup_end),
            'prize_date'=>$request->prize_date,
            'signup_num'=>$request->signup_num,
            'is_prize'=>0,
        ];
        Event::create($data);
        return redirect()->route('events.index')->with('success','添加活动成功');
    }


    public function update(Request $request)
    {
        $this->validate($request,
            [
                'title'=>'required',
                'content'=>'required',
                'signup_start'=>'required',
                'signup_end'=>'required|after:signup_start',
                'prize_date'=>'required|after:signup_end',
                'signup_num'=>'required',
            ],
            [
                'title.required'=>'标题不能为空',
                'content.required'=>'详情不能为空',
                'signup_start.required'=>'报名开始时间不能为空',
                'signup_end.required'=>'报名结束时间不能为空',
                'prize_date.required'=>'开奖时间不能为空',
                'signup_num.required'=>'报名人数限制不能为空',
                'signup_end.after'=>'报名结束时间不能早于开始时间',
                'prize_date.after'=>'开奖时间不能早于报名结束时间',

            ]);
        $id = $request->id;
        $event = Event::find($id);
        $data = [
            'title'=>$request->title,
            'content'=>$request->content,
            'signup_start'=>strtotime($request->signup_start),
            'signup_end'=>strtotime($request->signup_end),
            'prize_date'=>$request->prize_date,
            'signup_num'=>$request->signup_num,
        ];
        $event->update($data);
        return redirect()->route('events.index')->with('success','修改活动成功');
    }


    public function destroy(Event $event)
    {
        EventPrize::where('events_id','=',$event->id)->delete();
        $event->delete();
        return redirect()->route('events.index')->with('success','删除活动成功');
    }

    public function luck(Event $event)
    {
        $members_id = DB::table('event_members')->where('events_id','=',$event->id)->pluck('member_id')->toArray();
        $prizes = EventPrize::where('events_id','=',$event->id)->get();
        shuffle($members_id);
        for ($i = 0;$i < count($prizes);$i++){
            $prizes[$i]->update(['member_id'=>$members_id[$i]]);


            $id = $members_id[$i];
            $user = User::where('id','=',$id)->first();
            $email = $user->email;

            $title = '试用申请成功通知';
            $content = "<p>	
                亲，您申请的试用奖品".$prizes[$i]->name."已经<span style='color: red'>成功</span>获得！<br/>
                请注意查看。
               </p>";
            try{
                \Illuminate\Support\Facades\Mail::send('email.default',compact('title','content'),
                    function($message) use($email){
                        $to = $email;
                        $message->from(env('MAIL_USERNAME'))->to($to)->subject('试用申请成功通知');
                    });
            }catch (\Exception $e){
                return "邮件发送失败";
            }
        }
        $event->update(['is_prize'=>1]);
        return redirect()->route('events.index')->with('success','抽奖成功');
    }
}
