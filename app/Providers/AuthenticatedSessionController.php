<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function store(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Redirect based on user role or any custom logic
            if (Auth::user()->is_admin) {
                return redirect()->route('admin.dashboard'); // Redirect to admin dashboard
            }
            
            return redirect()->intended(RouteServiceProvider::HOME); // Default route (e.g., /movies)
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }
}
