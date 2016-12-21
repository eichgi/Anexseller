<?php
namespace App\Repositories;

use Core\{
    Auth, Log
};
use App\Helpers\{
    ResponseHelper, AnexGridHelper
};
use App\Models\{
    Producto
};
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Intervention\Image\ImageManagerStatic as Image;


class ProductoRepository
{
    private $producto;

    public function __construct()
    {
        $this->producto = new Producto;
    }

    public function buscar(string $q) : array
    {
        $result = [];

        try {
            $result = $this->producto
                ->where('nombre', 'like', "%$q%")
                ->orderBy('nombre')
                ->get()
                ->toArray();
        } catch (Exception $e) {
            Log::error(ProductoRepository::class, $e->getMessage());
        }

        return $result;
    }

    public function todo() : Collection
    {
        $result = NULL;

        try {
            $result = $this->producto->orderBy('id', 'desc')->get();
        } catch (Exception $e) {
            Log::error(ProductoRepository::class, $e->getMessage());
        }

        return $result;
    }

    public function listar() : string
    {
        $anexgrid = new AnexGridHelper;

        try {
            $result = $this->producto->orderBy(
                $anexgrid->columna,
                $anexgrid->columna_orden
            )->skip($anexgrid->pagina)
                ->take($anexgrid->limite)
                ->get();

            return $anexgrid->responde(
                $result,
                $this->producto->count()
            );
        } catch (Exception $e) {
            Log::error(ProductoRepository::class, $e->getMessage());
        }

        return "";
    }


    public function guardar(producto $model, array $foto = null) : ResponseHelper
    {
        $rh = new ResponseHelper;

        try {
            $this->producto->id = $model->id;
            $this->producto->nombre = $model->nombre;
            $this->producto->marca = $model->marca;
            $this->producto->costo = $model->costo;
            $this->producto->precio = $model->precio;

            if (!empty($model->id)) {
                /*
                 * Al setear este valor a True hacemos que Eloquent lo considere como un registro existente,
                 * por lo tanto hará un update
                 */
                $this->producto->exists = true;
            }

            if (!is_null($foto)) {
                $nombre_archivo = sprintf(
                    'media/producto-%s.%s',
                    $model->id,
                    pathinfo($foto['name'], PATHINFO_EXTENSION)
                );
                $img = Image::make($foto['tmp_name']);
                $img->resize(500, 500);
                $img->save('public/' . $nombre_archivo);
                $this->producto->foto = $nombre_archivo;
            }

            $this->producto->save();
            $rh->setResponse(true);
        } catch (Exception $e) {
            Log::error(ProductoRepository::class, $e->getMessage());
        }

        return $rh;
    }

    public function obtener($id) : Producto
    {
        $producto = new producto;

        try {
            $producto = $this->producto->find($id);
        } catch (Exception $e) {
            Log::error(ProductoRepository::class, $e->getMessage());
        }

        return $producto;
    }

    public function eliminar(int $id) : ResponseHelper
    {
        $rh = new ResponseHelper;

        try {
            if (Auth::getCurrentUser()->id == $id) {
                $rh->setResponse(false, 'No puede eliminarse usted mismo');
            } else {
                $this->producto->destroy($id);
                $rh->setResponse(true);
            }
        } catch (Exception $e) {
            Log::error(ProductoRepository::class, $e->getMessage());
        }

        return $rh;
    }

    public function importar(array $archivo) : ResponseHelper
    {
        $rh = new ResponseHelper;
        try {
            $data = [];
            $fila = 0;
            if (($gestor = fopen($archivo['tmp_name'], "r")) !== FALSE) {
                while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
                    /*$numero = count($datos);
                    echo "<p> $numero de campos en la línea $fila: <br /></p>\n";
                    $fila++;
                    for ($c = 0; $c < $numero; $c++) {
                        echo $datos[$c] . "<br />\n";
                    }*/
                    if ($fila > 0) {
                        $model = new Producto();
                        $model->nombre = $datos[0];
                        $model->marca = $datos[1];
                        $model->costo = $datos[2];
                        $model->precio = $datos[3];

                        $data[] = $model;
                    }
                    $fila++;
                }
                fclose($gestor);
            }
            foreach ($data as $item) {
                $item->save();
            }
            $rh->setResponse(true);
        } catch (Exception $e) {
            Log::error(ProductoRepository::class, $e->getMessage());
        }
        return $rh;
    }


}