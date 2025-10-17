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

        $insertedChat = ChatRoom::create([
            'from_user_id' => Auth::id(),
            'to_user_id' => $request->to_user_id,
            'content' => $request->message,
        ]);

        // Event data
        $eventData = [
            'id' => $insertedChat->id,
            'from_user_id' => Auth::id(),
            'to_user_id' => $request->to_user_id,
            'content' => $request->message,
            'created_at' => $insertedChat->created_at,
        ];

        // Dispatch the event to notify users about the new message
        event(new NewMessageEvent(
            $eventData['id'],
            $eventData['from_user_id'],
            $eventData['to_user_id'],
            $eventData['content'],
            $eventData['created_at']
        ));

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $to_user_id)
    {
        // Validate the to_user_id
        if (!$to_user_id || !is_numeric($to_user_id)) {
            return response()->json(['error' => 'Invalid user ID'], 400);
        }

        // Get pagination parameters
        $page = (int) $request->get('page', 1);
        $perPage = (int) $request->get('per_page', 50);
        
        // Ensure valid pagination values
        $page = max(1, $page);
        $perPage = min(100, max(10, $perPage)); // Limit between 10-100 messages per page

        // Build the base query for messages between authenticated user and target user
        $baseQuery = ChatRoom::where(function ($query) use ($to_user_id) {
            $query->where('from_user_id', Auth::id())
                ->where('to_user_id', $to_user_id);
        })
        ->orWhere(function ($query) use ($to_user_id) {
            $query->where('from_user_id', $to_user_id)
                ->where('to_user_id', Auth::id());
        });

        // Get total count (without pagination)
        $totalMessages = $baseQuery->count();

        // Calculate offset for pagination
        $offset = ($page - 1) * $perPage;

        // Get paginated results ordered by creation date (newest first for chat display)
        $messages = $baseQuery->orderBy('created_at', 'desc')
            ->offset($offset)
            ->limit($perPage)
            ->get();

        // Get participant details
        $participant = User::select('id', 'first_name', 'last_name', 'avatar')
            ->where('id', $to_user_id)
            ->first();

        if (!$participant) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json([
            'messages' => $messages,
            'totalMessages' => $totalMessages,
            'participant' => $participant,
            'currentPage' => $page,
            'perPage' => $perPage,
            'hasMorePages' => ($offset + $perPage) < $totalMessages
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