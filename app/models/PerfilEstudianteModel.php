<?php
// app/models/PerfilEstudianteModel.php

// Incluimos la configuración de la base de datos (DbConfig.php)
// Esta ruta es consistente con MateriaModel.php y otros modelos que funcionan.
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/DbConfig.php');

class PerfilEstudianteModel {
    private $conn; // Aquí almacenaremos la conexión mysqli

    public function __construct() {
        // Establecemos la conexión usando mysqli_ (consistente con MateriaModel.php)
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        // Verificamos si hay errores de conexión al construir el objeto
        if ($this->conn->connect_error) {
            // En caso de un error de conexión, terminamos la ejecución.
            // Para depuración: die() muestra el error. En producción, loguear y mostrar un mensaje genérico.
            die("Error de conexión a la base de datos: " . $this->conn->connect_error);
        }

        // Configuramos el conjunto de caracteres a utf8mb4 para evitar problemas con codificación (tildes, ñ, etc.)
        $this->conn->set_charset("utf8mb4");
    }

    /**
     * Obtiene los datos de un usuario por su ID, incluyendo el nombre del grupo si es estudiante.
     * Esta consulta ha sido ajustada para coincidir con el esquema de la base de datos proporcionado.
     *
     * @param int $userId El ID del usuario.
     * @return array|null Los datos del usuario y su grupo, o null si no se encuentra.
     */
    public function getEstudianteProfile($userId) {
        $query = "
            SELECT
                u.id,
                u.username,
                u.email,
                u.nombre,
                u.apellidos,         -- Corregido: 'apellidos' (plural) según el esquema
                r.nombre AS rol,     -- Corregido: 'nombre' en tabla roles según el esquema
                g.nombre AS nombre_grupo,
                g.descripcion AS descripcion_grupo
            FROM
                usuarios u
            LEFT JOIN
                roles r ON u.rol_id = r.id
            LEFT JOIN
                estudiante_grupo eg ON u.id = eg.estudiante_id
            LEFT JOIN
                grupos g ON eg.grupo_id = g.id
            WHERE
                u.id = ?
            LIMIT 1;
        ";

        // Preparamos la consulta SQL para evitar inyección SQL
        $stmt = $this->conn->prepare($query);

        // --- INICIO DEL BLOQUE DE DIAGNÓSTICO DE ERRORES AL PREPARAR LA CONSULTA ---
        // Este bloque es vital para identificar problemas en la sintaxis SQL o en el esquema.
        if ($stmt === false) {
            // Registra el error completo en los logs del servidor PHP
            error_log("Error al preparar la consulta en PerfilEstudianteModel: " . $this->conn->error . " SQL: " . $query);
            // Muestra un mensaje de error detallado en el navegador (solo para depuración)
            die("Error al preparar la consulta de perfil: " . $this->conn->error . "<br>Consulta SQL: " . htmlspecialchars($query));
        }
        // --- FIN DEL BLOQUE DE DIAGNÓSTICO DE ERRORES ---

        // Vinculamos el parámetro $userId al placeholder '?' en la consulta.
        // 'i' indica que el tipo de dato es un entero (integer).
        $stmt->bind_param("i", $userId);
        
        // Ejecutamos la consulta preparada
        $stmt->execute();
        
        // Obtenemos el objeto de resultado de la consulta
        $result = $stmt->get_result();

        // Retornamos la primera fila de resultados como un array asociativo.
        // Si no se encuentra ninguna fila, devuelve null.
        return $result->fetch_assoc();
    }

    /**
     * Método destructor. Se llama automáticamente cuando el objeto es destruido (por ejemplo, al finalizar el script).
     * Asegura que la conexión a la base de datos se cierre limpiamente.
     */
    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>