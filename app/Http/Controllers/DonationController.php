<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class DonationController extends Controller
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

    public function donationHistory() {
        $user = auth()->user();
        
        $transaction = Transaction::where('transactions.user_id', $user->id)
                       ->join('campaign', 'campaign.id', '=', 'transactions.campaign_id')
                       ->join('users', 'users.id', '=', 'campaign.user_id')
                       ->get(['users.name', 'campaign.title', 'campaign.cover', 'transactions.*']);

        
        return response()->json($transaction, 200);
                       

    }

    //
}
