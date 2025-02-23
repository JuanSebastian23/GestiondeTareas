<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
require_once(MODELS_PATH . '/Usuario.php');

class AuthController {
    private $usuario;

    public function __construct() {
        $this->usuario = new Usuario();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login($email, $password) {
        try {
            $usuario = $this->usuario->getUserByEmail($email);
            
            if (!$usuario) {
                return ['success' => false, 'message' => 'Correo electrónico no encontrado'];
            }

            if (!$usuario['activo']) {
                return ['success' => false, 'message' => 'Usuario inactivo'];
            }

            // Comparamos las contraseñas sin hashear
            if ($password === $usuario['password']) {
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['username'] = $usuario['username'];
                $_SESSION['nombre_completo'] = $usuario['nombre'] . ' ' . $usuario['apellidos'];
                $_SESSION['email'] = $usuario['email'];
                $_SESSION['rol'] = $usuario['rol_nombre'];
                $_SESSION['rol_id'] = $usuario['rol_id'];
                
                return ['success' => true, 'rol' => $usuario['rol_nombre']];
            }

            return ['success' => false, 'message' => 'Contraseña incorrecta'];
        } catch (Exception $e) {
            error_log("Error en login: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al intentar iniciar sesión'];
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: ' . BASE_URL . '/app/views/auth/login.php');
        exit();
    }

    public function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/app/views/auth/login.php');
            exit();
        }
        return true;
    }

    public function getCurrentUser() {
        return [
            'id' => $_SESSION['user_id'] ?? null,
            'nombre_completo' => $_SESSION['nombre_completo'] ?? null,
            'email' => $_SESSION['email'] ?? null,
            'rol' => $_SESSION['rol'] ?? null
        ];
    }
}
