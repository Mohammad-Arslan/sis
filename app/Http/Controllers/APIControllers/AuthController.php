<?php

namespace App\Http\Controllers\APIControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \App\Models\User;
use \Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $user = User::where('email', $request->email)->first();

            if (! $user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            $data['token'] = $user->createToken($user->name)->plainTextToken;

            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Successfully LoggedIn','data' => $data]);
        }
        catch (\Exception $exception){
            return response()->json(['code' => 422, 'status' => 'false', 'message' => $exception->getMessage(), 'data' => []]);
        }
    }

    public function getUser(Request $request){
        try {
            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data Sent Successfully.','data' => $request->user()]);
        }
        catch (\Exception $exception){
            return response()->json(['code' => 422, 'status' => 'false', 'message' => $exception->getMessage(), 'data' => []]);
        }
    }
}
