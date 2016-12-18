<?php
namespace App\Controllers;

use App\Validations\UsuarioValidation;
use Core\{Controller};
use App\Helpers\{UrlHelper};
use App\Repositories\{UsuarioRepository,RolRepository};
use App\Models\{Usuario};

class UsuarioController extends Controller {
    private $usuarioRepo;
    private $rolRepo;

    public function __construct() {
        parent::__construct();
        $this->usuarioRepo = new UsuarioRepository;
        $this->rolRepo = new RolRepository;
    }

    public function getIndex() {
        return $this->render('usuario/index.twig', [
            'title' => 'Usuarios'
        ]);
    }

    public function postGrid() {
        print_r($this->usuarioRepo->listar());
    }

    public function getCrud($id = 0) {
        $model = (
            $id === 0
                ? new Usuario
                : $this->usuarioRepo->obtener($id)
        );

        return $this->render('usuario/crud.twig', [
            'title' => 'Usuarios',
            'model' => $model,
            'roles' => $this->rolRepo->listar()
        ]);
    }

    public function postGuardar() {
        UsuarioValidation::validate($_POST);

        $model = new Usuario;
        $model->id = $_POST['id'];
        $model->rol_id = $_POST['rol_id'];
        $model->nombre = $_POST['nombre'];
        $model->apellido = $_POST['apellido'];
        $model->correo = $_POST['correo'];
        $model->password = $_POST['password'];

        $rh = $this->usuarioRepo->guardar($model);

        if($rh->response) {
            $rh->href = 'usuario';
        }
        
        print_r(
            json_encode($rh)
        );
    }

    public function postEliminar() {
        print_r(
            json_encode(
                $this->usuarioRepo->eliminar($_POST['id'])
            )
        );
    }

    public function getEliminar($id) {
        UrlHelper::redirect('usuario');
    }
}