<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Wallet;
use App\Models\User;

class WalletController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function getWallet() {
        $user = auth()->user();
        $wallet = User::find($user->id)->wallet;

        return response()->json($wallet, 200);
    }

    public function topUp(Request $request) {
        $user  = auth()->user();
        $wallet = Wallet::where('user_id', $user->id)->first();
        $wallet->balance = $request->balance;
        $wallet->save();

        return response()->json([
            'status' => 'Success',
            'data' => $wallet,
        ], 200);
    }

    public function makeWallet() {
        $user = auth()->user();
        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->balance = 0;
        $wallet->save();

        return response()->json([
            'status' => 'Berhasil'
        ], 200);
    }

    //
}
