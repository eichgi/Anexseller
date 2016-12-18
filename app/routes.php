<?php
/* Controllers */
$router->controller('/home', 'App\\Controllers\\HomeController');
$router->controller('/auth', 'App\\Controllers\\AuthController');
$router->controller('/cliente', 'App\\Controllers\\ClienteController');
$router->controller('/comprobante', 'App\\Controllers\\ComprobanteController');
$router->controller('/producto', 'App\\Controllers\\ProductoController');
$router->controller('/reporte', 'App\\Controllers\\ReporteController');
$router->controller('/usuario', 'App\\Controllers\\UsuarioController');

$router->get('/', function(){
    if(!\Core\Auth::isLoggedIn()){
        \App\Helpers\UrlHelper::redirect('auth');
    } else {
        \App\Helpers\UrlHelper::redirect('home');
    }
});

$router->get('/welcome', function(){
    return 'Welcome page';
}, ['before' => 'auth']);

$router->get('/test', function(){
    return 'Welcome page';
}, ['before' => 'auth']);