<?php
if (!defined('ROOT_PATH')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
}

require_once(MODELS_PATH . '/Materia.php');

class MateriaController {
    private $modelo;

    public function __construct() {
        $this->modelo = new Materia();
    }

    public function obtenerEstadisticas() {
        $stats = [];
        
        // Obtener todas las materias
        $materias = $this->modelo->obtenerTodas();
        
        // Contar total de materias
        $stats['total_materias'] = count($materias);
        
        // Contar materias activas
        $materiasActivas = array_filter($materias, function($materia) {
            return $materia['activo'] == 1;
        });
        $stats['materias_activas'] = count($materiasActivas);
        
        // Obtener total de grupos con materias asignadas
        $stats['total_grupos'] = $this->modelo->contarGruposConMaterias();
        
        // Obtener profesores asignados a materias
        $stats['total_profesores'] = $this->modelo->contarProfesoresConMaterias();
        
        // Obtener tareas asignadas a materias
        $stats['total_tareas'] = $this->modelo->contarTareas();
        
        return $stats;
    }

    public function obtenerTodas() {
        return $this->modelo->obtenerTodas();
    }

    public function obtenerPorId($id) {
        return $this->modelo->obtenerPorId($id);
    }

    public function obtenerGrupos($materia_id) {
        try {
            return $this->modelo->obtenerGrupos($materia_id);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function obtenerMateriasPorProfesor($profesorId) {
        try {
            return $this->modelo->obtenerMateriasPorProfesor($profesorId);
        } catch (Exception $e) {
            error_log("Error obteniendo materias por profesor: " . $e->getMessage());
            return [];
        }
    }

    public function procesarAccion() {
        if (!isset($_POST['accion'])) {
            return ['error' => 'Acción no especificada'];
        }

        switch ($_POST['accion']) {
            case 'crear':
                return $this->crear($_POST);
            case 'actualizar':
                return $this->actualizar($_POST);
            case 'cambiarEstado':
                return $this->cambiarEstado($_POST);
            default:
                return ['error' => 'Acción no válida'];
        }
    }

    private function crear($datos) {
        if (empty($datos['nombre']) || empty($datos['codigo'])) {
            return ['error' => 'El nombre y código son obligatorios'];
        }

        $resultado = $this->modelo->crear([
            'nombre' => $datos['nombre'],
            'codigo' => $datos['codigo'],
            'descripcion' => $datos['descripcion'] ?? ''
        ]);

        if (isset($resultado['success'])) {
            return ['success' => 'Materia creada exitosamente'];
        }
        return ['error' => $resultado['error'] ?? 'Error al crear la materia'];
    }

    private function actualizar($datos) {
        if (empty($datos['id']) || empty($datos['nombre']) || empty($datos['codigo'])) {
            return ['error' => 'El nombre y código son obligatorios'];
        }

        $resultado = $this->modelo->actualizar([
            'id' => (int)$datos['id'],
            'nombre' => $datos['nombre'],
            'codigo' => $datos['codigo'],
            'descripcion' => $datos['descripcion'] ?? ''
        ]);

        if ($resultado) {
            return ['success' => 'Materia actualizada exitosamente'];
        }
        return ['error' => 'Error al actualizar la materia'];
    }

    private function cambiarEstado($datos) {
        if (empty($datos['id']) || !isset($datos['activo'])) {
            return ['error' => 'Datos incompletos'];
        }

        $resultado = $this->modelo->cambiarEstado(
            (int)$datos['id'],
            (bool)$datos['activo']
        );

        if ($resultado) {
            return ['success' => 'Estado de la materia actualizado exitosamente'];
        }
        return ['error' => 'Error al actualizar el estado de la materia'];
    }

}

