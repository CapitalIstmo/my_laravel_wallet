<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
                'token' => $request->user()->createToken($request->email,['user'])->plainTextToken,
                'message' => 'Success',
                //'name_wallet' => $nombreNuevaWallet,
                'data' => $user,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'token' => null,
                'message' => 'Usuario y/o Contraseña no Valido.',
                'data' => null,
            ], 401);
        }
    }

    public function loginadmin(Request $request)
    {
        if ($request->email != "" || $request->password != "") {
            if (DB::table('users')->where(['email' => $request->email, 'type_user' => 'SA'])->exists()) {
                if (Auth::attempt($request->only('email', 'password'))) {

                    return response()->json([
                        'success' => true,
                        'token' => $request->user()->createToken($request->email,['admin'])->plainTextToken,
                        'message' => 'Login Valid and Token Generate.'
                    ],200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'User or Password not Valid.',
                    ], 401);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'User SuperAdmin Not Found.',
                ], 401);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Params not Valid.',
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
