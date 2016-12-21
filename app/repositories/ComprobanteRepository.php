<?php

namespace App\Repositories;


use App\Helpers\AnexGridHelper;
use App\Helpers\ResponseHelper;
use App\Models\Comprobante;
use Core\Log;
use Exception;


class ComprobanteRepository
{
    private $comprobante;

    /**
     * ComprobanteRepository constructor.
     * @param $comprobante
     */
    public function __construct()
    {
        $this->comprobante = new Comprobante;
    }

    public function generar(Comprobante $model, array $detalle) : ResponseHelper
    {
        $rh = new ResponseHelper;

        try {
            $model->sub_total = 0;
            $model->iva = 0;
            $model->total = 0;
            $model->fecha = date('Y-m-d');
            $model->anulado = 0;

            foreach ($detalle as $k => $d) {
                $d->orden = $k;
                $d->total = $d->cantidad * $d->precio;
                $model->total += $d->total;
            }

            //Subtotal
            $model->sub_total = $model->total / 1.18;
            $model->iva = $model->total - $model->sub_total;

            //Generar comprobante
            $model->save();

            //Guardar el detalle
            $model->detalle()->saveMany($detalle);

            $rh->setResponse(true);
        } catch (Exception $e) {
            Log::error(ProductoRepository::class, $e->getMessage());
        }

        return $rh;
    }

    public function listar() : string
    {
        $anexgrid = new AnexGridHelper();
        try {
            $result = $this->comprobante->orderBy(
                $anexgrid->columna,
                $anexgrid->columna_orden)
                ->skip($anexgrid->pagina)
                ->take($anexgrid->limite)
                ->get();

            foreach ($result as $r) {
                $r->cliente = $r->cliente;
            }

            return $anexgrid->responde($result, $this->comprobante->count());
        } catch (Exception $e) {
            Log::error(ComprobanteRepository::class, $e->getMessage());
        }
        return "";
    }

    public function anular(int $id) : ResponseHelper
    {
        $rh = new ResponseHelper;

        try {
            $this->comprobante->id = $id;
            $this->comprobante->anulado = 1;
            $this->comprobante->exists = true;

            $this->comprobante->save();
            $rh->setResponse(true);
        } catch (Exception $e) {
            Log::error(ComprobanteRepository::class, $e->getMessage());
        }

        return $rh;
    }

    public function obtener($id) : Comprobante
    {
        $model = new Comprobante();
        try {
            $model = $this->comprobante->find($id);
        } catch (Exception $e) {
            Log::error(ComprobanteRepository::class, $e->getMessage());
        }
        return $model;
    }


}