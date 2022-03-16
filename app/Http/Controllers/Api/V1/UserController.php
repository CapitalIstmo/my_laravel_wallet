<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    use ApiHelpers; // <---- Using the apiHelpers Trait

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($this->isAdmin($request->user())) {
            return UserResource::collection(User::latest()->paginate(10));
        }
        return $this->onError(401, 'Unauthorized Access only for admin');
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

    public function encontrarUsuario(Request $request)
    {
        if ($this->isAdmin($request->user())) {
            if ($request->type_search != "" || $request->data != "") {

                switch ($request->type_search) {
                    case 'ID':
                        # code...
                        $count = User::where('id', '=', $request->data)->count();
                        break;
                    case 'EMAIL':
                        # code...
                        $count = User::where('email', '=', $request->data)->count();
                        break;
                    case 'PHONE':
                        # code...
                        $count = User::where('phone', '=', $request->data)->count();
                        break;
                }

                if ($count > 0) {

                    $user = ";";

                    switch ($request->type_search) {
                        case 'ID':
                            # code...
                            $user = DB::table('users')->where('id', '=', $request->data)->get(['id','name','email','type_user','phone','type_doc','email_verified_at','created_at']);
                            break;
                        case 'EMAIL':
                            # code...
                            $user = DB::table('users')->where('email', '=', $request->data)->get(['id','name','email','type_user','phone','type_doc','email_verified_at','created_at']);
                            break;
                        case 'PHONE':
                            # code...
                            $user = DB::table('users')->where('phone', '=', $request->data)->get(['id','name','email','type_user','phone','type_doc','email_verified_at','created_at']);
                            break;
                    }

                    return response()->json([
                        'success' => true,
                        'message' => 'The user is found',
                        'data' => $user,
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
        return $this->onError(401, 'Unauthorized Access only for admin');

    }

    public function editarPerfil()
    {

    }
}
