class TaskSorter {
    constructor() {
        this.strategy = null;
    }

    setStrategy(strategy) {
        this.strategy = strategy;
    }

    sortTasks(tasks) {
        return this.strategy.sort(tasks);
    }
}

// Estrategias de ordenamiento
const sortByDate = {
    sort: (tasks) => {
        return [...tasks].sort((a, b) => {
            return new Date(a.dataset.dueDate) - new Date(b.dataset.dueDate);
        });
    }
};

const sortBySubject = {
    sort: (tasks) => {
        return [...tasks].sort((a, b) => {
            return a.dataset.subject.localeCompare(b.dataset.subject);
        });
    }
};

const sortByStatus = {
    sort: (tasks) => {
        const statusPriority = {
            'pendiente': 1,
            'en-progreso': 2,
            'completada': 3
        };
        return [...tasks].sort((a, b) => {
            return statusPriority[a.dataset.status] - statusPriority[b.dataset.status];
        });
    }
};

// Inicialización y manejo de eventos
document.addEventListener('DOMContentLoaded', function() {
    const sorter = new TaskSorter();
    const table = document.getElementById('tasksTable');
    const tbody = table.querySelector('tbody');

    document.querySelectorAll('.sort-btn').forEach(button => {
        button.addEventListener('click', function() {
            const sortType = this.dataset.sort;
            const rows = Array.from(tbody.querySelectorAll('tr'));

            switch(sortType) {
                case 'date':
                    sorter.setStrategy(sortByDate);
                    break;
                case 'subject':
                    sorter.setStrategy(sortBySubject);
                    break;
                case 'status':
                    sorter.setStrategy(sortByStatus);
                    break;
            }

            const sortedRows = sorter.sortTasks(rows);
            tbody.innerHTML = '';
            sortedRows.forEach(row => tbody.appendChild(row));

            // Actualizar botón de ordenamiento
            document.querySelector('.dropdown-toggle').textContent = 
                `Ordenado por: ${this.textContent.trim()}`;
        });
    });
});
