<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route; // Ensure that Route is imported

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication services to redirect users
     * after login.
     *
     * @var string
     */
    public const HOME = '/dashboard'; // Default page after login

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        // Register routes for your application.
        $this->routes(function () {
            // Define the route registration within the web middleware group.
            Route::middleware('web') // Web middleware group (session, CSRF, etc.)
                ->group(base_path('routes/web.php')); // Load routes from 'routes/web.php'
        });
    }
}
