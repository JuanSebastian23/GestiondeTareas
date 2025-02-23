<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Colegio San Francisco de Asís</title>
    <link rel="icon" href="<?= BASE_URL ?>/public/assets/images/favicon.webp">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="glass-effect">
            <img src="<?= BASE_URL ?>/public/assets/images/Logo_colegio.webp" alt="Logo Colegio San Francisco de Asís" class="school-logo">
            
            <div class="login-header">
                <h2>Recuperar Contraseña</h2>
                <p>Ingresa tu correo electrónico y te enviaremos las instrucciones para restablecer tu contraseña.</p>
            </div>

            <form class="login-form">
                <div class="form-group">
                    <i class="fas fa-envelope form-icon"></i>
                    <input type="email" class="form-control" placeholder="Correo electrónico" required>
                </div>

                <button type="submit" class="btn btn-login">
                    Enviar Instrucciones
                </button>

                <div class="forgot-password">
                    <a href="<?= BASE_URL ?>/app/views/auth/login.php">
                        <i class="fas fa-arrow-left"></i> Volver al inicio de sesión
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="<?= BASE_URL ?>/public/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
