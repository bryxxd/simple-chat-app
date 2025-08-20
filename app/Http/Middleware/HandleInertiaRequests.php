<?php

namespace App\Http\Middleware;

use Inertia\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User;
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
        $interactedUsers = User::join('chat_rooms', function ($join) {
            $join->on('users.id', '=', 'chat_rooms.to_user_id')
                ->where('chat_rooms.from_user_id', '=', Auth::id())
                ->orOn('users.id', '=', 'chat_rooms.from_user_id')
                ->where('chat_rooms.to_user_id', '=', Auth::id());
        })
            ->select('users.id', 'users.first_name', 'users.last_name', 'users.username', 'users.avatar')
            ->where('users.id', '!=', Auth::id())
            ->distinct()
            ->get();

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'success' => $request->session()->get('success'),
                'info' => $request->session()->get('info')
            ],
            'users' => Auth::check() ? User::select('id', 'first_name', 'last_name', 'email', 'avatar')->where('id', '!=', auth()->id())->get() : [],
            'interactedUsers' => Auth::check() ? $interactedUsers : [],
        ];
    }
}
