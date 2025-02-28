document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".btn-ver-tarea").forEach(button => {
        button.addEventListener("click", function () {
            let tareaId = this.getAttribute("data-id");
            document.getElementById("tarea_id").value = tareaId;
        });
    });

    document.getElementById("formSubirTarea").addEventListener("submit", function (event) {
        event.preventDefault();
        
        let formData = new FormData(this);
        
        fetch("http://localhost/GestiondeTareas/app/controllers/EntregaController.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text()) // 🔥 Cambia .json() a .text() para ver qué responde el servidor
        .then(data => {
            console.log("Respuesta del servidor:", data); // 🔍 Muestra la respuesta
            let jsonData;
            try {
                jsonData = JSON.parse(data); // Intenta parsear el JSON
            } catch (error) {
                console.error("Error al convertir a JSON:", error);
                return;
            }
            if (jsonData.status === "success") {
                alert("Tarea entregada correctamente");
                location.reload();
            } else {
                alert("Error: " + jsonData.message);
            }
        })
        .catch(error => console.error("Error en la petición:", error));
    });
});        