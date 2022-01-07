<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Wallet;
use App\Models\User;
use App\Models\Topup;

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
        $topup = new Topup;
        $topup->user_id = $request->user_id;
        $topup->amount = $request->amount;
        $topup->status = 'pending';
        $topup->save();

        return response()->json([
            'status' => 'succes',
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
