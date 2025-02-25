<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/DbConfig.php');

class NotificacionModel {
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

    // Marcar una notificación como leída
    public function marcarComoLeida($id) {
        $sql = "UPDATE notificaciones SET leida = 1 WHERE id = ?";
        $stmt = $this->ncon->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
