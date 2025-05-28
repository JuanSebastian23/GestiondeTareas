<?php
// app/controllers/PerfilController.php

// Incluimos el archivo de configuración de directorios (Dirs.php)
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
// Incluimos el modelo de perfil que acabamos de ajustar (PerfilEstudianteModel.php)
require_once(MODELS_PATH . '/PerfilEstudianteModel.php');

class PerfilController {
    private $perfilEstudianteModel;

    public function __construct() {
        try {
            // Instanciamos el modelo de perfil.
            // Aquí, el PerfilEstudianteModel.php ya se conecta a la BD al ser instanciado.
            $this->perfilEstudianteModel = new PerfilEstudianteModel();
        } catch (Exception $e) {
            // Registramos cualquier error que ocurra al inicializar el modelo.
            error_log("Error al inicializar PerfilController: " . $e->getMessage());
            // Relanzamos la excepción para que sea manejada en un nivel superior (ej. index.php)
            throw $e;
        }
    }

    /**
     * Método principal para procesar la solicitud del perfil del estudiante.
     * En este caso, solo se encarga de obtener y mostrar el perfil.
     */
    public function procesarSolicitud() {
        try {
            // 1. Verificación de seguridad y autenticación
            // Asegúrate de que el usuario esté logueado y que su ID y rol estén en la sesión.
            if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'estudiante') {
                // Si no está autorizado o no es estudiante, redirigimos al login.
                header('Location: ' . BASE_URL . '/app/views/auth/login.php');
                exit();
            }

            // Obtenemos el ID del usuario logueado desde la sesión.
            $userId = $_SESSION['user_id'];

            // 2. Obtener los datos del perfil usando el modelo
            $perfilData = $this->perfilEstudianteModel->getEstudianteProfile($userId);

            // 3. Manejo de errores si no se pudieron cargar los datos
            if (!$perfilData) {
                // Si no se encuentran datos, mostramos un mensaje de error o redirigimos.
                // Podrías usar sessionStorage aquí también si quieres mostrar un mensaje de error en la vista.
                $errorMessage = ['error' => 'No se pudieron cargar los datos de tu perfil. Por favor, intenta de nuevo más tarde.'];
                echo "<script>sessionStorage.setItem('operationResult', '" . json_encode($errorMessage) . "');</script>";
                // Cargar una vista de error o simplemente terminar.
                // Para este caso, solo incluiremos la vista y si $perfilData es null, la vista lo manejará.
                // Considera redirigir a una página de error si es un fallo crítico.
                // return; // No se hace return aquí para que la vista siempre se cargue.
            }

            // 4. Cargar la vista y pasarle los datos
            // La variable $perfilData (con los datos o null si hubo error) estará disponible en profile.php.
            require_once(VIEWS_PATH . '/student/profile.php');

        } catch (Exception $e) {
            // Registramos cualquier error que ocurra durante el procesamiento de la solicitud.
            error_log("Error al procesar solicitud de perfil: " . $e->getMessage());
            // Preparamos un mensaje de error para la vista.
            $errorMessage = ['error' => 'Error interno al procesar la solicitud de perfil: ' . $e->getMessage()];
            echo "<script>sessionStorage.setItem('operationResult', '" . json_encode($errorMessage) . "');</script>";
            // Aquí podrías cargar una vista de error genérica si lo prefieres, en lugar de solo el script.
            require_once(VIEWS_PATH . '/student/profile.php'); // O una vista de error dedicada
        }
    }
}
?>