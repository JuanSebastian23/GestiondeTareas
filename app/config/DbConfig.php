<?php
/**
 * Configuración de la base de datos
 * 
 * Este archivo contiene las constantes necesarias para la conexión
 * con la base de datos MySQL.
 */

if (!defined('ROOT_PATH')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
}

// Definir constantes de conexión
define('DB_HOST', 'localhost');
define('DB_USER', 'root');      // Usuario de la base de datos
define('DB_PASS', '');          // Contraseña, por defecto vacía para XAMPP
define('DB_NAME', 'gestion_tareas_escolares');
?>