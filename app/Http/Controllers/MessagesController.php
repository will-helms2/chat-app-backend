<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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

        $this->validate($request, [
          'channel_id' => 'required',
          'user_id' => 'required',
          'content' => 'required|String',
          'is_file' => 'required|Boolean',
        ]);

        $channel_id = $request->input('channel_id');
        $user_id = $request->input('user_id');
        $content = $request->input('content');
        $is_file = $request->input('is_private');

        #// TODO: make sure the user has access to this channel

        $message = new Message([
          'channel_id' => $channel_id,
          '$user_id' => $user_id,
          'content' => $content,
          'is_file' => $is_file
        ]);

        if ($message->save()) {
          return response()->json(compact('message'), 201);
        }

        $response = [
          'error' => 'An error has occured!'
        ];
        return response()->json($response, 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
