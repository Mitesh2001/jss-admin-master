<?php

namespace App\Http\Controllers\Admin;

use App\Discipline;
use App\Http\Controllers\Controller;
use App\Individual;
use App\Traits\GoogleTwoFactor;
use App\User;
use Freshbitsweb\Laratables\Laratables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use GoogleTwoFactor;

    /**
     * Displays the dashboard page to the admin
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    /**
     * Displays the profile page to the admin
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $user = Auth::guard('web')->user();

        return view('admin.profile', compact('user'));
    }

    /**
     * Updates admin profile details
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function profileUpdate()
    {
        $validatedData = request()->validate(User::profileValidationRules());

        $user = Auth::guard('web')->user();

        $user->update($validatedData);

        return redirect()->back()->with(['type' => 'success', 'message' => 'Profile updated successfully']);
    }

    /**
     * Updates admin password
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function passwordUpdate()
    {
        $validatedData = request()->validate(User::passwordValidationRules());

        $user = Auth::guard('web')->user();

        if (Hash::check(request('current_password'), $user->password)) {
            $user->password = bcrypt(request('password'));
            $user->save();

            return redirect()->back()->with(['type' => 'success', 'message' => 'Password updated successfully']);
        }

        return redirect()->back()->with(['type' => 'error', 'message' => 'Incorrect current password']);
    }

    /**
     * Display a list of captains
     *
     * @return \Illuminate\Http\Response
     **/
    public function index()
    {
        return view('admin.users.index');
    }

    /**
     * Returns the data for datatables.
     *
     * @return json
     */
    public function datatables()
    {
        return Laratables::recordsOf(User::class);
    }

    /**
     * Display a add page of user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $individuals = Individual::query()
            ->with('membership:id,individual_id,membership_number')
            ->whereHas('membership')
            ->get()
        ;

        $disciplines = Discipline::getList();

        return view('admin.users.add', compact('individuals', 'disciplines'));
    }

    /**
     * Store newly created user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $validatedData = request()->validate(User::validationRules());

        unset($validatedData['discipline_ids']);
        $validatedData['type'] = 2;
        $validatedData['password'] = bcrypt($validatedData['password']);

        $user = User::create($validatedData);
        $user->disciplines()->attach(request('discipline_ids'));

        return redirect()->route('admin.users.index')->with([
            'type' => 'success',
            'message' => 'Captain added successfully.'
        ]);
    }

    /**
     * Edit specified user.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $individuals = Individual::query()
            ->with('membership:id,individual_id,membership_number')
            ->whereHas('membership')
            ->get()
        ;
        $disciplines = Discipline::getList();

        return view('admin.users.edit', compact(['user', 'individuals', 'disciplines']));
    }

    /**
     * Update specified user.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(User $user)
    {
        $validatedData = request()->validate(User::validationRules($user->id));
        $validatedData['password'] = bcrypt($validatedData['password']);
        $user->update($validatedData);

        return redirect()->route('admin.users.index')->with([
            'type' => 'success',
            'message' => 'Captain updated successfully.'
        ]);
    }

    /**
     * Deletes the user
     *
     * @param \App\User $user
     * @return array
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with([
            'type' => 'success',
            'message' => 'Captain deleted successfully.'
        ]);
    }
}
