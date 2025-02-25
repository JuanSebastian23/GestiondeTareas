<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/models/TareaModel.php');

class TareaController {
    private $tareaModel;

    public function __construct() {
        $this->tareaModel = new TareaModel();
    }

    public function obtenerTareasParaEstudiantes() {
        if (!isset($_SESSION['user_id'])) {
            die("Acceso denegado.");
        }

        $estudiante_id = $_SESSION['user_id'];
        return $this->tareaModel->getTareasConDetalles($estudiante_id);
    }

    public function obtenerTareasFiltradas($materia = null, $estado = null) {
        if (!isset($_SESSION['user_id'])) {
            die("Acceso denegado.");
        }

        $estudiante_id = $_SESSION['user_id'];
        return $this->tareaModel->getTareasFiltradas($estudiante_id, $materia, $estado);
    }
}
?>
