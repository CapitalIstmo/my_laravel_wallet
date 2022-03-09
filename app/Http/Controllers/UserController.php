<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    public function depositar()
    {
        //OBTENEMOS EL USUARIO
        $user = User::find(10);

        //SE LE PUEDE DEPOSITAR
        $user->deposit(50000);

        //IMPRIMIMOS EL OBJETO
        dd($user->balance); // 0
    }

    public function transferir()
    {
        $primero = User::first();

        $segundo = User::orderBy('id', 'desc')->first(); // last user

        $primero->getKey() !== $segundo->getKey(); // true

        $primero->transfer($segundo, 5);

        dd($primero->balance); // 0

        dd($segundo->balance); // 0
    }

    public function retirar()
    {
        //OBTENEMOS EL USUARIO
        $user = User::first();

        //SE LE PUEDE RETIRAR (PERO GENERA ERROR SI NO TENEMOS SALDO SUFICIENTE)
        $user->withdraw(1);

        //SE LE PUEDE RETIRAR (PERO QUEDA EN NEGATIVOS SI NO TENEMOS SALDO)
        $user->forceWithdraw(19, ['description' => 'Prestamo Urgente']);

        //IMPRIMIMOS EL OBJETO
        dd($user->balance); // 0
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
}
