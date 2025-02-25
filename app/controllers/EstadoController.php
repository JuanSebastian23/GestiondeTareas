<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/models/EstadoModel.php');

class EstadoController {
    private $estadoModel;

    public function __construct() {
        $this->estadoModel = new EstadoModel();
    }

    public function obtenerEstados() {
        return $this->estadoModel->getEstadosDisponibles();
    }
}
?>
