<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // Metodo que recibe el formulario
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (Auth::attempt($request->only('email', 'password'))) {

            $user = DB::table('users')->where(['email' => $request->email])->first();

            //dd(Hash::make($password));

            return response()->json([
                'success' => true,
                'token' => $request->user()->createToken($request->email)->plainTextToken,
                'message' => 'Success',
                //'name_wallet' => $nombreNuevaWallet,
                'data' => $user
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Usuario y/o Contraseña no Valido.',
            ], 401);
        }
    }

    // Metodo que verifica que llegue la informacion correctamente
    public function validateLogin(Request $request)
    {
        return $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    }
}
