<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/DbConfig.php');

class MateriaModel {
    private $db;

    public function __construct() {
        $this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($this->db->connect_error) {
            die("Error de conexión a la base de datos: " . $this->db->connect_error);
        }

        $this->db->set_charset("utf8mb4");
    }

    public function obtenerMateriasPorEstudiante($estudiante_id) {
        $sql = "SELECT DISTINCT m.id, m.nombre 
                FROM materias m
                JOIN tareas t ON t.materia_id = m.id
                JOIN estudiante_grupo eg ON t.grupo_id = eg.grupo_id
                WHERE eg.estudiante_id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $estudiante_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
