<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/models/MateriaModel.php');

class MateriaController {
    private $materiaModel;

    public function __construct() {
        $this->materiaModel = new MateriaModel();
    }

    public function obtenerMateriasEstudiante($estudiante_id) {
        return $this->materiaModel->obtenerMateriasPorEstudiante($estudiante_id);
    }
}
?>
