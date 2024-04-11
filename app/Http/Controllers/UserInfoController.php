<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserInfoController extends Controller
{

    public function __construct()
    {
        $this->middleware("auth:api", ["only" => ['update', 'create']]);
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
                'error' => 'already have info',
            ]);
        }

        if (Auth::user()->id != $userId) {
            return response()->json([
                'error' => 'no permission',
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
        if (!$user) {
            return response()->json([
                'error' => 'not authenticated',
            ], 404);
        }
        $userInfo = $user->userInfo; // Lấy thông tin userInfo của user

        return response()->json([
            'status' => 'success',
            'user' => $user,
            //'info' => $userInfo
        ]);
    }

    public function uploadAvatar(Request $request)
    {

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'error' => 'Not authenticated',
            ], 404);
        };

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        //save new avatar file
        $imageName = time() . '.' . $request->image->extension();
        Storage::disk('public')->
        $request->image->move(public_path('images'), $imageName);

        //delete old avatar file
        $oldAvatar = $user->userInfo->avatar;
        dd($oldAvatar);
        if ($oldAvatar){
            if (Storage::disk('public')->exists('images/' . $oldAvatar)) {
                dd('tét');
                Storage::disk('public')->delete('images/' . $oldAvatar);
            }
        }

        //save new avatar to database
        $user->userInfo->avatar = $imageName;
        $user->userInfo->save();

        return response()->json(['success' => 'Image uploaded successfully']);
    }

    public function getAvatar($userId){
        //$user = Auth::user();
        $user = User::find($userId);
        return response()->json([
            'status'=> 'success',
            'avatar'=> 'http://localhost:8000/images/'.$user->UserInfo->avatar,
            ]);
    }
}
