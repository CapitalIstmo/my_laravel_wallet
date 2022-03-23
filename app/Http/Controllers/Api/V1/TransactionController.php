<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Resources\V1\TransactionResource;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TransactionController extends Controller
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
            return TransactionResource::collection(Transaction::latest()->paginate(15));
        }

        return $this->onError(401, 'Unauthorized Access only for admin');return TransactionResource::collection(DB::table('transactions')->where('payable_id', $request->id)->orderBy('id', 'desc')->get());
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
            'amount' => 'required',
        ];

        $input = $request->only('id_bussiness', 'id_payer', 'amount');

        $validator = Validator::make($input, $rules);

        //SI SON VALIDOS TODO OK
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->getMessageBag()]);
        } else {

            //VERIFICAMOS QUE SEAN VALIDOS
            $theBussiness = DB::table('users')->where(['id' => $request->id_bussiness])->first();

            $thePayer = DB::table('users')->where(['id' => $request->id_payer])->first();

            if ($theBussiness != null && $thePayer != null) {
                //CONSULTAMOS EL BALANCE DEL PAGADOR
                $bussiness = User::find($request->id_bussiness);

                $payer = User::find($request->id_payer);

                //SI BALANCE ES MAYOR QUE PAGO OK
                if ($bussiness->getKey() !== $payer->getKey()) {
                    //REALIZAMOS EL TRANSFER
                    if ($payer->balance > $request->amount) {
                        $payer->transfer($bussiness, $request->amount);
                        return response()->json([
                            'success' => true,
                            'message' => 'Pay Success',
                        ]);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Tu balance no alcanza para esta operación.',
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

    public function makeOrderPay(Request $request)
    {
        if ($request->amount != null && $request->id_bussiness != null) {
            return response()->json([
                'success' => true,
                'order' => base64_encode($request->amount) . "|" . base64_encode($request->id_bussiness),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Bussiness or Payer Not Valid',
            ]);
        }
    }

    public function viewMyTransactions(Request $request)
    {
        return TransactionResource::collection(DB::table('transactions')->where('payable_id', $request->id)->orderBy('id', 'desc')->get());
    }

    public function makeTransferByPhone(Request $request)
    {
        if ($request->phone != "" && $request->amount != "" && $request->id_payer != "") {

            $theDestiny = DB::table('users')->where(['phone' => $request->phone])->first();

            $theBussiness = DB::table('users')->where(['id' => $request->id_payer])->first();

            if ($theDestiny != null && $theBussiness != null) {

                $bussiness = User::find($theDestiny->id);

                $payer = User::find($request->id_payer);

                //SI BALANCE ES MAYOR QUE PAGO OK
                if ($bussiness->getKey() !== $payer->getKey()) {
                    //REALIZAMOS EL TRANSFER
                    if ($payer->balance > $request->amount) {
                        $payer->transfer($bussiness, $request->amount);
                        return response()->json([
                            'success' => true,
                            'message' => 'Pay Success',
                        ]);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Tu balance no alcanza para esta operación.',
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
                    'message' => 'Destiny not found.',
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Params not valid.',
            ]);
        }
    }

    public function makeDepositWithMoney(Request $request)
    {
        if ($request->amount != "" && $request->wallet_name != "" && $request->forceWallet != "" && $request->user_name != "") {
            $thePerson = DB::table('users')->where(['name' => $request->user_name])->first();
            if ($thePerson != null) {
                $user = User::find($thePerson->id);
                //echo $user->balance;

                if ($user->hasWallet($request->wallet_name)) {
                    $user->deposit($request->amount,['terminal_id' => $request->terminal_id, 'merchant_id' => $request->merchant_id]);
                    return response()->json([
                        'success' => true,
                        'message' => 'Deposit Success',
                        'transaction' => $user->wallet,
                        'fecha' => date('Y-m-d'),
                        'hora' => date('H:i:s'),
                    ]);
                } else {

                    if ($request->forceWallet == "1") {
                        $wallet = $user->createWallet([
                            'name' => $request->wallet_name,
                            'slug' => Str::slug($request->wallet_name, "-"),
                        ]);

                        $wallet->deposit($request->amount);

                        return response()->json([
                            'success' => true,
                            'message' => 'Deposit Success',
                            'transaction' => $wallet,
                            'fecha' => date('Y-m-d'),
                            'hora' => date('H:i:s'),
                        ]);

                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Name Wallet not found',
                        ]);
                    }
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.',
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Params not valid.',
            ]);
        }
    }
}
