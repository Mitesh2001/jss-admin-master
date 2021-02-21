<?php

namespace App\Traits;

use App\User;
use PragmaRX\Google2FA\Google2FA;

trait GoogleTwoFactor
{
    protected $routePrefix;

    public function __construct()
    {
        $this->routePrefix = request()->is('captain/*') ? 'captain' : 'admin';
    }

    /**
     * Generates the qr code for activate google two factor.
     *
     * @return string link of qr code image
     */
    public function googleTwoFactor()
    {
        $google2fa = new Google2FA();
        $user = auth()->user();

        $this->setupSecret($user, $google2fa->generateSecretKey());

        $google2fa->setAllowInsecureCallToGoogleApis(true);

        return $google2fa->getQRCodeGoogleUrl(
            config('app.name'),
            $user->username,
            $user->google2fa_secret
        );
    }

    /**
     * Setup google two factor secret
     *
     * @param \App\User $user
     * @param string $google secret key
     * @return void
     **/
    public function setupSecret($user, $key)
    {
        $user->google2fa_secret = $key;
        $user->save();
    }

    /**
     * Verify user google two factor.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function googleTwoFactorVerify()
    {
        $google2fa = new Google2FA();
        $user = auth()->user();

        if ($google2fa->verifyKey($user->google2fa_secret, request('qr_code'))) {
            return redirect()->back()->with([
                'type' => 'success',
                'message' => 'Google two factor authentication activated successfully.'
            ]);
        }

        $user->google2fa_secret = null;
        $user->save();

        return redirect()->back()->withErrors([
            'error' => 'Incorrect code.'
        ]);
    }

    /**
     * Disabled google two factor authentication.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disabledGoogleTwoFactor()
    {
        $user = auth()->user();

        $user->google2fa_secret = null;
        $user->save();

        return redirect()->back()->with([
            'type' => 'success',
            'message' => 'Google two factor authentication disabled successfully.'
        ]);
    }

    /**
     * Display for for verification code.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function googleTwoFactorCode()
    {
        return view($this->routePrefix. '.auth.google_two_factor');
    }

    /**
     * Verify code of google two factor authentication.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function googleTwoFactorCodeVerify()
    {
        $google2fa = new Google2FA();
        $user = User::find(request()->session()->get('user_id'));

        if ($google2fa->verifyKey($user->google2fa_secret, request('qr_code'))) {
            $this->guard()->login($user, true);

            return redirect()->route($this->routePrefix . '.dashboard');
        }

        request()->session()->forget('user_id');

        return redirect()->route($this->routePrefix . '.login')->withErrors([
            'error' => 'Incorrect code.'
        ]);
    }
}
