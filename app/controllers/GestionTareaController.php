<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
require_once(MODELS_PATH . '/TareaModel.php');

class GestionTareaController {
public function procesarCreacionTarea($datos) {
    $errores = [];

    if (!isset($datos['titulo']) || empty($datos['titulo'])) {
        $errores[] = 'Título faltante';
    }
    if (!isset($datos['descripcion']) || empty($datos['descripcion'])) {
        $errores[] = 'Descripción faltante';
    }
    if (!isset($datos['fecha_entrega']) || empty($datos['fecha_entrega'])) {
        $errores[] = 'Fecha de entrega faltante';
    }
    if (!isset($datos['materia_id']) || empty($datos['materia_id'])) {
        $errores[] = 'Materia faltante';
    }
    if (!isset($datos['grupo_id']) || empty($datos['grupo_id'])) {
        $errores[] = 'Grupo faltante';
    }
    if (!isset($datos['profesor_id']) || empty($datos['profesor_id'])) {
        $errores[] = 'Profesor faltante';
    }
    
    
    if (!empty($errores)) {
        return ['success' => false, 'message' => 'Faltan datos', 'errores' => $errores];
    }

    $titulo = $datos['titulo'];
    $descripcion = $datos['descripcion'];
    $fechaEntrega = date('Y-m-d H:i:s', strtotime($datos['fecha_entrega']));
    $materiaId = $datos['materia_id'];
    $grupoId = $datos['grupo_id'];
    $profesorId = $datos['profesor_id'];
    
    

    $tareaModel = new TareaModel();
    $resultado = $tareaModel->insertarTarea($titulo, $descripcion, $fechaEntrega, $materiaId, $grupoId, $profesorId);

    if ($resultado) {
        return ['success' => true, 'message' => 'Tarea creada correctamente'];
    } else {
        return ['success' => false, 'message' => 'Error al crear la tarea'];
    }
}

public function obtenerTareasActivas() {
    $tareaModel = new TareaModel();
    return $tareaModel->getTareasActivas();
    }
    public function obtenerTodasLasTareasConDetalles() {
    $tareaModel = new TareaModel();
    return $tareaModel->getTodasLasTareasConDetalles();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$gestionTareaController = new GestionTareaController();
$resultado = $gestionTareaController->procesarCreacionTarea($_POST);
echo json_encode($resultado);
}
?>