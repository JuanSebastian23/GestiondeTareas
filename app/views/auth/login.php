<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL);
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión Escolar - Colegio San Francisco de Asís</title>
    <link rel="icon" href="<?= BASE_URL ?>/public/assets/images/favicon.webp">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        <?php include(PUBLIC_PATH . '/assets/css/login.css'); ?>.login-container::before {
            background-image: linear-gradient(rgba(17, 24, 39, 0.6),
                    rgba(37, 99, 235, 0.4)),
                url('<?= BASE_URL ?>/public/assets/images/Background_login.webp');
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="glass-effect">
            <img src="<?= BASE_URL ?>/public/assets/images/Logo_colegio.webp" alt="Logo Colegio San Francisco de Asís" class="school-logo">

            <div class="login-header">
                <h2>Bienvenido</h2>
                <p>Sistema de Gestión de Tareas Escolares</p>
            </div>

            <form id="loginForm" class="login-form">
                <div class="form-group">
                    <i class="fas fa-user form-icon"></i>
                    <input type="email" class="form-control" name="email" placeholder="Correo electrónico" required>
                </div>

                <div class="form-group">
                    <i class="fas fa-lock form-icon"></i>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña" required>
                    <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                </div>

                <button type="submit" class="btn btn-login">
                    Iniciar Sesión
                </button>

                <div class="forgot-password">
                    <a href="<?= BASE_URL ?>/app/views/auth/reset.php">¿Olvidaste tu contraseña?</a>
                </div>
            </form>
        </div>
    </div>

    <script src="<?= BASE_URL ?>/public/assets/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= BASE_URL ?>/public/assets/js/login.js"></script>
</body>

</html>