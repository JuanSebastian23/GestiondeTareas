<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/models/EntregaModel.php');

class EntregaController {
    private $entregaModel;

    public function __construct() {
        $this->entregaModel = new EntregaModel();
    }

    public function subirTarea() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["archivo"])) {
            session_start();
            $tareaId = $_POST["tarea_id"];
            $estudianteId = $_SESSION["estudiante_id"];
            
            $directorioSubida = $_SERVER['DOCUMENT_ROOT'] . "/GestiondeTareas/uploads/";
            $nombreArchivo = basename($_FILES["archivo"]["name"]);
            $rutaArchivo = $directorioSubida . $nombreArchivo;

            if (move_uploaded_file($_FILES["archivo"]["tmp_name"], $rutaArchivo)) {
                if ($this->entregaModel->registrarEntrega($tareaId, $estudianteId, $nombreArchivo)) {
                    echo json_encode(["status" => "success", "message" => "Tarea entregada correctamente."]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Error al registrar la entrega en la base de datos."]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Error al subir el archivo."]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "No se ha recibido ningún archivo."]);
        }
    }
}

// Manejo de la solicitud AJAX
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $controller = new EntregaController();
    $controller->subirTarea();
}
?>
