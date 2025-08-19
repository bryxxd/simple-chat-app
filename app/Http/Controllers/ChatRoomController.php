<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ChatRoom;
use Illuminate\Http\Request;
use App\Events\NewMessageEvent;
use Illuminate\Support\Facades\Auth;

class ChatRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'message' => ['required', 'string'],
        ]);

        ChatRoom::create([
            'from_user_id' => Auth::id(),
            'to_user_id' => $request->to_user_id,
            'content' => $request->message,
        ]);

        // Dispatch the event to notify users about the new message
        event(new NewMessageEvent(Auth::id(), $request->to_user_id, $request->message));

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show($to_user_id)
    {
        //
        $chatQuery = ChatRoom::where(function ($query) use ($to_user_id) {
            $query->where('from_user_id', Auth::id())
                ->where('to_user_id', $to_user_id);
        })
            ->orWhere(function ($query) use ($to_user_id) {
                $query->where('from_user_id', $to_user_id)
                    ->where('to_user_id', Auth::id());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $to_user_details = User::select('id','first_name', 'last_name','avatar')->where('id', $to_user_id)->first();

        return response()->json([
            'messages' => $chatQuery,
            'to_user_details' => $to_user_details,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChatRoom $chatRoom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChatRoom $chatRoom)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChatRoom $chatRoom)
    {
        //
    }
}
