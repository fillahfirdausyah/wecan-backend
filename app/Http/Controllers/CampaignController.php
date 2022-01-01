<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        // return response()->json($request);
        
        $this->validate($request, [
            'user_id'              => 'required',
            'title'                => 'required',
            'description'          => 'required',
            'campaign_start'       => 'required',
            'campaign_goal'        => 'required',
            'campaign_collected'   => 'required',
            // 'status'               => 'required',
        ]);

        $data = new Campaign;
        $data->user_id            = $request->user_id;
        $data->title              = $request->title;
        $data->description        = $request->description;
        $data->campaign_start     = $request->campaign_start;
        $data->campaign_goal      = $request->campaign_goal;
        $data->campaign_collected = $request->campaign_collected;
        $data->status             = false;
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
