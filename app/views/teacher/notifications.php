<?php
if (!defined('ROOT_PATH')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
}
?>

<h1 class="position-relative header-page">Notificaciones</h1>
<div class="dashboard-page">
    <div class="wrapper">
        <div class="latest mega" data-aos="fade-up">
            <h2 class="section-header">Notificaciones Recientes</h2>
            <div class="data">
                <div class="d-flex align-items-center item">
                    <i class="fa-solid fa-file-alt fa-2x c-blue me-3"></i>
                    <div class="info">
                        <h3>Nueva entrega: Proyecto Final</h3>
                        <p>Juan Pérez - 1°A - Hace 5 minutos</p>
                    </div>
                    <button class="btn btn-sm btn-primary">Ver</button>
                </div>
                <div class="d-flex align-items-center item">
                    <i class="fa-solid fa-question-circle fa-2x c-orange me-3"></i>
                    <div class="info">
                        <h3>Consulta pendiente</h3>
                        <p>María García - 2°B - Hace 1 hora</p>
                    </div>
                    <button class="btn btn-sm btn-primary">Responder</button>
                </div>
            </div>
        </div>
    </div>
</div>
