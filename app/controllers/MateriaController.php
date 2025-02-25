<?php
if (!defined('ROOT_PATH')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
}
require_once(MODELS_PATH . '/Materia.php');

class MateriaController {
    private $materiaModel;

    public function __construct() {
        $this->materiaModel = new Materia();
    }

    public function obtenerEstadisticas() {
        return [
            'total_materias' => $this->materiaModel->contarMaterias(true),
            'total_grupos' => $this->materiaModel->contarGruposAsignados(),
            'total_profesores' => $this->materiaModel->contarProfesoresAsignados(),
            'total_tareas' => $this->materiaModel->contarTareasAsignadas()
        ];
    }

    public function obtenerTodas() {
        return $this->materiaModel->obtenerTodas();
    }

    public function obtenerPorId($id) {
        return $this->materiaModel->obtenerPorId($id);
    }

    public function obtenerMateriasEstudiante($estudiante_id) {
        return $this->materiaModel->getMateriasEstudiante($estudiante_id);
    }

    public function obtenerGrupos($materia_id) {
        try {
            return $this->materiaModel->obtenerGrupos($materia_id);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
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

        $resultado = $this->materiaModel->crear([
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

        $resultado = $this->materiaModel->actualizar([
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

        $resultado = $this->materiaModel->cambiarEstado(
            (int)$datos['id'],
            (bool)$datos['activo']
        );

        if ($resultado) {
            return ['success' => 'Estado de la materia actualizado exitosamente'];
        }
        return ['error' => 'Error al actualizar el estado de la materia'];
    }

}

