<?php
// Auth Filter
$router->filter('auth', function(){
    \App\Middlewares\AuthMiddleware::isLoggedIn();
});

// System Role Permission
$router->filter('isAdmin', function(){
    \App\Middlewares\RoleMiddleware::isAdmin();
});

$router->filter('isSeller', function(){
    \App\Middlewares\RoleMiddleware::isSeller();
});

$router->filter('isAnalyst', function(){
    \App\Middlewares\RoleMiddleware::isAnalyst();
});