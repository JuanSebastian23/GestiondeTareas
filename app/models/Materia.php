<?php
if (!defined('ROOT_PATH')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
}
require_once(CONFIG_PATH . '/Database.php');

class Materia {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function contarMaterias($soloActivas = false) {
        $sql = "SELECT COUNT(*) FROM materias";
        if ($soloActivas) {
            $sql .= " WHERE activo = TRUE";
        }
        return (int)$this->db->query($sql)->fetchColumn();
    }

    public function contarGruposAsignados() {
        $sql = "SELECT COUNT(*) FROM profesor_materia";
        return (int)$this->db->query($sql)->fetchColumn();
    }

    public function contarProfesoresAsignados() {
        $sql = "SELECT COUNT(DISTINCT profesor_id) FROM profesor_materia";
        return (int)$this->db->query($sql)->fetchColumn();
    }

    public function contarTareasAsignadas() {
        $sql = "SELECT COUNT(*) FROM tareas";
        return (int)$this->db->query($sql)->fetchColumn();
    }

    public function obtenerTodas() {
        $sql = "SELECT m.*, 
                (SELECT COUNT(*) FROM grupo_materia gm WHERE gm.materia_id = m.id AND gm.activo = TRUE) as total_grupos 
                FROM materias m 
                ORDER BY m.nombre";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM materias WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($datos) {
        try {
            $sql = "INSERT INTO materias (nombre, codigo, descripcion) 
                    VALUES (:nombre, :codigo, :descripcion)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':nombre' => $datos['nombre'],
                ':codigo' => $datos['codigo'],
                ':descripcion' => $datos['descripcion']
            ]);
            return ['success' => true];
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Código de error para duplicado
                return ['error' => 'Ya existe una materia con ese código'];
            }
            return ['error' => 'Error al crear la materia: ' . $e->getMessage()];
        }
    }

    public function actualizar($datos) {
        $sql = "UPDATE materias 
                SET nombre = :nombre, 
                    codigo = :codigo, 
                    descripcion = :descripcion 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $datos['id'],
            ':nombre' => $datos['nombre'],
            ':codigo' => $datos['codigo'],
            ':descripcion' => $datos['descripcion']
        ]);
    }

    public function cambiarEstado($id, $activo) {
        $sql = "UPDATE materias SET activo = :activo WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':activo' => $activo
        ]);
    }

    public function obtenerGrupos($materia_id) {
        $sql = "SELECT g.*, u.nombre as profesor_nombre, u.apellidos as profesor_apellidos,
                gm.activo as asignacion_activa, gm.profesor_id
                FROM grupos g
                LEFT JOIN grupo_materia gm ON g.id = gm.grupo_id AND gm.materia_id = :materia_id
                LEFT JOIN usuarios u ON gm.profesor_id = u.id
                WHERE g.activo = TRUE
                ORDER BY g.nombre";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':materia_id' => $materia_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerProfesoresDisponibles() {
        $sql = "SELECT u.* FROM usuarios u 
                INNER JOIN roles r ON u.rol_id = r.id 
                WHERE r.nombre = 'profesor' AND u.activo = TRUE
                ORDER BY u.nombre, u.apellidos";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
