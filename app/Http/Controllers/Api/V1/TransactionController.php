<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\TransactionResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return TransactionResource::collection(DB::table('transactions')->where('payable_id', $request->id)->orderBy('id', 'desc')->get());
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
    public function show($id)
    {
        //
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

    public function makeTransfer(Request $request)
    {
        $rules = [
            //'name_wallet' => 'required',
            //'token' => 'required',
            'id_bussiness' => 'required',
            'id_payer' => 'required',
            'amount' => 'required'
        ];

        $input = $request->only('id_bussiness', 'id_payer','amount');

        $validator = Validator::make($input, $rules);

        //SI SON VALIDOS TODO OK
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->getMessageBag()]);
        } else {

            //VERIFICAMOS QUE SEAN VALIDOS
            $theBussiness = DB::table('users')->where(['id' => $request->id_bussiness, 'type_user' => 'E'])->first();

            $thePayer = DB::table('users')->where(['id' => $request->id_payer, 'type_user' => 'U'])->first();

            if ($theBussiness != null && $thePayer != null) {
                //CONSULTAMOS EL BALANCE DEL PAGADOR
                $bussiness = User::find($request->id_bussiness);

                $payer = User::find($request->id_payer);

                //SI BALANCE ES MAYOR QUE PAGO OK
                if ($bussiness->getKey() !== $payer->getKey()) {
                    //REALIZAMOS EL TRANSFER
                    if($payer->balance > 0){
                        $payer->transfer($bussiness, $request->amount);
                        return response()->json([
                            'success' => true,
                            'message' => 'Pay Success',
                        ]);
                    }else{
                        return response()->json([
                            'success' => false,
                            'message' => 'Balance not available.',
                        ]);
                    }
                } else {
                    // SI NO, VALIO CHETOS.
                    //MOSTRAMOS ERROR
                    return response()->json([
                        'success' => false,
                        'message' => 'Operation not Valid',
                    ]);
                }


            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Bussiness or Payer Not Valid',
                ]);
            }
        }
    }
}
