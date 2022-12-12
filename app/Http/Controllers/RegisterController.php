<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hash;

class RegisterController extends Controller
{

    public function register(UserRegisterRequest $request)
    {
        $request['password'] = bcrypt($request['password']);
        if ($request['photo']) {
            $file = $request->file('photo');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('public/user-image'), $filename);
            $request['image'] = '/public/user-image/' . $filename;
        }
        $user = User::create($request->all());

        $token = $user->createToken('MyApp')->accessToken;
        if ($user) {
            return response()->json(['success' => true, 'message' => $user->first_name . ' ' . $user->last_name . ' created successfully', 'token' => $token]);
        } else {
            return response()->json(['success' => false, 'message' => 'some thing went wrong...']);
        }
    }

    public function login(Request $request)
    {
        if (User::where('email', $request->email)->exists()) {
            $user = User::where('email', $request->email)->first();
            $password = Hash::check($request->password, $user->password, []);
            if ($password) {
                $token = $user->createToken('MyApp')->accessToken;
                $data =
                    [
                        'first_name' => $user->first_name,
                        'first_name' => $user->last_name,
                        'email' => $user->email,
                    ];
                return response()->json(['success' => true, 'message' => 'logedIn successfully', 'data' => $data, 'token' => $token]);


            } else {
                return response()->json(['success' => false, 'message' => "Email or Password is incorrect"]);
            }
        } else {
            return response()->json(['success' => false, 'message' => "un-Authenticated"]);
        }


    }


}
