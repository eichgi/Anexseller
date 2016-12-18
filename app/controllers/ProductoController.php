<?php
namespace App\Controllers;

use App\Helpers\ResponseHelper;
use App\Repositories\ProductoRepository;
use Core\{
    Controller
};
use App\Models\Producto;

class ProductoController extends Controller
{
    private $productoRepo;

    public function __construct()
    {
        parent::__construct();
        $this->productoRepo = new ProductoRepository();
    }

    public function getIndex()
    {
        return $this->render('producto/index.twig', [
            'title' => 'Productos'
        ]);
    }

    public function getCrud($id = 0)
    {
        $model = (
        $id === 0
            ? new Producto
            : $this->productoRepo->obtener($id)
        );

        return $this->render('producto/crud.twig', [
            'title' => 'Productos',
            'model' => $model
        ]);
    }

    public function postImportar()
    {
        $rh = new ResponseHelper();
        if (empty($_FILES['archivo'])) {
            $rh->setResponse(false, 'Debe adjuntar un archivo CSV');
        } else {
            $rh = $this->productoRepo->importar($_FILES['archivo']);
            if ($rh->response) {
                $rh->href = 'self';
            }
        }
        print_r(json_encode($rh));
    }

    public function getExportar()
    {
        header("Content-type: application/vnd.ms-excel"); /* Indica que tipo de archivo es que va a descargar */
        header("Content-Disposition: attachment;filename=mi_archivo.xls"); /* El nombre del archivo y la extensiòn */
        header("Pragma: no-cache");
        header("Expires: 0");
        return $this->render('producto/excel.twig', [
            'model' => $this->productoRepo->todo()
        ]);

    }

    public function postGrid()
    {
        print_r($this->productoRepo->listar());
    }

    public function postGuardar()
    {
        $model = new Producto();
        $model->id = $_POST['id'];
        $model->marca = $_POST['marca'];
        $model->nombre = $_POST['nombre'];
        $model->costo = $_POST['costo'];
        $model->precio = $_POST['precio'];

        $foto = NULL;
        if (!empty($_FILES['foto'])) {
            $foto = $_FILES['foto'];
        }

        /*var_dump($foto);
        exit;*/

        $rh = $this->productoRepo->guardar($model, $foto);

        if ($rh->response) {
            $rh->href = 'producto';
        }

        print_r(json_encode($rh));
    }

    /*public function postCrud() //Si se deja postCrud marcará error de POST
    {

    }*/

    public function postEliminar($id)
    {

    }
}