<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // If user has 2FA, user needs to have either a valid 2FA token or a valid recovery code
//        if (!$this->validateCode($user, (string) $request->code)) {
//            throw ValidationException::withMessages([
//                'code' => ['The provided one-time password is incorrect.'],
//            ]);
//        }

        return response()->json(['token' => $user->createToken('Login Token')->plainTextToken]);
    }

}
