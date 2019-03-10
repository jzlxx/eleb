<?php

namespace App\Http\Controllers;

use App\Models\EventPrize;
use Illuminate\Http\Request;

class EventPrizeController extends Controller
{
    //

    public function store(Request $request){
        $this->validate($request,[
            'name'=>"required",
            'description'=>"required",
        ],[
            'name.required'=>'商品名不能为空',
            'description.required'=>'商品详情不能为空',
        ]);
        $data = [
            "events_id"=>$request->events_id,
            "name"=>$request->name,
            "description"=>$request->description,
            "member_id"=>0
        ];
        EventPrize::create($data);
        return redirect()->route('events.index')->with('success','添加奖品成功');
    }

    public function destory(Request $request)
    {
        for ($i = 0;$i < count($request->eventprize);$i++){
            $eventprize = EventPrize::find($request->eventprize[$i]);
            $eventprize->delete();
        }
        return redirect()->route('events.index')->with('success','删除奖品成功');
    }
}
