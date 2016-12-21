<?php
namespace App\Controllers;

use App\Models\Cliente;
use App\Repositories\ClienteRepository;
use Core\{
    Auth, Controller, Log
};

class ClienteController extends Controller
{
    private $clienteRepo;

    public function __construct()
    {
        parent::__construct();
        $this->clienteRepo = new ClienteRepository();
    }

    public function getIndex()
    {
        return $this->render('cliente/index.twig', [
            'title' => 'Clientes'
        ]);
    }

    public function getCrud($id = 0)
    {
        $model = (
        $id === 0
            ? new Cliente
            : $this->clienteRepo->obtener($id)
        );

        return $this->render('cliente/crud.twig', [
            'title' => 'Clientes',
            'model' => $model
        ]);

    }

    public function postGrid()
    {
        print_r($this->clienteRepo->listar());
    }

    public function postGuardar()
    {

        $model = new Cliente;
        $model->id = $_POST['id'];
        $model->nombre = $_POST['nombre'];
        $model->direccion = $_POST['direccion'];

        $rh = $this->clienteRepo->guardar($model);

        if ($rh->response) {
            $rh->href = 'cliente';
        }

        print_r(
            json_encode($rh)
        );
    }

    public function postEliminar()
    {
        print_r(
            json_encode(
                $this->clienteRepo->eliminar($_POST['id'])
            )
        );
    }

    public function getBuscar($q)
    {
        print_r(
            json_encode(
                $this->clienteRepo->buscar($q)
            )
        );
    }
}