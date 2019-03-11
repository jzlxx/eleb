<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventMember;
use App\Models\EventPrize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class EventController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $events = Event::where('signup_start','<',strtotime(date('Y-m-d')))->paginate(3);
        return view('event.index',['events'=>$events]);
    }

    public function sign(Event $event)
    {
        $events_id = $event->id;
        $num = Redis::decr($events_id);
        if($num >= 0){
            $member_id = Auth::user()->id;
            EventMember::create(
                [
                    'events_id'=>$events_id,
                    'member_id'=>$member_id,
                ]
            );
            return redirect()->route('events.index')->with('success','报名成功');
        }else{
            Redis::incr($events_id);
            return redirect()->route('events.index')->with('warning','报名人数已满');
        }
    }
}
