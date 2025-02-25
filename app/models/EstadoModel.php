<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/DbConfig.php');

class EstadoModel {
    private $Edb;

    public function __construct() {
        $this->Edb = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($this->Edb->connect_error) {
            die("Error de conexión a la base de datos: " . $this->Edb->connect_error);
        }

        $this->Edb->set_charset("utf8mb4");
    }

    public function getEstadosDisponibles() {
        $sql = "SELECT id, nombre FROM estados_tarea";
        $result = $this->Edb->query($sql);

        if (!$result) {
            die("Error en la consulta: " . $this->Edb->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
