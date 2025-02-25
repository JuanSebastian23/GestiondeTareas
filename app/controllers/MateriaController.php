<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/models/MateriaModel.php');

class MateriaController
{
    public function obtenerMateriasEstudiante($estudiante_id)
    {
        $materiaModel = new MateriaModel();
        return $materiaModel->getMateriasEstudiante($estudiante_id);
    }
}
?>
