<?php

namespace App\Http\Controllers\auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginForm()
    {
        if (Helper::hasLoggedIn()) {
            return view('NiceAdmin.index');
        }
        return view('NiceAdmin.login');
    }

    public function login(Request $request)
    {
        $inputs = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        try {
            if (Auth::attempt($inputs)) {
                $request->session()->regenerate();

                $authenticatedUser = Auth::user();
                session()->put([
                    'id' => $authenticatedUser->id,
                    'roleId' => $authenticatedUser->role_id,
                    'username' => $authenticatedUser->firstname . ' ' . $authenticatedUser->lastname
                ]);

                $role = User::where('role_id', session('roleId'))->with('roles')->firstOrFail()->roles->role;
                session()->put([
                    'role' => $role
                ]);

                if (session('roleId') == 1)
                    return redirect()->route('home')->with('success', 'Login successfully!');
                if (session('roleId') == 2)
                    return redirect()->route('st-home')->with('success', 'Login successfully!');
                if (session('roleId') == 3)
                    return redirect()->route('cs-home')->with('success', 'Login successfully!');
                if (session('roleId') == 4)
                    return redirect()->route('dof-home')->with('success', 'Login successfully!');
                if (session('roleId') == 5 || session('roleId') == 6)
                    return redirect()->route('dos-home')->with('success', 'Login successfully!');
            }
            $errorMessage = __('auth.failed');
            return back()->withInput()->with('error', $errorMessage);
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        return redirect()->intended('/login');
    }
}
