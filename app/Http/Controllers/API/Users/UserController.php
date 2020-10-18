<?php

namespace App\Http\Controllers\API\Users;


use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserBehaviour;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function getNearbyUsers (Request $request){
        if (Auth::check())
        {
            $loggedInUserId = Auth::user()->getId();
        }
        $latitude = $request->has('latitude') ? $request->query('latitude') : null;
        $longitude = $request->has('longitude') ? $request->query('longitude') : null;
        $allUsers = User::select('id','name','latitude','longitude', 'email', 'date_of_birth', 'gender', 'profile_photo_path')->whereNotIn('id',[$loggedInUserId])->get();
        $today=Carbon::now();
        if($allUsers){
            $index = 0;
            foreach ($allUsers as $user){
                 $distance = $this->distance($latitude,$longitude,$user['latitude'],$user['longitude'],'kilometer');
                 if($distance <= 5) {
                    $userDOB = Carbon::parse($user['date_of_birth']);
                    
                    $userAgeInYears = $userDOB->diffInYears($today);
                    
                    $data[$index] = [
                         'id' => $user['id'],
                         'name' => $user['name'],
                         'distance' => $user['distance'],
                         'date_of_birth' => $user['date_of_birth'],
                         'email'        => $user['email'],
                         'gender'       => $user['gender'],
                         'distance' => round($distance, 1),
                         'age'      => $userAgeInYears,
                         'profile_pic' => env('APP_URL').'/storage/'.$user['profile_photo_path']
                     ];
                 }
                 $index ++;
            }
            return $data;
        }
    }
    protected function distance($lat1, $lon1, $lat2, $lon2, $unit) {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);
        if ($unit == "kilometer") {
            return ($miles * 1.609344);
        } else if ($unit == "mile") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    public function getLoggedInUser()
    {
        if (Auth::check())
        {
            $loggedInUserId = Auth::user()->getId();
        }

        return User::findOrFail($loggedInUserId);
    }

    public function likeUsers(int $userId, Request $request)
    {
        $likeData = [
            'user_id' => $request->has('user_id') ? $request->user_id : null,
            'like_dislike_user_id' => $request->has('liked_user_id') ? $request->liked_user_id : null,
            'is_liked' => $request->has('is_liked') ? $request->is_liked : null,
            'is_disliked' => $request->has('is_disliked') ? $request->is_disliked : null,
        ];
        
        $userLikedData = UserBehaviour::updateOrCreate(['user_id' => $userId, 'like_dislike_user_id' => $likeData['like_dislike_user_id']], $likeData);
        
        $mutualLike = UserBehaviour::where(['user_id' => $likeData['like_dislike_user_id'], 'like_dislike_user_id' => $userId, 'is_liked' => 1])->first();

        if($mutualLike) {
            return response()->json([
                'status' => 'nutual_like',
                'message' => "It's a Match!"
            ]);
        }
        
        if($userLikedData) {
            return response()->json([
                'status' => 'success',
                'message' => 'You liked this user'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Filed to like this user'
            ]);
        }
    
    }

    
    public function dislikeUsers(int $userId, Request $request)
    {
        $dislikeData = [
            'user_id' => $request->has('user_id') ? $request->user_id : null,
            'like_dislike_user_id' => $request->has('disliked_user_id') ? $request->disliked_user_id : null,
            'is_liked' => $request->has('is_liked') ? $request->is_liked : null,
            'is_disliked' => $request->has('is_disliked') ? $request->is_disliked : null,
        ];
        
        $userdislikedData = UserBehaviour::updateOrCreate(['user_id' => $userId, 'like_dislike_user_id' => $dislikeData['like_dislike_user_id']], $dislikeData);
        if($userdislikedData) {
            return response()->json([
                'status' => 'success',
                'message' => 'You disliked this user'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Filed to dislike this user'
            ]);
        }
    }
}
