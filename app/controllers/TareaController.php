<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/models/TareaModel.php');

class TareaController {
    private $tareaModel;

    public function __construct() {
        $this->tareaModel = new TareaModel();
    }

    // Obtener tareas asignadas a estudiantes
    public function obtenerTareasParaEstudiantes() {
        return $this->tareaModel->getTareasConDetalles();
    }
}
?>
