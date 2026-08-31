<?php
namespace App\Http\Controllers;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
class AuthController extends Controller
{
    public function create(): View { return view('auth.login'); }
    public function store(LoginRequest $request): RedirectResponse
    {
        $username = strtolower(trim($request->validated('username')));
        $user = User::firstOrCreate(['username'=>$username]);
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->intended(route('dashboard'))->with('status', 'Welcome, '.$user->username.'!');
    }
    public function destroy(): RedirectResponse
    {
        Auth::logout(); request()->session()->invalidate(); request()->session()->regenerateToken();
        return redirect()->route('login')->with('status','You have been logged out.');
    }
}
