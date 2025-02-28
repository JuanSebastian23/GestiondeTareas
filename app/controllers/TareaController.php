<?php
if (!defined('ROOT_PATH')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
}

require_once(MODELS_PATH . '/TareaModel.php');
require_once(MODELS_PATH . '/EstadoModel.php');

class TareaController {
    private $model;
    private $estadoModel;

    public function __construct() {
        $this->model = new TareaModel();
        $this->estadoModel = new EstadoModel();
    }

    public function obtenerTareasParaEstudiantes() {
        if (!isset($_SESSION['user_id'])) {
            die("Acceso denegado.");
        }

        $estudiante_id = $_SESSION['user_id'];
        return $this->model->getTareasConDetalles($estudiante_id);
    }

    public function obtenerTareasFiltradas($materia = null, $estado = null) {
        if (!isset($_SESSION['user_id'])) {
            die("Acceso denegado.");
        }

        $estudiante_id = $_SESSION['user_id'];
        return $this->model->getTareasFiltradas($estudiante_id, $materia, $estado);
    }

    /**
     * Obtiene las tareas asignadas a un profesor con información de entregas
     * 
     * @param int $profesorId ID del profesor
     * @param int|null $grupoId Filtro de grupo (opcional)
     * @param int|null $materiaId Filtro de materia (opcional)
     * @param int|null $estadoId Filtro de estado (opcional)
     * @return array Lista de tareas con información de entregas
     */
    public function obtenerTareasAsignadasConEntregas($profesorId, $grupoId = null, $materiaId = null, $estadoId = null) {
        // Obtener tareas asignadas al profesor
        $tareas = $this->model->obtenerTareasPorProfesor($profesorId, $grupoId, $materiaId, $estadoId);
        
        // Enriquecer con información de entregas
        foreach ($tareas as &$tarea) {
            $entregas = $this->model->obtenerEntregasPorTarea($tarea['id']);
            $tarea['entregadas'] = count($entregas);
            
            // Contar estudiantes en el grupo
            $tarea['total_estudiantes'] = $this->model->contarEstudiantesPorGrupo($tarea['grupo_id']);
            
            // Agregar estado actual de la tarea
            $estadoActual = $this->model->obtenerEstadoTarea($tarea['id']);
            $tarea['estado'] = $estadoActual['nombre'] ?? 'pendiente';
        }
        
        return $tareas;
    }

    /**
     * Obtiene la lista de estados posibles para las tareas
     */
    public function obtenerEstadosTarea() {
        return $this->estadoModel->obtenerTodos();
    }

    /**
     * Obtiene los grupos asignados a un profesor
     */
    public function obtenerGruposPorProfesor($profesorId) {
        return $this->model->obtenerGruposPorProfesor($profesorId);
    }

    /**
     * Obtiene las materias asignadas a un profesor
     */
    public function obtenerMateriasPorProfesor($profesorId) {
        return $this->model->obtenerMateriasPorProfesor($profesorId);
    }

    /**
     * Califica una entrega de tarea
     */
    public function calificarEntrega($datos) {
        // Validar los datos recibidos
        if (empty($datos['entrega_id']) || !isset($datos['calificacion'])) {
            return ['error' => 'Datos incompletos para calificar'];
        }

        // Verificar que la calificación esté en el rango válido
        $calificacion = floatval($datos['calificacion']);
        if ($calificacion < 0 || $calificacion > 10) {
            return ['error' => 'La calificación debe estar entre 0 y 10'];
        }

        // Obtener el ID del estado 'calificada'
        $estadoCalificada = $this->estadoModel->obtenerPorNombre('calificada');
        if (!$estadoCalificada) {
            return ['error' => 'No se pudo obtener el estado de calificada'];
        }

        // Preparar los datos para la actualización
        $datosActualizar = [
            'id' => $datos['entrega_id'],
            'calificacion' => $calificacion,
            'comentarios' => $datos['comentarios'] ?? '',
            'estado_id' => $estadoCalificada['id']
        ];

        // Actualizar la entrega
        $resultado = $this->model->actualizarEntrega($datosActualizar);
        if ($resultado) {
            return ['success' => 'Entrega calificada correctamente'];
        } else {
            return ['error' => 'Error al calificar la entrega'];
        }
    }

    /**
     * Cambia el estado de una tarea
     */
    public function cambiarEstadoTarea($datos) {
        if (empty($datos['tarea_id']) || empty($datos['estado_id'])) {
            return ['error' => 'Datos incompletos para cambiar el estado'];
        }

        $resultado = $this->model->actualizarEstadoTarea($datos['tarea_id'], $datos['estado_id']);
        if ($resultado) {
            $nuevoEstado = $this->estadoModel->obtenerPorId($datos['estado_id']);
            return ['success' => 'Estado de la tarea actualizado a ' . ucfirst($nuevoEstado['nombre'])];
        } else {
            return ['error' => 'Error al actualizar el estado de la tarea'];
        }
    }
}
?>
