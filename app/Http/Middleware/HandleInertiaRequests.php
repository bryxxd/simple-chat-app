<?php

namespace App\Http\Middleware;

use Inertia\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $interactedUsers = [];
        
        if (Auth::check()) {
            $userId = Auth::id();
            $interactedUsers = DB::table('users')
                ->whereExists(function ($query) use ($userId) {
                    $query->select(DB::raw(1))
                        ->from('chat_rooms')
                        ->where(function ($q) use ($userId) {
                            $q->where('chat_rooms.from_user_id', $userId)
                                ->whereColumn('chat_rooms.to_user_id', 'users.id');
                        })
                        ->orWhere(function ($q) use ($userId) {
                            $q->where('chat_rooms.to_user_id', $userId)
                                ->whereColumn('chat_rooms.from_user_id', 'users.id');
                        });
                })
                ->leftJoin('chat_rooms as last_message', function ($join) use ($userId) {
                    $join->on('last_message.id', '=', DB::raw("(
                        SELECT id FROM chat_rooms 
                        WHERE (
                            (from_user_id = {$userId} AND to_user_id = users.id) OR 
                            (from_user_id = users.id AND to_user_id = {$userId})
                        ) 
                        ORDER BY created_at DESC 
                        LIMIT 1
                    )"));
                })
                ->select('users.id', 'users.first_name', 'users.last_name', 'users.username', 'users.avatar', 'last_message.content', 'last_message.created_at')
                ->orderBy('last_message.created_at', 'desc')
                ->where('users.id', '!=', $userId)
                ->get();
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'success' => $request->session()->get('success'),
                'info' => $request->session()->get('info')
            ],
            'users' => Auth::check() ? User::select('id', 'first_name', 'last_name', 'email', 'avatar')->where('id', '!=', Auth::id())->get() : [],
            'interactedUsers' => $interactedUsers,
        ];
    }
}
