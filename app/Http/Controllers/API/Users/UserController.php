<?php

namespace App\Http\Controllers\API\Users;


use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // public function getNearbyUsers(Request $request)
    // {
    //     $latitude = $request->has('latitude') ? $request->query('latitude') : null;
    //     $longitude = $request->has('longitude') ? $request->query('longitude') : null;
        
    //     return User::get();
    // }


    public function getNearbyUsers (Request $request){
        if (Auth::check())
        {
            $loggedInUserId = Auth::user()->getId();
        }
        $latitude = $request->has('latitude') ? $request->query('latitude') : null;
        $longitude = $request->has('longitude') ? $request->query('longitude') : null;
        $allUsers = User::select('id','name','latitude','longitude', 'email', 'date_of_birth', 'gender')->whereNotIn('id',[$loggedInUserId])->get();
        
        if($allUsers){
            $index = 0;
            foreach ($allUsers as $user){
                 $distance = round($this->distance($latitude,$longitude,$user['latitude'],$user['longitude'],'kilometer'));
                //  if($distance <= 5) {
                     $data[$index] = [
                         'id' => $user['id'],
                         'name' => $user['name'],
                         'distance' => $user['distance'],
                         'date_of_birth' => $user['date_of_birth'],
                         'email'        => $user['email'],
                         'gender'       => $user['gender'],
                         'distance' => $distance,

                     ];
                //  }
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
}
