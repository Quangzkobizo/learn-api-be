<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;

class UserInfoController extends Controller
{
    public function store(Request $request)
    {
        //$user = auth()->user();
        $user = User::find($request->user_id);
        if ($user->userInfo() != null) {
            $user->userInfo()->delete();
        }


        $newUserInfo = UserInfo::create([
            'user_id' => $request->user_id,
            'full_name' => $request->full_name,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'bio' => $request->bio,
        ]);

        return response()->json([
            'status' => 'UserInfo created',
            //'user' => $user->name,
        ]);
    }

    public function show($user_id)
    {
        //$user = User::find($user_id);
        $info = UserInfo::where('user_id', $user_id)->first();

        if ($info != null) {
            return response()->json([
                'status' => 'success',
                'info' => $info,

            ]);
        } else{
            return response()->json([
                'status'=> 'no info',
            ]);
        }
    }
}
