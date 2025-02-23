/** @format */
document.addEventListener('DOMContentLoaded', function() {
    // Yearly Target Progress Bar
    const skills = document.querySelector(".yearly-target .prog");
    if (skills) {
        const allSkills = document.querySelectorAll(".prog .details .progress span");
        
        window.addEventListener("scroll", () => {
            if (window.scrollY >= skills.offsetTop) {
                allSkills.forEach((skill) => {
                    skill.style.width = skill.dataset.prog;
                });
            } else {
                allSkills.forEach((skill) => {
                    skill.style.width = "0";
                });
            }
        });
    }

    // Statistics Counter
    const stats = document.getElementById("statistics");
    if (stats) {
        let started = false;
        const numbers = document.querySelectorAll(".statistics .row .box span");
        
        window.addEventListener("scroll", () => {
            if (window.scrollY >= stats.offsetTop - 150) {
                if (!started) {
                    numbers.forEach(num => startCount(num));
                }
                started = true;
            }
        });
    }

    // Initialize Bootstrap components
    initializeBootstrapComponents();

    // Manejo de posts dinámicos
    const posts = document.querySelectorAll(".latest-post .data");
    if (posts.length > 0) {
        let currentIndex = 0;

        function showPost(index) {
            posts.forEach(post => {
                post.classList.remove("active");
                post.style.display = "none";
            });
            posts[index].classList.add("active");
            posts[index].style.display = "block";
        }

        function startPostCycle() {
            showPost(currentIndex);
            currentIndex = (currentIndex + 1) % posts.length;
            setTimeout(startPostCycle, 8000);
        }

        startPostCycle();
    }

    // Manejo del modo oscuro
    const darkmodeButton = document.getElementById("darkmode-button");
    if (darkmodeButton) {
        darkmodeButton.addEventListener("click", toggleTheme);
        loadTheme(); // Cargar tema guardado al iniciar
    }

    // Inicializar todos los dropdowns de Bootstrap
    var dropdowns = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'))
    dropdowns.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl)
    });

    // Inicializar tooltips si existen
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});

// Resto de funciones auxiliares
function startCount(el) {
    if (el && el.dataset.goal) {
        const goal = el.dataset.goal;
        let count = setInterval(() => {
            el.textContent++;
            if (el.textContent == goal) {
                clearInterval(count);
            }
        }, 3000 / goal);
    }
}

function initializeBootstrapComponents() {
    // Initialize dropdowns
    const dropdowns = document.querySelectorAll('.dropdown-toggle');
    if (dropdowns.length > 0) {
        dropdowns.forEach(dropdown => {
            new bootstrap.Dropdown(dropdown);
        });
    }

    // Initialize tooltips
    const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    if (tooltips.length > 0) {
        tooltips.forEach(tooltip => {
            new bootstrap.Tooltip(tooltip);
        });
    }
}

// Función para alternar el tema
function toggleTheme() {
    const elements = [
        ".sidebar",
        ".header",
        ".notif-container",
        ".dashboard",
        ".responsive-table",
        "body"
    ];

    elements.forEach(selector => {
        const element = document.querySelector(selector);
        if (element) {
            element.classList.toggle("dark");
        }
    });

    const icon = document.querySelector(".toggle-icon");
    if (icon) {
        if (icon.classList.contains("fa-moon")) {
            icon.classList.replace("fa-moon", "fa-sun");
            localStorage.setItem("theme", "dark");
        } else {
            icon.classList.replace("fa-sun", "fa-moon");
            localStorage.setItem("theme", "light");
        }
    }
}

// Función para cargar el tema guardado
function loadTheme() {
    const savedTheme = localStorage.getItem("theme");
    const elements = [
        ".sidebar",
        ".header",
        ".notif-container",
        ".dashboard",
        ".responsive-table",
        "body"
    ];

    if (savedTheme === "dark") {
        elements.forEach(selector => {
            const element = document.querySelector(selector);
            if (element) {
                element.classList.add("dark");
            }
        });

        const icon = document.querySelector(".toggle-icon");
        if (icon) {
            icon.classList.replace("fa-moon", "fa-sun");
        }
    }
}

// ! Make a PopUp To A Lastest News Photos.
let img = document.querySelectorAll(".news .data .item img");
img.forEach((ele) => {
  ele.addEventListener("click", (e) => {
    let div = document.createElement("div");
    div.className = "popup-overlay";

    let popUp = document.createElement("div");
    popUp.className = "popUp";

    let popUpImg = document.createElement("img");
    popUpImg.src = ele.src;

    div.addEventListener("click", function () {
      popUp.remove();
      div.remove();
    });

    document.body.appendChild(popUp);
    document.body.appendChild(div);
    popUp.appendChild(popUpImg);
  });
});

// ! I Spent Two Hours In This Block Of Code And Doesn't Work ^_^
document.addEventListener("DOMContentLoaded", () => {
  let deleteBtns = document.querySelectorAll(".contain-delete");

  deleteBtns.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      let item = e.target.closest(".item");
      if (item) {
        item.classList.toggle("done");
      }
    });
  });
});

// ! I Spent Two Hours In This Block Of Code And Doesn't Work ^_^
document.addEventListener("DOMContentLoaded", () => {
  // Get the notification bell and container elements
  let bell = document.querySelector(".notifications");
  let notifContainer = document.getElementById("notif-container");

  // Toggle the visibility of the notification container when the bell is clicked
  bell.addEventListener("click", () => {
    // Toggle visibility
    notifContainer.style.display =
      notifContainer.style.display === "block" ? "none" : "block";
  });

  // Get all list items inside the notification container
  let notifItems = notifContainer.querySelectorAll("li");

  // Add click event listener to each notification item
  notifItems.forEach((item) => {
    item.addEventListener("click", () => {
      // Add the "show" class to the clicked item
      item.classList.add("show");
    });
  });
});
