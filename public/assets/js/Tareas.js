// Script para actualizar contadores -->
   function actualizarContadorTareas() {
    fetch('http://localhost/GestiondeTareas/app/views/student/contar_tareas.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error(data.error);
                return;
            }

            // Actualizar los contadores en la página
            document.querySelector(".border-warning .card-text span").textContent = data["En Progreso"] || 0;
            document.querySelector(".border-success .card-text span").textContent = data.Completada || 0;
            document.querySelector(".border-danger .card-text span").textContent = data.Vencida || 0;
            document.querySelector(".border-primary .card-text span").textContent = data.Calificada || 0;
            // Asegúrate de que tienes una clase para "Pendiente"

        })
        .catch(error => console.error("Error al obtener tareas:", error));
}

// Ejecutar la función cada 5 segundos para actualizar en tiempo real
document.addEventListener('DOMContentLoaded', () => {
    actualizarContadorTareas();
    setInterval(actualizarContadorTareas, 5000); // Actualiza cada 5 segundos
});



//Script para cargar tareas con AJAX -->

    function cargarTareas(filtrar = false) {
        let materia = document.getElementById("filter-materia").value;
        let estado = document.getElementById("filter-estado").value;

        let url = filtrar 
            ? `http://localhost/GestiondeTareas/app/views/student/filtrar_tareas.php?materia=${materia}&estado=${estado}`
            : `http://localhost/GestiondeTareas/app/views/student/listar_tareas.php`;

        fetch(url)
            .then(response => response.text())
            .then(data => {
                document.getElementById('tareas-list').innerHTML = data;
                actualizarContadorTareas(); // Actualizar contadores después de cargar tareas
            })
            .catch(error => console.error('Error cargando tareas:', error));
    }

    document.addEventListener('DOMContentLoaded', function () {
        cargarTareas();

        document.getElementById("filter-materia").addEventListener("change", function() {
            cargarTareas(true);
        });

        document.getElementById("filter-estado").addEventListener("change", function() {
            cargarTareas(true);
        });
    });
