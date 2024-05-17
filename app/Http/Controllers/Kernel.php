<?php

protected $routeMiddleware = [
    // other middlewares
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
];
