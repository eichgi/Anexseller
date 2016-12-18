<?php
namespace App\Controllers;

use Core\{Auth, Controller, Log};

class ReporteController extends Controller {
    private $usermodel;

    public function __construct() {
        parent::__construct();
    }

    public function getVentas() {
        return $this->render('reporte/ventas.twig', [
            'title' => 'Reporte'
        ]);
    }

    public function getProductos() {
        return $this->render('reporte/productos.twig', [
            'title' => 'Reporte'
        ]);
    }
}