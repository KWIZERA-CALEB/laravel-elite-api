<?php

namespace App\Http\Controllers\Base;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    //Return teachers
    public function teachers() {
        $creteria = "Teacher";
        $teachers = User::where('role', 'LIKE', "%{$creteria}%")
                        ->get();
        
        $data = [
            'status'=>200,
            'teachers'=>$teachers,
        ];

        return response()->json($data, 200);

    }

    //user profile
    public function userProfile(Request $request, $id) {
        $user = User::find($id);

        $data = [
            'status'=>200,
            'user'=>$user,
        ];

        return response()->json($data, 200);

    }

    //Profile
    public function loginedUser() {
        $user = auth()->user();

        $data = [
            'status'=>200,
            'user'=>$user,
        ];

        return response()->json($data, 200);
    }
}
