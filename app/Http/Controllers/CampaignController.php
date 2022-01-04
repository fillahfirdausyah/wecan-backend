<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Campaign;
use App\Models\Transaction;

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


    public function findCampaign($id) {
        $data = Campaign::find($id);
    

        return response()->json($data, 200,);
    }


    public function payDonation(Request $request) {

        $data = new Transaction;
        $data->user_id      = $request->user_id;
        $data->campaign_id  = $request->campaign_id;
        $data->amount       = $request->amount;
        $data->save();

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ], 200);

    }
}
