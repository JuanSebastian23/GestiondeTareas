<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/models/NotificationModel.php');

class NotificationController {
    private $notificationModel;

    public function __construct() {
        $this->notificationModel = new NotificationModel();
    }

    public function obtenerNotificaciones($usuario_id) {
        return $this->notificationModel->obtenerNotificaciones($usuario_id);
    }

    // Agregar $usuario_id como argumento
    public function marcarNotificacionLeida($id, $usuario_id) {
        return $this->notificationModel->marcarComoLeida($id, $usuario_id);
    }
}
?>
