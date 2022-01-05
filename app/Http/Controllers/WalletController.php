<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Wallet;

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

    public function topUp(Request $request) {
        $user  = auth()->user();
        $wallet = new Wallet;
        $wallet->user_id = $request->user_id;
        $wallet->balance = $request->balance;
        $wallet->save();

        return response()->json([
            'status' => 'Success',
            'data' => $wallet,
        ], 200);
    }

    //
}
