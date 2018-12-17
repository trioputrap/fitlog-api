<?php

namespace App\Http\Controllers\API;

use DB;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Follow; 
use Illuminate\Support\Facades\Auth; 

class FollowController extends Controller 
{

public $successStatus = 200;
    /** 
     * follow api
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function getFollowers() 
    { 
        $followers = DB::table('users')
                    ->join('follows','users.id','=','follows.follower_id')
                    ->select('users.id', 'users.name', 'users.gender', 'users.birthday', 'users.weight', 'users.height', 'users.username')
                    ->where('follows.following_id','=',Auth::user()->id)
                    ->get();
        return response()->json($followers, $this-> successStatus); 
    } 

    public function getFollowing() 
    { 
        $following = DB::table('users')
                    ->join('follows','users.id','=','follows.following_id')
                    ->select('users.id', 'users.name', 'users.gender', 'users.birthday', 'users.weight', 'users.height', 'users.username')
                    ->where('follows.follower_id','=',Auth::user()->id)
                    ->get();
        return response()->json($following, $this-> successStatus); 
    } 

    public function follow(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'follower_id' => 'required', 
            'following_id' => 'required', 
        ]);
        $follow = Follow::create($request); 
        return response()->json($follow, $this-> successStatus); 
    } 

    
    public function unfollow(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'following_id' => 'required'
        ]);
        $input = $request->all();
        $follow = Follow::where('follower_id', Auth::user()->id)
                        ->where('follower_id', $input['follower_id'])->delete();
        return response()->json($follow, $this-> successStatus); 
    } 
}