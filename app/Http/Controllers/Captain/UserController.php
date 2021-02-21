<?php

namespace App\Http\Controllers\Captain;

use App\Http\Controllers\Controller;
use App\Traits\GoogleTwoFactor;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use GoogleTwoFactor;
    /**
     * Displays the dashboard page to the captaion
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        return view('captain.dashboard');
    }

    /**
     * Displays the profile page to the captain
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $user = Auth::guard('captain')->user();

        return view('captain.profile', compact('user'));
    }

    /**
     * Updates captain profile details
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function profileUpdate()
    {
        $validatedData = request()->validate(User::profileValidationRules());

        $user = Auth::guard('captain')->user();

        $user->update($validatedData);

        return redirect()->back()->with(['type' => 'success', 'message' => 'Profile updated successfully']);
    }

    /**
     * Updates captain password
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function passwordUpdate()
    {
        $validatedData = request()->validate(User::passwordValidationRules());

        $user = Auth::guard('captain')->user();

        if (Hash::check(request('current_password'), $user->password)) {
            $user->password = bcrypt(request('password'));
            $user->save();

            return redirect()->back()->with(['type' => 'success', 'message' => 'Password updated successfully']);
        }

        return redirect()->back()->with(['type' => 'error', 'message' => 'Incorrect current password']);
    }
}
