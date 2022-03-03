<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return UserResource::collection(User::latest()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if ($request->id != "") {
            $count = User::where('id', '=', $request->id)->count();

            if ($count > 0) {
                $user = User::find($request->id);

                return response()->json([
                    'success' => true,
                    'message' => 'The user is found',
                    'data' => $user->name,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'User Not Found',
                    'data' => '',
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Params not Valid',
                'data' => '',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function myBalance(Request $request)
    {
        $rules = [
            //'name_wallet' => 'required',
            //'token' => 'required',
            'id' => 'required',
        ];

        $input = $request->only('id');

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->getMessageBag()]);
        } else {

            $user = DB::table('users')->where('id', $request->id)->first();

            //dd($user);

            if ($user != null) {

                $myUser = User::find($request->id);

                return response()->json([
                    'success' => true,
                    'message' => 'Success',
                    'balance' => $myUser->balance,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Not User',
                    'balance' => 0,
                ]);
            }
        }
    }
}
