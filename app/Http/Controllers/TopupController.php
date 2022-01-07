<?php

namespace App\Http\Controllers;

use App\Models\Topup;
use App\Models\Wallet;

class TopupController extends Controller
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

    public function getPendingTopup() {
        $topup = Topup::where('status', 'pending')
                 ->join('users', 'users.id', '=', 'topup.user_id')->get(['users.name', 'topup.*']);

        return response()->json($topup, 200);
    }


    public function acceptTopup($id) {
        $topup = Topup::find($id);
        $wallet = Wallet::find($topup->user_id);
        $currentBalance = $wallet->balance;
        $newBalance = $currentBalance + $topup->amount;
        $wallet->balance = $newBalance;
        $wallet->save();
        $topup->status = 'accpeted';
        $topup->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Topup diterima',
        ], 200);
    }

    public function getTopupTransaction() {
        $user = auth()->user();
        $topup = Topup::where('user_id', $user->id)->get();

        return response()->json($topup, 200);
    }
    //
}
