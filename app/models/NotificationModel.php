<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/DbConfig.php');

class NotificationModel {
    private $ncon;

    public function __construct() {
        $this->ncon = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->ncon->connect_error) {
            die("Conexión fallida: " . $this->ncon->connect_error);
        }
    }

    // Obtener notificaciones de un usuario específico
    public function obtenerNotificaciones($usuario_id) {
        $sql = "SELECT id, titulo, mensaje, leida, created_at 
                FROM notificaciones 
                WHERE usuario_id = ? 
                ORDER BY created_at DESC 
                LIMIT 10";
        $stmt = $this->ncon->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $notificaciones = [];
        while ($row = $result->fetch_assoc()) {
            $notificaciones[] = $row;
        }

        return $notificaciones;
    }

    // Marcar una notificación como leída SOLO SI pertenece al usuario autenticado
    public function marcarComoLeida($id, $usuario_id) {
        $sql = "UPDATE notificaciones SET leida = 1 WHERE id = ? AND usuario_id = ?";
        $stmt = $this->ncon->prepare($sql);
        $stmt->bind_param("ii", $id, $usuario_id);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }
}
?>
