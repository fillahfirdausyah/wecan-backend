<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Campaign;
use App\Models\Transaction;
use App\Models\Comment;
use App\Models\User;
use App\Models\Wallet;


class CampaignController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'findCampaign']]);
    }

    public function index() {
        $data = Campaign::all();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    public function makeCampaign(Request $request) {

        // return response()->json($request->cover->extension());
        
        $this->validate($request, [
            'user_id'       => 'required',
            'title'         => 'required',
            'description'   => 'required',
            'cover'         => 'required | mimes:jpeg,jpg,png',
            'goal'          => 'required',
            'collected'     => 'required',
            'over'          => 'required',
            'url'           => 'required',
            // 'status'               => 'required',
        ]);

        $coverName = 'null';

        $coverName = 'Campaign-' . $request->url . '-' . time() .  '.' . $request->cover->extension();
        $request->cover->move('image/campaign', $coverName);

        $data = new Campaign;
        $data->user_id      = $request->user_id;
        $data->title        = $request->title;
        $data->description  = $request->description;
        $data->over         = $request->over;
        $data->goal         = $request->goal;
        $data->collected    = $request->collected;
        $data->status       = false;
        $data->cover        = $coverName;
        $data->url          = $request->url;
        $data->save();

        return response()->json([
            'status' => 'Success', 
            'message' => 'Berhasil Membuat Campaign',
            'data' => $data,
        ],200);
        
    }


    public function myCampaign() {
        $user = auth()->user();
        $campaign = User::find($user->id)->campaign;

        return response()->json($campaign, 200);
    }


    public function findCampaign($url) {
        $data = Campaign::where('url', $url)->first();
        $user = Campaign::find($data->id)->user;
        $comment = Campaign::join('comment', 'campaign.id', '=', 'comment.campaign_id')
                                ->join('users', 'users.id', '=', 'comment.user_id')
                                ->get(['users.username', 'comment.*']);

        return response()->json([
            'campaign' => $data,
            'user' => $user,
            'comment' => $comment,
        ], 200,);

    }


    public function payDonation(Request $request, $url) {
        
        $user = auth()->user();
        $campaign = Campaign::where('url', $url)->first();
        $currentCollected = $campaign->collected;
        $newCollected = $request->amount + $currentCollected;
        $campaign->collected = $newCollected;
        $campaign->save();

        $comment = new Comment;
        $comment->user_id = $user->id;
        $comment->campaign_id = $campaign->id;
        $comment->content = $request->content;
        $comment->save();

        $transaction = new Transaction;
        $transaction->user_id      = $user->id;
        $transaction->campaign_id  = $campaign->id;
        $transaction->amount       = $request->amount;
        $transaction->save();

        $wallet = Wallet::where('user_id', $user->id)->first();
        $newBallance = $wallet->balance - $request->amount;
        $wallet->balance = $newBallance;
        $wallet->save();



        return response()->json([
            'status' => 'success',
        ], 200);

    }
}
