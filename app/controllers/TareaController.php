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
        try {
            $this->model = new TareaModel();
            $this->estadoModel = new EstadoModel();
        } catch (Exception $e) {
            error_log("Error al inicializar TareaController: " . $e->getMessage());
            throw $e;
        }
    }

    public function obtenerTareasParaEstudiantes() {
        try {
            if (!isset($_SESSION['user_id'])) {
                throw new Exception("Acceso denegado.");
            }

            $estudiante_id = $_SESSION['user_id'];
            return $this->model->getTareasConDetalles($estudiante_id);
        } catch (Exception $e) {
            error_log("Error al obtener tareas para estudiantes: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerTareasFiltradas($materia = null, $estado = null) {
        try {
            if (!isset($_SESSION['user_id'])) {
                throw new Exception("Acceso denegado.");
            }

            $estudiante_id = $_SESSION['user_id'];
            return $this->model->getTareasFiltradas($estudiante_id, $materia, $estado);
        } catch (Exception $e) {
            error_log("Error al obtener tareas filtradas: " . $e->getMessage());
            return [];
        }
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
        try {
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
        } catch (Exception $e) {
            error_log("Error al obtener tareas asignadas con entregas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene la lista de estados posibles para las tareas
     */
    public function obtenerEstadosTarea() {
        try {
            return $this->estadoModel->obtenerTodos();
        } catch (Exception $e) {
            error_log("Error al obtener estados de tarea: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene los grupos asignados a un profesor
     */
    public function obtenerGruposPorProfesor($profesorId) {
        try {
            return $this->model->obtenerGruposPorProfesor($profesorId);
        } catch (Exception $e) {
            error_log("Error al obtener grupos por profesor: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene las materias asignadas a un profesor
     */
    public function obtenerMateriasPorProfesor($profesorId) {
        try {
            return $this->model->obtenerMateriasPorProfesor($profesorId);
        } catch (Exception $e) {
            error_log("Error al obtener materias por profesor: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Califica una entrega de tarea
     */
    public function calificarEntrega($datos) {
        try {
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
        } catch (Exception $e) {
            error_log("Error al calificar entrega: " . $e->getMessage());
            return ['error' => 'Error al calificar la entrega'];
        }
    }

    /**
     * Cambia el estado de una tarea
     */
    public function cambiarEstadoTarea($datos) {
        try {
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
        } catch (Exception $e) {
            error_log("Error al cambiar estado de tarea: " . $e->getMessage());
            return ['error' => 'Error al actualizar el estado de la tarea'];
        }
    }

    // Asegurarse de tener un método para obtener estadísticas de tareas
    public function obtenerEstadisticas() {
        try {
            $tareaModel = new TareaModel();
            
            return [
                'total_tareas' => $tareaModel->contarTodasLasTareas(),
                'tareas_activas' => $tareaModel->contarTareasActivas(),
                'tareas_completadas' => $tareaModel->contarTareasPorEstado('Completada'),
                'tareas_pendientes' => $tareaModel->contarTareasPorEstado('Pendiente')
            ];
        } catch (Exception $e) {
            error_log("Error al obtener estadísticas: " . $e->getMessage());
            return [
                'total_tareas' => 0,
                'tareas_activas' => 0,
                'tareas_completadas' => 0,
                'tareas_pendientes' => 0
            ];
        }
    }

    /**
     * Cuenta el número de estudiantes en un grupo específico
     * @param int $grupoId ID del grupo
     * @return int Número de estudiantes en el grupo
     */
    public function contarEstudiantesPorGrupo($grupoId) {
        try {
            return $this->model->contarEstudiantesPorGrupo($grupoId);
        } catch (Exception $e) {
            error_log("Error al contar estudiantes por grupo: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Obtiene las notificaciones de un usuario
     * @param int $usuarioId ID del usuario
     * @param int $limit Límite de notificaciones a obtener (opcional)
     * @return array Notificaciones del usuario
     */
    public function obtenerNotificaciones($usuarioId, $limit = null) {
        try {
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if ($conn->connect_error) {
                throw new Exception("Error de conexión: " . $conn->connect_error);
            }
            
            $sql = "SELECT * FROM notificaciones WHERE usuario_id = ? ORDER BY created_at DESC";
            if ($limit) {
                $sql .= " LIMIT ?";
            }
            
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparando la consulta: " . $conn->error);
            }
            
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
        } catch (Exception $e) {
            error_log("Error al obtener notificaciones: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Cuenta las notificaciones no leídas de un usuario
     * @param int $usuarioId ID del usuario
     * @return int Número de notificaciones no leídas
     */
    public function contarNotificacionesNoLeidas($usuarioId) {
        try {
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if ($conn->connect_error) {
                throw new Exception("Error de conexión: " . $conn->connect_error);
            }
            
            $sql = "SELECT COUNT(*) as total FROM notificaciones WHERE usuario_id = ? AND leida = 0";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparando la consulta: " . $conn->error);
            }
            
            $stmt->bind_param("i", $usuarioId);
            $stmt->execute();
            
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            $conn->close();
            return $row['total'];
        } catch (Exception $e) {
            error_log("Error al contar notificaciones no leídas: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Marca todas las notificaciones de un usuario como leídas
     * @param int $usuarioId ID del usuario
     * @return bool Éxito de la operación
     */
    public function marcarTodasComoLeidas($usuarioId) {
        try {
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if ($conn->connect_error) {
                throw new Exception("Error de conexión: " . $conn->connect_error);
            }
            
            $sql = "UPDATE notificaciones SET leida = 1 WHERE usuario_id = ? AND leida = 0";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparando la consulta: " . $conn->error);
            }
            
            $stmt->bind_param("i", $usuarioId);
            $result = $stmt->execute();
            
            $conn->close();
            return $result;
        } catch (Exception $e) {
            error_log("Error al marcar notificaciones como leídas: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Marca una notificación específica como leída
     * @param int $notificacionId ID de la notificación
     * @param int $usuarioId ID del usuario (para verificar que le pertenece)
     * @return bool Éxito de la operación
     */
    public function marcarComoLeida($notificacionId, $usuarioId) {
        try {
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if ($conn->connect_error) {
                throw new Exception("Error de conexión: " . $conn->connect_error);
            }
            
            $sql = "UPDATE notificaciones SET leida = 1 WHERE id = ? AND usuario_id = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparando la consulta: " . $conn->error);
            }
            
            $stmt->bind_param("ii", $notificacionId, $usuarioId);
            $result = $stmt->execute();
            
            $conn->close();
            return $result;
        } catch (Exception $e) {
            error_log("Error al marcar notificación como leída: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Crea una nueva tarea y envía notificaciones a los estudiantes del grupo
     * @param array $datos Datos de la tarea a crear
     * @return array Respuesta de la operación
     */
    public function crearTarea($datos) {
        // Validar datos
        if (empty($datos['titulo']) || empty($datos['fecha_entrega']) || 
            empty($datos['materia_id']) || empty($datos['grupo_id']) || 
            empty($datos['profesor_id'])) {
            
            error_log("Datos incompletos para crear tarea: " . print_r($datos, true));
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
                try {
                    $this->enviarNotificacionesTarea($tareaId, $datos['grupo_id'], $datos['titulo'], $datos['materia_id']);
                } catch (Exception $e) {
                    error_log("Error al enviar notificaciones: " . $e->getMessage());
                    // No hacemos fallar la creación si las notificaciones fallan
                }
                
                return [
                    'success' => true,
                    'message' => 'Tarea creada exitosamente',
                    'id' => $tareaId
                ];
            } else {
                error_log("No se pudo insertar la tarea en la base de datos");
                return ['error' => 'No se pudo crear la tarea'];
            }
        } catch (Exception $e) {
            error_log("Excepción al crear tarea: " . $e->getMessage());
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
        try {
            // Obtener el nombre de la materia
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if ($conn->connect_error) {
                throw new Exception("Error de conexión: " . $conn->connect_error);
            }
            
            $sql = "SELECT nombre FROM materias WHERE id = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparando la consulta: " . $conn->error);
            }
            
            $stmt->bind_param("i", $materiaId);
            $stmt->execute();
            $result = $stmt->get_result();
            $materia = $result->fetch_assoc();
            $nombreMateria = $materia ? $materia['nombre'] : 'la materia';
            
            // Obtener estudiantes del grupo
            $sql = "SELECT estudiante_id FROM estudiante_grupo WHERE grupo_id = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparando la consulta: " . $conn->error);
            }
            
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
            if (!$stmt) {
                throw new Exception("Error preparando la consulta: " . $conn->error);
            }
            
            foreach ($estudiantes as $estudianteId) {
                $stmt->bind_param("iss", $estudianteId, $titulo, $mensaje);
                if ($stmt->execute()) {
                    $insertados++;
                }
            }
            
            $conn->close();
            return $insertados > 0;
        } catch (Exception $e) {
            error_log("Error al enviar notificaciones de tarea: " . $e->getMessage());
            throw $e; // Re-lanzamos la excepción para que sea capturada por el llamador
        }
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
        
        try {
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
                // Obtener información del estudiante
                $estudiante = $this->obtenerDatosEstudiante($datos['estudiante_id']);
                
                // Obtener el profesor asignado a la tarea
                $profesorId = $tarea['profesor_id'];
                
                // Enviar notificación al profesor
                try {
                    $this->enviarNotificacionEntrega(
                        $profesorId,
                        $estudiante,
                        $tarea,
                        $datos['archivo_adjunto'] ?? null
                    );
                } catch (Exception $e) {
                    error_log("Error al enviar notificación de entrega: " . $e->getMessage());
                    // Continuamos aunque la notificación falle
                }
                
                return ['success' => 'Tarea entregada correctamente'];
            } else {
                return ['error' => 'No se pudo registrar la entrega'];
            }
        } catch (Exception $e) {
            error_log('Error en entregarTarea: ' . $e->getMessage());
            return ['error' => 'Error al entregar la tarea: ' . $e->getMessage()];
        }
    }

    /**
     * Obtiene datos básicos de un estudiante
     * @param int $estudianteId ID del estudiante
     * @return array Datos del estudiante
     */
    private function obtenerDatosEstudiante($estudianteId) {
        try {
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if ($conn->connect_error) {
                throw new Exception("Error de conexión: " . $conn->connect_error);
            }
            
            $sql = "SELECT id, nombre, apellidos FROM usuarios WHERE id = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparando la consulta: " . $conn->error);
            }
            
            $stmt->bind_param("i", $estudianteId);
            $stmt->execute();
            $result = $stmt->get_result();
            $estudiante = $result->fetch_assoc();
            
            $conn->close();
            return $estudiante;
        } catch (Exception $e) {
            error_log("Error al obtener datos del estudiante: " . $e->getMessage());
            return [
                'id' => $estudianteId,
                'nombre' => 'Usuario',
                'apellidos' => 'Desconocido'
            ];
        }
    }

    /**
     * Envía una notificación al profesor cuando un estudiante entrega una tarea
     * @param int $profesorId ID del profesor
     * @param array $estudiante Datos del estudiante
     * @param array $tarea Datos de la tarea
     * @param string|null $archivoAdjunto Nombre del archivo adjunto (si existe)
     */
    private function enviarNotificacionEntrega($profesorId, $estudiante, $tarea, $archivoAdjunto) {
        try {
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if ($conn->connect_error) {
                throw new Exception("Error de conexión: " . $conn->connect_error);
            }
            
            // Crear el título y mensaje de la notificación
            $nombreCompleto = $estudiante['nombre'] . ' ' . $estudiante['apellidos'];
            $titulo = "Nueva entrega: " . $tarea['titulo'];
            
            // Crear mensaje con o sin referencia al archivo adjunto
            $mensaje = $nombreCompleto . " ha entregado la tarea '" . $tarea['titulo'] . "'";
            if ($archivoAdjunto) {
                $mensaje .= " con un archivo adjunto.";
            } else {
                $mensaje .= ".";
            }
            
            // Insertar la notificación para el profesor
            $sql = "INSERT INTO notificaciones (usuario_id, titulo, mensaje, leida) VALUES (?, ?, ?, 0)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparando la consulta: " . $conn->error);
            }
            
            $stmt->bind_param("iss", $profesorId, $titulo, $mensaje);
            $stmt->execute();
            
            $conn->close();
        } catch (Exception $e) {
            error_log("Error al enviar notificación de entrega: " . $e->getMessage());
            throw $e; // Re-lanzamos la excepción para que pueda ser manejada por el llamador
        }
    }

    /**
     * Verifica si un estudiante ya ha entregado una tarea
     * @param int $tareaId ID de la tarea
     * @param int $estudianteId ID del estudiante
     * @return array|null Datos de la entrega o null si no existe
     */
    public function verificarEntrega($tareaId, $estudianteId) {
        try {
            return $this->model->obtenerEntregaPorEstudiante($tareaId, $estudianteId);
        } catch (Exception $e) {
            error_log("Error al verificar entrega: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtiene los detalles de una tarea específica por su ID
     * @param int $tareaId ID de la tarea
     * @return array|null Detalles de la tarea o null si no existe
     */
    public function obtenerTareaPorId($tareaId) {
        try {
            return $this->model->obtenerTareaPorId($tareaId);
        } catch (Exception $e) {
            error_log("Error al obtener tarea por ID: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtiene todas las entregas para una tarea específica
     * @param int $tareaId ID de la tarea
     * @return array Lista de entregas
     */
    public function obtenerEntregasPorTarea($tareaId) {
        try {
            return $this->model->obtenerEntregasPorTarea($tareaId);
        } catch (Exception $e) {
            error_log("Error al obtener entregas por tarea: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Cuenta el número de entregas pendientes de revisión para un profesor
     * @param int $profesorId ID del profesor
     * @return int Número de entregas pendientes
     */
    public function contarEntregasPendientes($profesorId) {
        if (!$profesorId) {
            return 0;
        }
        
        try {
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if ($conn->connect_error) {
                throw new Exception("Error de conexión: " . $conn->connect_error);
            }
            
            // Obtenemos entregas que están en estado "entregada" (no calificadas)
            // y que pertenecen a tareas creadas por este profesor
            $sql = "SELECT COUNT(*) as total FROM entregas_tarea et
                    INNER JOIN estados_tarea est ON et.estado_id = est.id
                    INNER JOIN tareas t ON et.tarea_id = t.id
                    WHERE t.profesor_id = ? 
                    AND est.nombre = 'entregada'";
                    
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparando la consulta: " . $conn->error);
            }
            
            $stmt->bind_param("i", $profesorId);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            $conn->close();
            return (int)($row['total'] ?? 0);
        } catch (Exception $e) {
            error_log("Error al contar entregas pendientes: " . $e->getMessage());
            return 0;
        }
    }
}
?>
