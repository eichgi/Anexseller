<?php
namespace App\Controllers;

use Core\{Auth, Controller, Log};

class HomeController extends Controller {
    private $usuarioRepo;

    public function __construct() {
        parent::__construct();
    }

    public function getIndex() {
        return $this->render('home/index.twig', [
            'title' => 'Inicio'
        ]);
    }

    public function getTest() {
        return $this->render('home/test.twig', [
            'title' => 'Inicio',
            'model' => ['manzana','fresa','uva']
        ]);
    }

    public function getSignin() {
        Auth::signIn([
            'id' => 1,
            'name' => 'Eduardo',
        ]);
    }

    public function getUser() {
        var_dump(Auth::getCurrentUser());
    }

    public function getIsloggedin() {
        return var_dump(Auth::isLoggedIn());
    }

    public function anyTest($param, $param2 = 'default') {
        return 'This will respond to /controller/test/{param}/{param2}? with any method';
    }
}