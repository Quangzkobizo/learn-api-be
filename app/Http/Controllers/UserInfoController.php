<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserInfoController extends Controller
{

    public function __construct()
    {
        $this->middleware("auth:api", ["only" => ['me', 'update', 'create']]);
    }

    public function update(Request $request, $userId)
    {
        $userInfo = UserInfo::where('user_id', $userId)->first();

        if (!$userInfo) {
            return response()->json([
                'error' => 'User information not found',
            ], 404);
        }

        if ($userId != Auth::user()->id) {
            return response()->json([
                'error' => 'You do not have permission to update this user information',
            ], 403);
        }

        // Cập nhật thông tin người dùng
        $userInfo->update([
            'full_name' => $request->input('full_name', $userInfo->full_name),
            'date_of_birth' => $request->input('date_of_birth', $userInfo->date_of_birth),
            'gender' => $request->input('gender', $userInfo->gender),
            'bio' => $request->input('bio', $userInfo->bio),
        ]);

        return response()->json([
            'message' => 'User information updated successfully',
            'user_info' => $userInfo,
        ], 200);
    }


    public function create(Request $request, $userId)
    {
        $userInfo = UserInfo::where('user_id', $userId)->first();
        if ($userInfo) {
            return response()->json([
                'error'=> 'already have info',
            ]);
        }

        if (Auth::user()->id != $userId) {
            return response()->json([
                'error'=> 'no permission',
                ], 404);
        }

        $newUserInfo = UserInfo::create([
            'user_id' => $request->user_id,
            'full_name' => $request->full_name,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'bio' => $request->bio,
        ]);

        return response()->json([
            'message' => 'info created',
        ], 200);
    }

    public function me()
{
    $user = Auth::user(); // Lấy thông tin user thông qua Auth::user()
    $userInfo = $user->userInfo; // Lấy thông tin userInfo của user

    return response()->json([
        'status' => 'success',
        'user' => $user,
        //'info' => $userInfo
    ]);
}

}
