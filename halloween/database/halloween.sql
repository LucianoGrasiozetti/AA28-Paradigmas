-- PASO 1: Configuración de la base de datos "halloween" (tablas disfraces, usuarios y votos)
CREATE DATABASE IF NOT EXISTS halloween CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE halloween;
CREATE TABLE disfraces (
  id int(11) NOT NULL AUTO_INCREMENT,
  nombre varchar(50) NOT NULL,
  descripcion text NOT NULL,
  votos int(11) NOT NULL DEFAULT 0,
  foto varchar(20) NOT NULL,
  foto_blob mediumblob NOT NULL,
  eliminado int(11) NOT NULL DEFAULT 0,
  id_usuario int(11) DEFAULT NULL, -- quién subió el disfraz (paso 3b)
  PRIMARY KEY (id),
  KEY idx_dueno (id_usuario)
) ENGINE=InnoDB;
CREATE TABLE usuarios (
  id int(11) NOT NULL AUTO_INCREMENT,
  nombre varchar(50) NOT NULL,
  clave text NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_nombre (nombre)
) ENGINE=InnoDB;
CREATE TABLE votos (
  id int(11) NOT NULL AUTO_INCREMENT,
  id_usuario int(11) NOT NULL,
  id_disfraz int(11) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_voto (id_usuario, id_disfraz),
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
  FOREIGN KEY (id_disfraz) REFERENCES disfraces(id)
) ENGINE=InnoDB;
-- Administrador: usuario "admin" / clave "Admin123!"
INSERT INTO usuarios (nombre, clave) VALUES ('admin', '$2y$10$4n/A4TX8io2eXRVVEHk4GeHnhVVMNBiXm6UC7obMKz2wrz/HbDtQu');
