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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'message' => ['required', 'string'],
        ]);

        $insertedChat = ChatRoom::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->message,
        ]);

        // Event data
        $eventData = [
            'id' => $insertedChat->id,
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->message,
            'created_at' => $insertedChat->created_at,
        ];

        // Dispatch the event to notify users about the new message
        event(new NewMessageEvent(
            $eventData['id'],
            $eventData['sender_id'],
            $eventData['receiver_id'],
            $eventData['content'],
            $eventData['created_at'],
        ));

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $receiver_id)
    {
        // Validate the receiver_id
        if (!$receiver_id || !is_numeric($receiver_id)) {
            return response()->json(['error' => 'Invalid user ID'], 400);
        }

        // Get pagination parameters
        $page = (int) $request->get('page', 1);
        $perPage = (int) $request->get('per_page', 50);

        // Ensure valid pagination values
        $page = max(1, $page);
        $perPage = min(100, max(10, $perPage)); // Limit between 10-100 messages per page

        // Build the base query for messages between authenticated user and target user
        $baseQuery = ChatRoom::where(function ($query) use ($receiver_id) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $receiver_id);
        })
            ->orWhere(function ($query) use ($receiver_id) {
                $query->where('sender_id', $receiver_id)
                    ->where('receiver_id', Auth::id());
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
            ->where('id', $receiver_id)
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
     * Mark messages as read.
     */
    public function markedAsRead(Request $request)
    {
        //
        $messagesIds = $request->get('message_ids', []);

        foreach ($messagesIds as $messageId) {
            $messageId = ChatRoom::find($messageId)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }
        return response()->json(['message' => 'Messages marked as read', 'message_ids' => $messagesIds]);
    }
}
