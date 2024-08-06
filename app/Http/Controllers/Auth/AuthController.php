<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


/**
 * @OA\Info(
 *      title="App",
 *      version="1.0.0"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/register",
     *      description="Register User",
     *      @OA\RequestBody(
     *          required=true,
     *               @OA\JsonContent(
     *               required={"name", "email", "password", "age",
     *              "profile", "role", "phone"},
     *               @OA\Property(property="name", type="string", example="example"),
     *               @OA\Property(property="email", type="string", format="email", example="example@gmail.com"),
     *               @OA\Property(property="password", type="string", format="password", example="pass123"),
     *               @OA\Property(property="age", type="string", example="10"),
     *               @OA\Property(property="profile", type="string", example="profile.png"),
     *               @OA\Property(property="role", type="string", example="Student"),
     *               @OA\Property(property="phone", type="string", example="0798205731"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="User registered",
     *          @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/User")
     *            )
     *      )
     * )
     */
    public function register(Request $request) {
        $validate = Validator::make($request->all(), [
            'name'=>'required|max:255|string',
            'email'=>'required|max:255|email|unique:users,email',
            'age'=>'required|integer|min:1',
            'password'=>'required|min:8',
            'profile'=>'required',
            'role'=>'required',
            'phone'=>'max:10|min:10|nullable',
        ],
        [
            'name.required'=>'Full name is required',
            'name.max'=>'Full name too long',
            'name.string'=>'Full name is invalid',
            'email.required'=>'Email is required',
            'email.email'=>'Email is invalid',
            'email.unique'=>'Email Not Allowed',
            'email.max'=>'Email is long',
            'age.integer'=>'Age must be a number',
            'age.required'=>'Age is required',
            'password.min'=>'Password too short',
            'password.required'=>'Password is required',
            'profile.required'=>'Profile is required',
            'role.required'=>'Role is required',
            'phone.min'=>'Phone must be exactly 10 digits',
            'phone.max'=>'Phone must be exactly 10 digits',
        ]
        );

        if($validate->fails()) {
            $data = [
                'status'=>422,
                'error'=>$validate->messages()
            ];
            return response()->json($data, 422);
        }else {
            $verificationCode = rand(100000, 999999);

            $user = User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'age'=>$request->age,
                'password'=>Hash::make($request->password),
                'profile'=>$request->profile,
                'role'=>$request->role,
                //'verification_code'=>$verificationCode,
                //'is_verified'=>false,
                'phone'=>$request->phone,
            ]);

            //send email
            //Mail::to($user->email)->send(new VerificationMail($user, $verificationCode));

            $data = [
                'status'=>201,
                'message'=>'You are Registered'
            ];

            return response()->json($data, 201);
        }
    }


    /**
     * @OA\Post(
     *      path="/api/login",
     *      description="Login User",
     *      @OA\Response(
     *          response=200,
     *          description="User loggined",
     *          @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/User")
     *            )
     *      )
     * )
     */
    public function login(Request $request) {
        $validate = Validator::make($request->all(), [
            'email'=>'required|max:255|email',
            'password'=>'required',
        ],
        [
            'email.required'=>'Email is required',
            'email.email'=>'Invalid email',
            'email.max'=>'Email too long',
            'password.required'=>'Password is required'
        ]);

        if($validate->fails()) {
            $data = [
                'status'=>422,
                'error'=>$validate->messages()
            ];
            return response()->json($data, 422);
        }else {
            if(!auth()->attempt($request->only('email', 'password', $request->remember))) {
                $data = [
                    'status'=>401,
                    'error'=>'Incorrect Credentials'
                ];
                return response()->json($data, 401);
            }

            // Retrieve the user and login user
            /** @var \App\Models\User $user **/
            $user = auth()->user();

            /** @var \App\Models\User $user **/
            //$user = Auth::user(); 

            //Generate a token
            $token = $user->createToken('auth_token')->plainTextToken;

            $data = [
                'status'=>200,
                'token'=>$token,
                'user'=>$user,
                'message'=>'You are loggedIn'
            ];
            return response()->json($data, 200);
        }
    }

    public function editPassword(Request $request) {
        $validate = Validator::make($request->all(), [
            'old_password'=>'required',
            'new_password'=>'required|min:8',
        ],[
            'new_password.required'=>'New Password is required',
            'new_password.min'=>'Password must be 8 chars',
            'old_password.required'=>'Old Password is required'
        ]);

        if($validate->fails()) {
            $data = [
                'status'=>422,
                'error'=>$validate->messages()
            ];
            return response()->json($data, 422);
        }else {
            $user = auth()->user();
            // check old password
            if(!Hash::check($request->input('old_password'), $user->password)) {
                $data = [
                    'status'=>422,
                    'error'=>'Old Password is incorrect'
                ];
                return response()->json($data, 422);
            }else {
                $user->password = Hash::make($request->input('new_password'));
                //$user->save();

                $data = [
                    'status'=>200,
                    'message'=>'Password Changed'
                ];
                return response()->json($data, 200);
            }
        }
    }

    public function logOut(Request $request) {
        $user = $request->user();

        $user->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }
}
