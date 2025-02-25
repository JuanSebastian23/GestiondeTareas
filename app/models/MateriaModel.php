<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/DbConfig.php');

class MateriaModel
{
    private $mdb;

    public function __construct()
    {
        new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $this->mdb->set_charset("utf8mb4");
    }

    public function getMateriasEstudiante($estudiante_id)
    {
        $sql = "SELECT DISTINCT m.id, m.nombre 
                FROM materias m
                JOIN tareas t ON t.materia_id = m.id
                JOIN grupos g ON t.grupo_id = g.id
                JOIN estudiante_grupo eg ON g.id = eg.grupo_id
                WHERE eg.estudiante_id = ?";

        $stmt = $this->mdb->prepare($sql);
        $stmt->execute([$estudiante_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
