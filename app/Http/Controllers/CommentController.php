<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function addComment(Request $request) {

        $this->validate($request, [
            'user_id'       => 'required',
            'campaign_id'   => 'required',
            'content'       => 'required'
        ]);


        $data = new Comment;
        $data->user_id = $request->user_id;
        $data->campaign_id = $request->campaign_id;
        $data->content = $request->content;
        $data->save();

        return response()->json([
            'status' => 'Success'
        ], 200);
    }

    //
}
