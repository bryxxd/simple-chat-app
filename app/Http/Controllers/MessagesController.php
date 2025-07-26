<?php

namespace App\Http\Controllers;

use App\Models\Messages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
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
            'message' => ['required', 'string', 'max:255'],
        ]);

        Messages::insert([
            'from_user_id' => Auth::id(),
            'to_user_id' => $request->to_user_id,
            'content' => $request->message,
        ]);

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Messages $messages, Request $request)
    {
        //
        Messages::where('from_user_id', Auth::id())->orWhere('to_user_id', $request->to_user_id)->get();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Messages $messages)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Messages $messages)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Messages $messages)
    {
        //
    }
}
