<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/controllers/NotificationController.php');

$notificacionController = new NotificacionController();
$notificacionController->marcarComoLeida();
?>
