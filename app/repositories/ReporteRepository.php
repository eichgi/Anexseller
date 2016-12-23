<?php
namespace App\Repositories;

use Core\{
    Log
};
use App\Helpers\{
    AnexGridHelper
};
use App\Models\{
    Producto
};
use Exception;
use Illuminate\Database\Capsule\Manager as DB;


class ReporteRepository
{
    private $producto;

    public function __construct()
    {
        $this->producto = new Producto;
    }

    public function listar() : string
    {
        $anexgrid = new AnexGridHelper;

        try {
            /*$result = $this->producto->orderBy(
                $anexgrid->columna,
                $anexgrid->columna_orden
            )->skip($anexgrid->pagina)
                ->take($anexgrid->limite)
                ->get();

            return $anexgrid->responde(
                $result,
                $this->producto->count()
            );*/
            $sql = '
                SELECT
                    YEAR(fecha) anio,
                    MONTH(FECHA) mes,
                    (
                        SELECT
                            SUM(costo*cantidad)
                        FROM comprobante_detalle
                        WHERE comprobante_id = c.id
                    ) costo,
                    SUM(total) total
                FROM
                comprobante c
                WHERE c.anulado = 0
                GROUP BY c.id, anio, mes';

            $result = DB::select("
                SELECT 
                  anio,
                  mes,
                  SUM(costo) costo,
                  SUM(total) total
                FROM ($sql) alias
                GROUP BY anio, mes
                ORDER BY anio desc, mes desc
                LIMIT $anexgrid->pagina, $anexgrid->limite
            ");

            $total = DB::select("
                SELECT 
                  COUNT(*) t
                FROM ($sql) alias
            ");

            /*$result = $this->producto->orderBy(
                $anexgrid->columna,
                $anexgrid->columna_orden
            )
                ->skip($anexgrid->pagina)
                ->take($anexgrid->limite)
                ->get();*/

            //return $anexgrid->responde($result, $this->producto->count());
            return $anexgrid->responde($result, $total[0]->t);
        } catch (Exception $e) {
            Log::error(ReporteRepository::class, $e->getMessage());
        }

        return "";
    }

}