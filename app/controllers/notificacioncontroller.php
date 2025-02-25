<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/models/NotificacionModel.php');

class NotificacionController {
    private $modelo;

    public function __construct() {
        $this->modelo = new NotificacionModel();
    }

    // Obtener notificaciones del usuario logueado
    public function obtenerNotificaciones() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(["error" => "Usuario no autenticado"]);
            return;
        }

        $usuario_id = $_SESSION['user_id'];
        $notificaciones = $this->modelo->obtenerNotificaciones($usuario_id);
        echo json_encode($notificaciones);
    }

    // Marcar una notificación como leída
    public function marcarComoLeida() {
        if (!isset($_GET['id'])) {
            echo json_encode(["error" => "ID no proporcionado"]);
            return;
        }

        $id = intval($_GET['id']);
        $resultado = $this->modelo->marcarComoLeida($id);
        echo json_encode(["success" => $resultado]);
    }
}
?>
