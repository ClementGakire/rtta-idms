<?php

namespace App\Http\Controllers;

use App\UserSession;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $sessions = UserSession::with('user')->orderBy('date', 'desc')->get();
        return view('users.sessions.index')->with('sessions', $sessions);
    }

    public function create()
    {
        $users = User::all();
        return view('users.sessions.create')->with('users', $users);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        UserSession::create([
            'user_id' => $request->input('user_id'),
            'date' => $request->input('date'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'notes' => $request->input('notes'),
        ]);

        return redirect('/user-sessions')->with('success', 'Session recorded');
    }
}
