<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/DbConfig.php');

class TareaModel {
    private $db;

    public function __construct() {
        $this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $this->db->set_charset("utf8mb4");
    }

    public function getTareasConDetalles() {
        $sql = "SELECT 
                    t.id, 
                    t.titulo, 
                    t.descripcion, 
                    t.fecha_creacion, 
                    t.fecha_entrega, 
                    t.estado_id, 
                    m.nombre AS materia_nombre, 
                    g.nombre AS grupo_nombre 
                FROM tareas t
                JOIN materias m ON t.materia_id = m.id
                JOIN grupos g ON t.grupo_id = g.id";
    
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result(); // Obtener los resultados
    
        $tareas = $result->fetch_all(MYSQLI_ASSOC); // Convertir a array asociativo
    
        $stmt->close(); // ✅ Cerrar statement para evitar errores
    
        return $tareas;
    }
}
?>
