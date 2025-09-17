<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MyListController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReviewController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact/submit', [ContactController::class, 'submit'])->name('contact.submit');
// Add this alias outside of prefix group
Route::get('/login', [UserController::class, 'loginForm'])->name('login');
Route::get('user/movies', [MovieController::class, 'index'])->name('movies');
Route::get('/movies/search', [MovieController::class, 'search'])->name('movies.search');

Route::prefix('user')->group(function () {
  
    Route::get('/login', [UserController::class, 'loginForm'])->name('user.login.form');
    Route::post('/login', [UserController::class, 'login'])->name('user.login');
    
    // Registration Routes
    Route::get('/register', [UserController::class, 'registerForm'])->name('user.register.form');
    Route::post('/register', [UserController::class, 'register'])->name('user.register');
    
    // Password Reset Routes
    Route::get('/forgot-password', [UserController::class, 'showForgotPasswordForm'])->name('user.forgot-password.form');
    Route::post('/forgot-password', [UserController::class, 'sendResetLinkEmail'])->name('user.forgot-password.email');
    Route::get('/reset-password/{token}', [UserController::class, 'showResetPasswordForm'])->name('user.password.reset');
    Route::post('/reset-password', [UserController::class, 'resetPassword'])->name('user.password.update');
    
});


Route::get('user/movie/{movieId}', [MovieController::class, 'show'])->name('movie.detail');
// Protected Routes (Require Authentication)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
   
Route::post('movierating/{id}', [MovieController::class, 'rating']);

//reviews
Route::post('/moviereview/{movieId}', [ReviewController::class, 'submitReview'])
    ->name('movie.submit-review')
    ->middleware('auth'); 

    
// Review edit and update
Route::get('/movies/{movieId}/review/edit', [MovieController::class, 'editReview'])->name('movie.review.edit');
Route::post('/movies/{movieId}/review/update', [MovieController::class, 'updateReview'])->name('movie.review.update');





    // Profile Routes
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
    
    Route::put('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [UserController::class, 'updatePassword'])->name('profile.password.update');
    
    // Watchlist Routes


    // Movie Watchlist Actions
    Route::post('/movie/{movieId}/watchlist', [MovieController::class, 'toggleWatchlist'])->name('movie.toggle-watchlist');
    Route::post('/movie/{movieId}/remove-watchlist', [MovieController::class, 'removeWatchlist'])->name('movie.remove-watchlist');

    // Logout
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
});








Route::middleware(['auth'])->group(function () {
    Route::get('/mylist', [MyListController::class, 'index'])->name('mylist');
    Route::post('/mylist/add', [MyListController::class, 'store'])->name('mylist.add');
    Route::delete('/mylist/remove/{id}', [MyListController::class, 'destroy'])->name('mylist.remove');
    
    Route::post('/mylist/toggle-watched/{id}', [MyListController::class, 'toggleWatched'])->name('mylist.toggle-watched');
    Route::post('/mylist/update-progress/{id}', [MyListController::class, 'updateProgress'])->name('mylist.update-progress');
});



//admin routs
Route::prefix('admin')->as('admin.')->group(function () {
Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/users', [UserController::class, 'index']);
Route::get('/movies', [MovieController::class, 'moviesdata'])->name('movies.list');
Route::post('/addmovies', [MovieController::class, 'insertmovies']);
Route::get('/addmovies', [MovieController::class, 'addshow']);
Route::get('/genres', [CategoryController::class, 'viewgenres']);


});


//crud user
Route::prefix('admin')->as('admin.')->group(function () {

Route::get('/users/{id}', [UserController::class, 'edit'])->name('admin.users.update');

Route::post('updateuser/{id}', [UserController::class, 'update'])->name('movies.store');

Route::get('/deleteusers/{id}', [UserController::class, 'destroy'])->name('users.destroy');

});


//crud movie
Route::prefix('admin')->as('admin.')->group(function () {

Route::get('/movies/{id}', [MovieController::class, 'edit'])->name('admin.movies.update');

Route::post('updatemovies/{id}', [MovieController::class, 'update'])->name('movies.store');

Route::get('/deletemovies/{id}', [MovieController::class, 'destroy'])->name('movies.destroy');

Route::get('/reviews/{id}', [MovieController::class, 'selectedmoviereview']);




});


//genre
Route::prefix('admin')->as('admin.')->group(function () {
Route::get('/addgenres', [CategoryController::class, 'addGenreForm']);
Route::post('/addgenres', [CategoryController::class, 'insertgenres']);
Route::delete('/genres/{id}', [CategoryController::class, 'deleteGenre'])->name('genres.delete');

});
