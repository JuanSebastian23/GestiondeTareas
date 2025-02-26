<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
require_once(CONTROLLERS_PATH . '/NotificationController.php');
require_once(CONTROLLERS_PATH . '/AuthController.php');

$auth = new AuthController();
$notificationController = new NotificationController();

// Verificar sesión activa
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/app/views/auth/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$notificaciones = $notificationController->obtenerNotificaciones($user_id);
?>

<h1 class="position-relative header-page">Notificaciones</h1>
<div class="dashboard-page">
    <div class="wrapper">
        <div class="latest mega" data-aos="fade-up">
            <h2 class="section-header">Notificaciones Recientes</h2>
            <div class="data" id="notificaciones-container">
                <?php if (empty($notificaciones)): ?>
                    <p>No tienes nuevas notificaciones.</p>
                <?php else: ?>
                    <?php foreach ($notificaciones as $notif): ?>
                        <?php 
                            $colorIcono = $notif['leida'] == 0 ? "c-orange" : "c-blue";
                            $icono = $notif['leida'] == 0 ? "fa-bell" : "fa-check-circle";
                        ?>
                        <div class="d-flex align-items-center item">
                            <i class="fa-solid <?= $icono ?> fa-2x <?= $colorIcono ?> me-3"></i>
                            <div class="info">
                                <h3><?= htmlspecialchars($notif['titulo']) ?></h3>
                                <p><?= htmlspecialchars($notif['mensaje']) ?> - <?= date("d M Y H:i", strtotime($notif['created_at'])) ?></p>
                            </div>
                            <button class="btn btn-sm btn-primary" 
                                        onclick="marcarComoLeida(<?= $notif['id'] ?>); redirigirPagina();">
                                        <?= $notif['leida'] == 0 ? "Marcar como leída" : "Ver" ?>
                                    </button>
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    function marcarComoLeida(id) {
        fetch(`<?= BASE_URL ?>/app/views/student/notificacion_leida.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error("Error al marcar como leída:", error));
    }
</script>

<script>
function redirigirPagina() {
    window.location.href = "<?= BASE_URL ?>?page=task_visualization&role=student";
}
</script>