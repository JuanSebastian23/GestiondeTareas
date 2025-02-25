<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/DbConfig.php');

class TareaModel {
    private $db;

    public function __construct() {
        $this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $this->db->set_charset("utf8mb4");

        if ($this->db->connect_error) {
            die("Error de conexión: " . $this->db->connect_error);
        }
    }

    public function getTareasConDetalles($estudiante_id) {
        $sql = "SELECT 
                    t.id, t.titulo, t.fecha_creacion, t.fecha_entrega, 
                    et.nombre AS estado_nombre, 
                    m.nombre AS materia_nombre, 
                    g.nombre AS grupo_nombre 
                FROM tareas t
                JOIN materias m ON t.materia_id = m.id
                JOIN grupos g ON t.grupo_id = g.id
                JOIN estudiante_grupo eg ON g.id = eg.grupo_id
                LEFT JOIN estados_tarea et ON t.estado_id = et.id  
                WHERE eg.estudiante_id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $estudiante_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getTareasFiltradas($estudiante_id, $materia = null, $estado = null) {
        $sql = "SELECT 
                    t.id, t.titulo, t.fecha_creacion, t.fecha_entrega, 
                    et.nombre AS estado_nombre, 
                    m.nombre AS materia_nombre, 
                    g.nombre AS grupo_nombre 
                FROM tareas t
                JOIN materias m ON t.materia_id = m.id
                JOIN grupos g ON t.grupo_id = g.id
                JOIN estudiante_grupo eg ON g.id = eg.grupo_id
                LEFT JOIN estados_tarea et ON t.estado_id = et.id  
                WHERE eg.estudiante_id = ?";

        $params = [$estudiante_id];
        $types = "i";

        if ($materia) {
            $sql .= " AND m.nombre = ?";
            $params[] = $materia;
            $types .= "s";
        }

        if ($estado) {
            $sql .= " AND et.nombre = ?";
            $params[] = $estado;
            $types .= "s";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
