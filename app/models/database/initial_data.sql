USE gestion_tareas_escolares;

-- Insertar roles solo si no existen
INSERT IGNORE INTO roles (nombre) VALUES 
('administrador'),
('profesor'),
('estudiante');

-- Ya no insertamos estados_tarea aquí porque se insertaron en schema.sql

-- Insertar usuarios con contraseñas sin hashear
-- Administradores
INSERT INTO usuarios (username, password, email, nombre, apellidos, rol_id, activo) VALUES
('admin', 'admin123', 'admin@escuela.edu', 'Administrador', 'Principal', 1, 1);

INSERT INTO usuarios (username, password, email, nombre, apellidos, rol_id, activo) VALUES
('admin2', 'admin456', 'admin2@escuela.edu', 'Administrador', 'Secundario', 1, 1);

-- Profesores
INSERT INTO usuarios (username, password, email, nombre, apellidos, rol_id, activo) VALUES
('profesor1', 'prof123', 'profesor1@escuela.edu', 'Juan', 'Pérez', 2, 1);

INSERT INTO usuarios (username, password, email, nombre, apellidos, rol_id, activo) VALUES
('profesor2', 'prof456', 'profesor2@escuela.edu', 'María', 'González', 2, 1);

INSERT INTO usuarios (username, password, email, nombre, apellidos, rol_id, activo) VALUES
('profesor3', 'prof789', 'profesor3@escuela.edu', 'Carlos', 'Rodríguez', 2, 1);

-- Estudiantes
INSERT INTO usuarios (username, password, email, nombre, apellidos, rol_id, activo) VALUES
('estudiante1', 'est123', 'estudiante1@escuela.edu', 'Ana', 'Martínez', 3, 1);

INSERT INTO usuarios (username, password, email, nombre, apellidos, rol_id, activo) VALUES
('estudiante2', 'est456', 'estudiante2@escuela.edu', 'Pedro', 'López', 3, 1);

INSERT INTO usuarios (username, password, email, nombre, apellidos, rol_id, activo) VALUES
('estudiante3', 'est789', 'estudiante3@escuela.edu', 'Laura', 'Sánchez', 3, 1);

INSERT INTO usuarios (username, password, email, nombre, apellidos, rol_id, activo) VALUES
('estudiante4', 'est101', 'estudiante4@escuela.edu', 'Miguel', 'Torres', 3, 1);

INSERT INTO usuarios (username, password, email, nombre, apellidos, rol_id, activo) VALUES
('estudiante5', 'est102', 'estudiante5@escuela.edu', 'Carmen', 'Díaz', 3, 1);
