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
<script>
    function cargarNotificaciones() {
        fetch('http://localhost/GestiondeTareas/app/views/student/notificaciones.php')
            .then(response => response.json())
            .then(data => {
                let container = document.getElementById("notificaciones-container");
                container.innerHTML = ""; // Limpiar contenido previo

                if (data.length === 0) {
                    container.innerHTML = "<p>No tienes nuevas notificaciones.</p>";
                    return;
                }

                data.forEach(notif => {
                    let colorIcono = notif.leida === "0" ? "c-orange" : "c-blue";
                    let icono = notif.leida === "0" ? "fa-bell" : "fa-check-circle";
                    
                    let notifHTML = `
                        <div class="d-flex align-items-center item">
                            <i class="fa-solid ${icono} fa-2x ${colorIcono} me-3"></i>
                            <div class="info">
                                <h3>${notif.titulo}</h3>
                                <p>${notif.mensaje} - ${formatearFecha(notif.created_at)}</p>
                            </div>
                            <button class="btn btn-sm btn-primary" onclick="marcarComoLeida(${notif.id})">
                                ${notif.leida === "0" ? "Marcar como leída" : "Ver"}
                            </button>
                        </div>
                    `;
                    container.innerHTML += notifHTML;
                });
            })
            .catch(error => console.error("Error al obtener notificaciones:", error));
    }

    function marcarComoLeida(id) {
        fetch(`http://localhost/GestiondeTareas/app/views/student/marcar_leida.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cargarNotificaciones(); // Recargar la lista después de marcar como leída
                }
            })
            .catch(error => console.error("Error al marcar como leída:", error));
    }

    function formatearFecha(fecha) {
        let date = new Date(fecha);
        return date.toLocaleString("es-ES", { day: "numeric", month: "short", year: "numeric", hour: "2-digit", minute: "2-digit" });
    }

    // Cargar notificaciones al inicio
    cargarNotificaciones();
</script>
