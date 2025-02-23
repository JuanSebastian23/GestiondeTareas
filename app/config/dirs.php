<?php
// Rutas base
define('ROOT_PATH', dirname(dirname(__DIR__)));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', APP_PATH . '/config');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('ASSETS_PATH', PUBLIC_PATH . '/assets');

// Rutas MVC principales
define('CONTROLLERS_PATH', APP_PATH . '/controllers');
define('MODELS_PATH', APP_PATH . '/models');
define('VIEWS_PATH', APP_PATH . '/views');
define('LAYOUTS_PATH', VIEWS_PATH . '/layouts');

// URL Base - Ajusta esto según tu configuración de servidor
define('BASE_URL', '/GestiondeTareas');

// Helper function para construir URLs
function url($path = '') {
    return BASE_URL . '/' . ltrim($path, '/');
}
?>