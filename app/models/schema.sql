-- Creación de la base de datos
CREATE DATABASE IF NOT EXISTS gestion_tareas_escolares
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE gestion_tareas_escolares;

-- Tabla de roles
CREATE TABLE roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    rol_id INT NOT NULL,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (rol_id) REFERENCES roles(id)
);

-- Tabla de grupos
CREATE TABLE grupos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Modificación de la tabla profesor_grupo
CREATE TABLE IF NOT EXISTS profesor_grupo (
    profesor_id INT,
    grupo_id INT,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (profesor_id, grupo_id),
    FOREIGN KEY (profesor_id) REFERENCES usuarios(id),
    FOREIGN KEY (grupo_id) REFERENCES grupos(id)
);

-- Tabla de materias
CREATE TABLE materias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de relación grupo-materia
CREATE TABLE grupo_materia (
    grupo_id INT,
    materia_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (grupo_id, materia_id),
    FOREIGN KEY (grupo_id) REFERENCES grupos(id),
    FOREIGN KEY (materia_id) REFERENCES materias(id)
);

-- Tabla de relación estudiante-grupo
CREATE TABLE estudiante_grupo (
    estudiante_id INT,
    grupo_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (estudiante_id, grupo_id),
    FOREIGN KEY (estudiante_id) REFERENCES usuarios(id),
    FOREIGN KEY (grupo_id) REFERENCES grupos(id)
);

-- Tabla de relación profesor-materia
CREATE TABLE profesor_materia (
    profesor_id INT,
    materia_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (profesor_id, materia_id),
    FOREIGN KEY (profesor_id) REFERENCES usuarios(id),
    FOREIGN KEY (materia_id) REFERENCES materias(id)
);

-- Tabla de estados de tareas
CREATE TABLE estados_tarea (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de tareas
CREATE TABLE tareas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_entrega DATETIME NOT NULL,
    materia_id INT NOT NULL,
    grupo_id INT NOT NULL,
    profesor_id INT NOT NULL,
    estado_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (materia_id) REFERENCES materias(id),
    FOREIGN KEY (grupo_id) REFERENCES grupos(id),
    FOREIGN KEY (profesor_id) REFERENCES usuarios(id),
    FOREIGN KEY (estado_id) REFERENCES estados_tarea(id)
);

-- Tabla de entregas de tareas
CREATE TABLE entregas_tarea (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tarea_id INT NOT NULL,
    estudiante_id INT NOT NULL,
    estado_id INT NOT NULL,
    fecha_entrega TIMESTAMP,
    comentarios TEXT,
    archivo_adjunto VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tarea_id) REFERENCES tareas(id),
    FOREIGN KEY (estudiante_id) REFERENCES usuarios(id),
    FOREIGN KEY (estado_id) REFERENCES estados_tarea(id)
);

-- Tabla de notificaciones
CREATE TABLE notificaciones (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    mensaje TEXT NOT NULL,
    leida BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Índices para optimizar consultas
CREATE INDEX idx_tareas_fecha_entrega ON tareas(fecha_entrega);
CREATE INDEX idx_tareas_estado ON tareas(estado_id);
CREATE INDEX idx_entregas_tarea_estado ON entregas_tarea(estado_id);
CREATE INDEX idx_notificaciones_usuario ON notificaciones(usuario_id, leida);
CREATE INDEX idx_materias_nombre ON materias(nombre);
CREATE INDEX idx_grupo_materia_materia ON grupo_materia(materia_id);
CREATE INDEX idx_profesor_materia_materia ON profesor_materia(materia_id);

-- Trigger para crear notificaciones automáticas
DELIMITER //
CREATE TRIGGER trig_notificar_tarea_proxima
AFTER INSERT ON tareas
FOR EACH ROW
BEGIN
    INSERT INTO notificaciones (usuario_id, titulo, mensaje)
    SELECT 
        eg.estudiante_id,
        CONCAT('Nueva tarea: ', NEW.titulo),
        CONCAT('Se ha asignado una nueva tarea para la materia ', 
               (SELECT nombre FROM materias WHERE id = NEW.materia_id),
               '. Fecha de entrega: ', DATE_FORMAT(NEW.fecha_entrega, '%d/%m/%Y'))
    FROM estudiante_grupo eg
    WHERE eg.grupo_id = NEW.grupo_id;
END//
DELIMITER ;
