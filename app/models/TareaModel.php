<?php
if (!defined('ROOT_PATH')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
}
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/DbConfig.php');

class TareaModel {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $this->conn->set_charset("utf8mb4");

        if ($this->conn->connect_error) {
            die("Error de conexión: " . $this->conn->connect_error);
        }
    }

    public function insertarTarea($titulo, $descripcion, $fechaEntrega, $materiaId, $grupoId, $profesorId) {
        $estadoId = 1; // Estado "pendiente" por defecto
        $sql = "INSERT INTO tareas (titulo, descripcion, fecha_entrega, materia_id, grupo_id, profesor_id, estado_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssiii", $titulo, $descripcion, $fechaEntrega, $materiaId, $grupoId, $profesorId, $estadoId);
        return $stmt->execute();
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

        $stmt = $this->conn->prepare($sql);
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

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getTareasActivas() {
        $sql = "SELECT id, titulo, fecha_entrega FROM tareas WHERE estado_id = 1"; // Asumiendo que 1 es el estado "pendiente"
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getTodasLasTareasConDetalles() {
        $sql = "SELECT t.id, t.titulo, t.fecha_creacion, t.fecha_entrega, et.nombre AS estado_nombre, m.nombre AS materia_nombre, g.nombre AS grupo_nombre FROM tareas t JOIN materias m ON t.materia_id = m.id JOIN grupos g ON t.grupo_id = g.id LEFT JOIN estados_tarea et ON t.estado_id = et.id";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerTareasPorProfesor($profesorId, $grupoId = null, $materiaId = null, $estadoId = null) {
        $sql = "SELECT t.*, 
                   g.nombre AS grupo_nombre, 
                   m.nombre AS materia_nombre, 
                   et.nombre AS estado_nombre
                FROM tareas t
                INNER JOIN grupos g ON t.grupo_id = g.id
                INNER JOIN materias m ON t.materia_id = m.id
                INNER JOIN estados_tarea et ON t.estado_id = et.id
                WHERE t.profesor_id = ?";
        
        $params = [$profesorId];
        $types = "i";
        
        if ($grupoId) {
            $sql .= " AND t.grupo_id = ?";
            $params[] = $grupoId;
            $types .= "i";
        }
        
        if ($materiaId) {
            $sql .= " AND t.materia_id = ?";
            $params[] = $materiaId;
            $types .= "i";
        }
        
        if ($estadoId) {
            $sql .= " AND t.estado_id = ?";
            $params[] = $estadoId;
            $types .= "i";
        }
        
        $sql .= " ORDER BY t.fecha_entrega ASC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function obtenerEntregasPorTarea($tareaId) {
        $sql = "SELECT et.*, 
                   u.nombre AS estudiante_nombre, 
                   u.apellidos AS estudiante_apellidos,
                   est.nombre AS estado
                FROM entregas_tarea et
                INNER JOIN usuarios u ON et.estudiante_id = u.id
                INNER JOIN estados_tarea est ON et.estado_id = est.id
                WHERE et.tarea_id = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $tareaId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function contarEstudiantesPorGrupo($grupoId) {
        $sql = "SELECT COUNT(*) AS total FROM estudiante_grupo WHERE grupo_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $grupoId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['total'];
    }
    
    public function obtenerEstadoTarea($tareaId) {
        $sql = "SELECT et.nombre 
                FROM tareas t
                INNER JOIN estados_tarea et ON t.estado_id = et.id
                WHERE t.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $tareaId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    
    public function obtenerGruposPorProfesor($profesorId) {
        $sql = "SELECT g.* 
                FROM grupos g
                INNER JOIN profesor_grupo pg ON g.id = pg.grupo_id
                WHERE pg.profesor_id = ? AND g.activo = 1
                ORDER BY g.nombre";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $profesorId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function obtenerMateriasPorProfesor($profesorId) {
        $sql = "SELECT DISTINCT m.* 
                FROM materias m
                INNER JOIN grupo_materia gm ON m.id = gm.materia_id
                WHERE gm.profesor_id = ? AND m.activo = 1
                ORDER BY m.nombre";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $profesorId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Actualiza la información de una entrega tras la calificación
     */
    public function actualizarEntrega($datos) {
        // Revisar en la base de datos si la columna se llama calificacion o nota
        $sql = "DESCRIBE entregas_tarea";
        $result = $this->conn->query($sql);
        $columnExists = false;
        $columnName = "calificacion"; // Nombre por defecto
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                if ($row['Field'] === 'calificacion') {
                    $columnExists = true;
                    break;
                } else if ($row['Field'] === 'nota') {
                    $columnName = "nota";
                    $columnExists = true;
                    break;
                }
            }
        }
        
        // Si no existe la columna, intentamos crearla
        if (!$columnExists) {
            $alterSql = "ALTER TABLE entregas_tarea ADD COLUMN $columnName DECIMAL(5,2) NULL";
            $this->conn->query($alterSql);
        }
        
        // Ahora procedemos a actualizar usando el nombre de columna correcto
        $sql = "UPDATE entregas_tarea 
                SET estado_id = ?, 
                    $columnName = ?, 
                    comentarios = ? 
                WHERE id = ?";
        
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("idsi", $datos['estado_id'], $datos['calificacion'], $datos['comentarios'], $datos['id']);
            return $stmt->execute();
        }
        
        return false;
    }
    
    public function actualizarEstadoTarea($tareaId, $estadoId) {
        $sql = "UPDATE tareas SET estado_id = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $estadoId, $tareaId);
        
        return $stmt->execute();
    }

    // Añadir métodos para estadísticas

    /**
     * Cuenta el número total de tareas en el sistema
     */
    public function contarTodasLasTareas() {
        $query = "SELECT COUNT(*) as total FROM tareas";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    /**
     * Cuenta el número de tareas activas
     */
    public function contarTareasActivas() {
        // Ajusta esta consulta según la estructura de tu tabla de tareas
        $query = "SELECT COUNT(*) as total FROM tareas WHERE estado_id != 4"; // Asumiendo que estado_id=4 es para tareas archivadas o eliminadas
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    /**
     * Cuenta el número de tareas según su estado
     * @param string $estado Nombre del estado a contar
     */
    public function contarTareasPorEstado($estado) {
        $query = "SELECT COUNT(t.id) as total FROM tareas t 
                  JOIN estados_tarea e ON t.estado_id = e.id 
                  WHERE e.nombre = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $estado);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }
}
?>
