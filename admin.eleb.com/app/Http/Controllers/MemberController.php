<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:会员管理']);
    }

    public function index(Request $request)
    {
        $keyword = $request->keyword;
        if ($keyword){
            $members = Member::where('username','like',"%$keyword%")->paginate(3);
        }else{
            $members = Member::paginate(3);
        }
        return view('member.index',['members'=>$members,'keyword'=>$keyword]);
    }

    public function destroy(Member $member)
    {
        $member->delete();
        return redirect()->route('members.index')->with('success','删除成功');
    }
    public function ustatus(Member $member)
    {
        if($member->status == 0){
            $member->status = 1;
        }else{
            $member->status = 0;
        }
        $member->save();
        return redirect()->route('members.index')->with('success','修改状态成功');
    }
}
