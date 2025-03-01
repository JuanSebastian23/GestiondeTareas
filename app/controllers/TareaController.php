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

    // Asegurarse de tener un método para obtener estadísticas de tareas
    public function obtenerEstadisticas() {
        $tareaModel = new TareaModel();
        
        return [
            'total_tareas' => $tareaModel->contarTodasLasTareas(),
            'tareas_activas' => $tareaModel->contarTareasActivas(),
            'tareas_completadas' => $tareaModel->contarTareasPorEstado('Completada'),
            'tareas_pendientes' => $tareaModel->contarTareasPorEstado('Pendiente')
        ];
    }

    /**
     * Cuenta el número de estudiantes en un grupo específico
     * @param int $grupoId ID del grupo
     * @return int Número de estudiantes en el grupo
     */
    public function contarEstudiantesPorGrupo($grupoId) {
        return $this->model->contarEstudiantesPorGrupo($grupoId);
    }

    /**
     * Obtiene las notificaciones de un usuario
     * @param int $usuarioId ID del usuario
     * @param int $limit Límite de notificaciones a obtener (opcional)
     * @return array Notificaciones del usuario
     */
    public function obtenerNotificaciones($usuarioId, $limit = null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        $sql = "SELECT * FROM notificaciones WHERE usuario_id = ? ORDER BY created_at DESC";
        if ($limit) {
            $sql .= " LIMIT ?";
        }
        
        $stmt = $conn->prepare($sql);
        
        if ($limit) {
            $stmt->bind_param("ii", $usuarioId, $limit);
        } else {
            $stmt->bind_param("i", $usuarioId);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $notificaciones = $result->fetch_all(MYSQLI_ASSOC);
        
        $conn->close();
        return $notificaciones;
    }

    /**
     * Cuenta las notificaciones no leídas de un usuario
     * @param int $usuarioId ID del usuario
     * @return int Número de notificaciones no leídas
     */
    public function contarNotificacionesNoLeidas($usuarioId) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        $sql = "SELECT COUNT(*) as total FROM notificaciones WHERE usuario_id = ? AND leida = 0";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        $conn->close();
        return $row['total'];
    }

    /**
     * Marca todas las notificaciones de un usuario como leídas
     * @param int $usuarioId ID del usuario
     * @return bool Éxito de la operación
     */
    public function marcarTodasComoLeidas($usuarioId) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        $sql = "UPDATE notificaciones SET leida = 1 WHERE usuario_id = ? AND leida = 0";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $usuarioId);
        $result = $stmt->execute();
        
        $conn->close();
        return $result;
    }

    /**
     * Marca una notificación específica como leída
     * @param int $notificacionId ID de la notificación
     * @param int $usuarioId ID del usuario (para verificar que le pertenece)
     * @return bool Éxito de la operación
     */
    public function marcarComoLeida($notificacionId, $usuarioId) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        $sql = "UPDATE notificaciones SET leida = 1 WHERE id = ? AND usuario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $notificacionId, $usuarioId);
        $result = $stmt->execute();
        
        $conn->close();
        return $result;
    }

    /**
     * Crea una nueva tarea y envía notificaciones a los estudiantes del grupo
     * @param array $datos Datos de la tarea a crear
     * @return array Respuesta de la operación
     */
    public function crearTarea($datos) {
        // Validar datos
        if (empty($datos['titulo']) || empty($datos['fecha_entrega']) || 
            empty($datos['materia_id']) || empty($datos['grupo_id'])) {
            return ['error' => 'Todos los campos obligatorios deben ser completados'];
        }
        
        try {
            // Insertar la tarea
            $tareaId = $this->model->insertarTarea(
                $datos['titulo'],
                $datos['descripcion'] ?? '',
                $datos['fecha_entrega'],
                $datos['materia_id'],
                $datos['grupo_id'],
                $datos['profesor_id']
            );
            
            if ($tareaId) {
                // Enviar notificaciones a los estudiantes del grupo
                $this->enviarNotificacionesTarea($tareaId, $datos['grupo_id'], $datos['titulo'], $datos['materia_id']);
                
                return [
                    'success' => true,
                    'message' => 'Tarea creada exitosamente',
                    'id' => $tareaId
                ];
            } else {
                return ['error' => 'No se pudo crear la tarea'];
            }
        } catch (Exception $e) {
            return ['error' => 'Error al crear la tarea: ' . $e->getMessage()];
        }
    }

    /**
     * Envía notificaciones a los estudiantes del grupo sobre una nueva tarea
     * @param int $tareaId ID de la tarea
     * @param int $grupoId ID del grupo
     * @param string $tituloTarea Título de la tarea
     * @param int $materiaId ID de la materia
     * @return bool Éxito de la operación
     */
    private function enviarNotificacionesTarea($tareaId, $grupoId, $tituloTarea, $materiaId) {
        // Obtener el nombre de la materia
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        $sql = "SELECT nombre FROM materias WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $materiaId);
        $stmt->execute();
        $result = $stmt->get_result();
        $materia = $result->fetch_assoc();
        $nombreMateria = $materia ? $materia['nombre'] : 'la materia';
        
        // Obtener estudiantes del grupo
        $sql = "SELECT estudiante_id FROM estudiante_grupo WHERE grupo_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $grupoId);
        $stmt->execute();
        $result = $stmt->get_result();
        $estudiantes = [];
        
        while ($row = $result->fetch_assoc()) {
            $estudiantes[] = $row['estudiante_id'];
        }
        
        // Preparar notificación
        $titulo = "Nueva tarea: " . $tituloTarea;
        $mensaje = "Se ha asignado una nueva tarea para " . $nombreMateria . ". Revisa tus pendientes.";
        
        // Insertar notificaciones para cada estudiante
        $insertados = 0;
        $sql = "INSERT INTO notificaciones (usuario_id, titulo, mensaje, leida) VALUES (?, ?, ?, 0)";
        $stmt = $conn->prepare($sql);
        
        foreach ($estudiantes as $estudianteId) {
            $stmt->bind_param("iss", $estudianteId, $titulo, $mensaje);
            if ($stmt->execute()) {
                $insertados++;
            }
        }
        
        $conn->close();
        return $insertados > 0;
    }

    /**
     * Permite a un estudiante entregar una tarea
     * @param array $datos Datos de la entrega
     * @return array Resultado de la operación
     */
    public function entregarTarea($datos) {
        // Validar datos obligatorios
        if (empty($datos['tarea_id']) || empty($datos['estudiante_id'])) {
            return ['error' => 'Faltan datos obligatorios'];
        }
        
        // Verificar si ya existe una entrega previa
        $entregaExistente = $this->verificarEntrega($datos['tarea_id'], $datos['estudiante_id']);
        if ($entregaExistente) {
            return ['error' => 'Ya has entregado esta tarea anteriormente'];
        }
        
        // Verificar si la tarea aún está activa
        $tarea = $this->model->obtenerTareaPorId($datos['tarea_id']);
        if (!$tarea) {
            return ['error' => 'La tarea no existe'];
        }
        
        $fechaActual = time();
        $fechaEntrega = strtotime($tarea['fecha_entrega']);
        
        if ($fechaEntrega < $fechaActual) {
            return ['error' => 'No se pueden entregar tareas vencidas'];
        }
        
        // Preparar datos para la entrega
        $datosEntrega = [
            'tarea_id' => $datos['tarea_id'],
            'estudiante_id' => $datos['estudiante_id'],
            'comentarios' => $datos['comentarios'] ?? '',
            'archivo_adjunto' => $datos['archivo_adjunto'] ?? null
        ];
        
        // Realizar la entrega
        $resultado = $this->model->registrarEntrega($datosEntrega);
        
        if ($resultado) {
            return ['success' => 'Tarea entregada correctamente'];
        } else {
            return ['error' => 'No se pudo registrar la entrega'];
        }
    }

    /**
     * Verifica si un estudiante ya ha entregado una tarea
     * @param int $tareaId ID de la tarea
     * @param int $estudianteId ID del estudiante
     * @return array|null Datos de la entrega o null si no existe
     */
    public function verificarEntrega($tareaId, $estudianteId) {
        return $this->model->obtenerEntregaPorEstudiante($tareaId, $estudianteId);
    }
}
?>
