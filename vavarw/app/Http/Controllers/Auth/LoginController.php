<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UserSession;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * After the user is authenticated, create a user session record.
     */
    protected function authenticated(Request $request, $user)
    {
        // set start_time and a temporary end_time equal to start_time (will be updated on logout)
        $time = now();
        UserSession::create([
            'user_id' => $user->id,
            'date' => $time->toDateString(),
            'start_time' => $time->format('H:i:s'),
            'end_time' => $time->format('H:i:s'),
            'notes' => null,
        ]);
    }

    /**
     * Log the user out of the application and update the session end_time.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $session = UserSession::where('user_id', $user->id)->orderBy('created_at', 'desc')->first();
            if ($session) {
                $session->end_time = now()->format('H:i:s');
                $session->save();
            }
        }

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
