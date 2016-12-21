<?php
namespace App\Repositories;

use Core\Auth;
use Core\Log;
use App\Helpers\ResponseHelper;
use App\Helpers\AnexGridHelper;
use App\Models\Cliente;
use Exception;

class ClienteRepository
{
    private $cliente;

    public function __construct()
    {
        $this->cliente = new Cliente;
    }

    public function guardar(Cliente $model) : ResponseHelper
    {
        $rh = new ResponseHelper;

        try {
            $this->cliente->id = $model->id;
            $this->cliente->nombre = $model->nombre;
            $this->cliente->direccion = $model->direccion;

            if (!empty($model->id)) {
                /*
                 * Al setear este valor a True hacemos que Eloquent lo considere como un registro existente,
                 * por lo tanto hará un update
                 */
                $this->cliente->exists = true;
            }

            $this->cliente->save();
            $rh->setResponse(true);
        } catch (Exception $e) {
            Log::error(ClienteRepository::class, $e->getMessage());
        }

        return $rh;
    }

    public function obtener($id) : Cliente
    {
        $cliente = new Cliente;

        try {
            $cliente = $this->cliente->find($id);
        } catch (Exception $e) {
            Log::error(ClienteRepository::class, $e->getMessage());
        }

        return $cliente;
    }

    public function listar() : string
    {
        $anexgrid = new AnexGridHelper;

        try {
            $result = $this->cliente->orderBy(
                $anexgrid->columna,
                $anexgrid->columna_orden
            )->skip($anexgrid->pagina)
                ->take($anexgrid->limite)
                ->get();

            return $anexgrid->responde(
                $result,
                $this->cliente->count()
            );
        } catch (Exception $e) {
            Log::error(ClienteRepository::class, $e->getMessage());
        }

        return "";
    }

    public function eliminar(int $id) : ResponseHelper
    {
        $rh = new ResponseHelper;

        try {
            if (Auth::getCurrentUser()->id == $id) {
                $rh->setResponse(false, 'No puede eliminarse usted mismo');
            } else {
                $this->cliente->destroy($id);
                $rh->setResponse(true);
            }
        } catch (Exception $e) {
            Log::error(ClienteRepository::class, $e->getMessage());
        }

        return $rh;
    }

    public function buscar(string $q) : array
    {
        $result = [];

        try {
            $result = $this->cliente
                ->where('nombre', 'like', "%$q%")
                ->orderBy('nombre')
                ->get()
                ->toArray();
        } catch (Exception $e) {
            Log::error(ClienteRepository::class, $e->getMessage());
        }

        return $result;
    }


}