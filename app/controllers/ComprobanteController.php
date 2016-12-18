<?php
namespace App\Controllers;

use Core\{Auth, Controller, Log};

class ComprobanteController extends Controller {
    private $usermodel;

    public function __construct() {
        parent::__construct();
    }

    public function getIndex() {
        return $this->render('comprobante/index.twig', [
            'title' => 'Comprobantes'
        ]);
    }

    public function getNuevo() {
        return $this->render('comprobante/nuevo.twig', [
            'title' => 'Comprobantes'
        ]);
    }

    public function getDetalle($id) {
        return $this->render('comprobante/detalle.twig', [
            'title' => 'Comprobantes'
        ]);
    }

    public function getPdf($id) {
        
    }

    public function postEliminar($id){

    }
}