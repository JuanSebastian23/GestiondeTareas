<?php
if (!defined('ROOT_PATH')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
}
?>

<h1 class="position-relative header-page">Recursos Educativos</h1>
<div class="dashboard-page">
    <div class="wrapper">
        <!-- Recursos destacados -->
        <div class="latest mega" data-aos="fade-right" data-aos-delay="250">
            <h2 class="section-header">Recursos Destacados</h2>
            <div class="data">
                <div class="d-flex align-items-center item">
                    <img src="<?= BASE_URL ?>/public/assets/images/pdf.svg" alt="">
                    <div class="info">
                        <h3>Guía de Matemáticas</h3>
                        <p>Prof. García</p>
                    </div>
                    <div class="time">PDF - 2.9mb</div>
                </div>
                <div class="d-flex align-items-center item">
                    <img src="<?= BASE_URL ?>/public/assets/images/avi.svg" alt="">
                    <div class="info">
                        <h3>Tutorial de Química</h3>
                        <p>Lab Virtual</p>
                    </div>
                    <div class="time">Video - 15min</div>
                </div>
                <!-- Más recursos... -->
            </div>
        </div>

        <!-- Biblioteca Digital -->
        <div class="news mega" data-aos="fade-up" data-aos-delay="300">
            <h2 class="section-header">Biblioteca Digital</h2>
            <div class="data">
                <div class="row">
                    <div class="col-lg-12 d-flex align-items-center item">
                        <img src="<?= BASE_URL ?>/public/assets/images/news-01.png" alt="">
                        <div class="info">
                            <h3>Libros de Texto</h3>
                            <p>Colección completa del curso</p>
                        </div>
                        <button class="btn btn-primary">Acceder</button>
                    </div>
                    <!-- Más recursos... -->
                </div>
            </div>
        </div>
    </div>
</div>
