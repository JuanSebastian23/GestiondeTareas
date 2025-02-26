<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/controllers/NotificationController.php');

$notificacionController = new NotificationController();

// Verificar si se envían los parámetros correctos
if (isset($_GET['id']) && isset($_GET['usuario_id'])) {
    $id = intval($_GET['id']); // Convertir a entero por seguridad
    $usuario_id = intval($_GET['usuario_id']); // Convertir a entero

    $notificacionController->marcarNotificacionLeida($id, $usuario_id);
} else {
    echo "Error: Falta el ID de la notificación o el usuario.";
}
?>
