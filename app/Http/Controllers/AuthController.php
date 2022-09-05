<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Http\Resources\AuthResource;
use App\Http\Resources\GetMeResource;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validated = $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|min:6"
        ],
        [
            "name.required" => "Mohon untuk mengisi nama anda.",
            "email.required" => "Mohon untuk mengisi email anda",
            "email.email" => "Mohon masukan alamat email yang valid",
            "email.unique" => "Email sudah digunakan. Silahkan gunakan email yang lain",
            "password.required" => "Mohon untuk mengisi password anda",
            "password.min" => "Isi password minimal 6 karakter"
        ]);
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);

        event(new Registered($user));

        return response()->json([
            "success" => true,
            "message" => "Registered"
        ]);
    }


    public function login(Request $request)
    {
        if(!Auth::attempt($request->only("email","password")))
        {
            return response()->json([
                "message" => "Email atau password anda salah"
            ]);
        }

        $user = User::where("email", $request["email"])->firstOrFail();

        return new AuthResource($user);
    }

    public function getme()
    {
        $user = auth()->user();
        return new GetMeResource($user);
    }

    public function refresh()
    {
        $user = auth()->user();
        $user->currentAccessToken()->delete();
        $newToken = $user->createToken("access_token")->plainTextToken;

        return response()->json([
            "success" => true,
            "access_token" => $newToken
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            "success" => true,
            "message" => "Loged out"
        ]);
    }
}
