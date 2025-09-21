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
use App\Http\Controllers\WatchlistController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');

// Add this alias outside of prefix group
Route::get('/login', [UserController::class, 'loginForm'])->name('login');
Route::get('user/movies', [MovieController::class, 'index'])->name('movies');
Route::get('/movies/search', [MovieController::class, 'search'])->name('movies.search');
Route::get('user/movie/{movieId}', [MovieController::class, 'show'])->name('movie.detail');
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





Route::post('/admin/notifications/mark-as-read', [App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('admin.notifications.markAsRead');


Route::middleware(['auth'])->group(function () {
    Route::get('/mylist', [MyListController::class, 'index'])->name('mylist');
    
    Route::post('/watchlist/toggle/{movieId}', [WatchlistController::class, 'toggle'])->name('watchlist.toggle');
    Route::post('/watchlist/mark/{id}', [WatchlistController::class, 'markWatched'])->name('watchlist.mark');
    Route::get('/watchlist', [WatchlistController::class, 'index'])->name('watchlist.index');
    
    // Use {movieId} here to match your controller
    Route::post('/mylist/add/{movieId}', [WatchlistController::class, 'add'])->name('mylist.add');
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

use App\Http\Controllers\Admin\NotificationController;
Route::delete('/admin/notifications/{id}', [NotificationController::class, 'destroy'])
    ->name('admin.notifications.destroy');

Route::post('/admin/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])
    ->name('admin.notifications.markAsRead');

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


Route::get('/reviews/{id}', [ReviewController::class, 'selectedmoviereview']);



});


//genre
Route::prefix('admin')->as('admin.')->group(function () {
Route::get('/addgenres', [CategoryController::class, 'addGenreForm']);
Route::post('/addgenres', [CategoryController::class, 'insertgenres']);
Route::delete('/genres/{id}', [CategoryController::class, 'deleteGenre'])->name('genres.delete');

});


Route::middleware('auth')->group(function(){
    Route::get('/movies/{movie}/check-review', [ReviewController::class, 'checkReview'])
         ->name('movie.check-review');

    Route::post('/movies/{movie}/submit-review', [ReviewController::class, 'submitReview'])
         ->name('movie.submit-review');
});

Route::post('/feedback-submit', [ContactController::class, 'submitFeedback'])
     ->middleware('auth')
     ->name('contact.submit');



Route::prefix('admin')->as('admin.')->group(function () {
    Route::get('/feedbacks', [ContactController::class, 'viewfeedbacks'])->name('feedbacks');
    Route::get('/feedbacks/{id}', [ContactController::class, 'viewSingleFeedback'])->name('feedback.view');
    Route::delete('/deletefeedbacks/{id}', [ContactController::class, 'deleteFeedback'])->name('feedback.delete');
    Route::post('/feedback/{id}/reply', [ContactController::class, 'replyFeedback'])->name('feedback.reply');
});

Route::get('/feedback-reply/{feedback}', [ContactController::class, 'showFeedbackReply']);



