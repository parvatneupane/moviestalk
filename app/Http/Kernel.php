
<?php

use App\Http\Middleware\AdminMiddleware;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{

protected $routeMiddleware = [
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
];

}