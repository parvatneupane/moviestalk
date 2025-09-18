<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // Show Login Form
    public function loginForm()
    {
        // Redirect logged-in users to dashboard
    
        return view('user.login');
    }

public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
        $request->session()->regenerate(); // Prevent 419 CSRF issues
        $user = Auth::user();

        if ($user->role == 'admin') {
          
            $usersCount = User::count();
            $moviesCount = Movie::count();

            return view('admin.adminblade.dashboard', [
                'users' => $usersCount,
                'movies' => $moviesCount,
                'user' => $user,
            ]);
        }

        return redirect()->route('home')->with('success', 'Login successful!');
    }

    return back()->with('error', 'Credentials did not match.');
}





  
    public function registerForm()
    {
        
        return view('user.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.unique' => 'This email address is already registered. Please choose another.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($user) {
            return redirect()->route('user.login.form')->with('success', 'Registration successful! Please login.');
        } else {
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

   
    public function dashboard()
    {
        $user = Auth::user();
        return view('user.home', compact('user'));
    }

  
    public function showForgotPasswordForm()
    {
        return view('user.forgot-password');
    }

  
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        } else {
            return back()->withErrors(['email' => __($status)]);
        }
    }

   
    public function showResetPasswordForm($token)
    {
        return view('user.reset-password', ['token' => $token]);
    }

   
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required|string',
        ]);

        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
            ])->save();
        });

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('user.login.form')->with('status', 'Password has been reset!');
        } else {
            return back()->withErrors(['email' => 'The reset token is invalid or has expired.']);
        }
    }

  
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home')->with('success', 'Logged out successfully!');
    }

  
    public function index()
    {
        if (Auth::user()->role != 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $users = User::orderBy('id', 'desc')->get();
        return view('admin.adminblade.users', compact('users'));
    }

   public function updateProfile(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . Auth::id(),
        'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);

    $user = Auth::user();

    $user->name = $request->name;
    $user->email = $request->email;

    if ($request->hasFile('avatar')) {


    if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
        Storage::disk('public')->delete($user->avatar);
    }

   
    $avatarPath = $request->file('avatar')->store('avatars', 'public');

   
    $user->avatar = $avatarPath;  
}

    $user->save();

    return back()->with('success', 'Profile updated successfully!');
}


    // Update Password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password updated successfully!');
    }




//admin only

//form update show
    public function edit($id)
    {
    
        $user = User::findOrFail($id);
        return view('admin.adminblade.updateuser', compact('user'));
    }


    // update user
   public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    // Validate input
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'role' => 'required|string',
    ]);

    // Update user
    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
    ]);

    // Redirect back to users list with success message
    return redirect('/admin/users')->with('success', 'User updated successfully!');
}

 //delete user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('/admin/users')->with('success', 'User deleted successfully');
    }


}
