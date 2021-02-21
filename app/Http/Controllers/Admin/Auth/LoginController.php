<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Traits\GoogleTwoFactor;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    use AuthenticatesUsers, GoogleTwoFactor;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/google-two-factor-code';

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * username column should be used for authentication instead of email column
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Guard to be used for authentication
     */
    protected function guard()
    {
        return Auth::guard('web');
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        $this->guard()->logout();

        request()->session()->invalidate();

        return redirect('/admin');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->type != 1) {
            $this->guard()->logout();
            request()->session()->invalidate();

            return redirect()->back()->with([
                'type' => 'error',
                'message' => 'Not eligible for login.'
            ]);
        }

        if ($user->google2fa_secret) {
            $this->guard()->logout();
            request()->session()->invalidate();

            request()->session()->put('user_id', $user->id);

            return redirect()->route('admin.google_two_factor_code');
        }
    }
}
