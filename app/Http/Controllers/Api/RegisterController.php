<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'unique:users|required',
            'password' => 'required',
            'type_user' => 'required',
        ];

        $input = $request->only('name', 'email', 'password', 'type_user');

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->getMessageBag()]);
        } else {

            // CREAMOS NUEVO TOKEN
            $myUserCreate = User::create(['name' => $request->name, 'email' => $request->email, 'password' => Hash::make($request->password), 'type_user' => 'U']);

            //CREAMOS NOMBRE DE LA NUEVA WALLET
            //$nombreNuevaWallet = 'my-wallet-' . Str::slug($myUserCreate->name, "-") . "-" . $myUserCreate->id;
            //CREAMOS NUEVA CARTERA
            //$wallet = $myUserCreate->createWallet([
            //    'name' => 'Wallet ' . $myUserCreate->name,
            //    'slug' => $nombreNuevaWallet,
            //]);

            $myUserCreate->deposit(1000);

            if (Auth::attempt($request->only('name', 'email', 'password', 'type_user'))) {
                return response()->json([
                    'success' => true,
                    'token' => $request->user()->createToken($myUserCreate->email)->plainTextToken,
                    'message' => 'Success',
                    //'name_wallet' => $nombreNuevaWallet,
                    'data' => $myUserCreate
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }
        }
    }
}
