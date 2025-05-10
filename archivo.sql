-- Tabla de usuarios (opcional si ya la tienes)
CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  correo VARCHAR(100) UNIQUE NOT NULL,
  contrasena VARCHAR(255) NOT NULL,
  rol ENUM('admin', 'usuario') DEFAULT 'usuario'
);

-- Tabla de cuestionarios
CREATE TABLE cuestionarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(255) NOT NULL,
  descripcion TEXT,
  fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
  creado_por INT,
  fecha_edicion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  editado_por INT,
  FOREIGN KEY (creado_por) REFERENCES usuarios(id),
  FOREIGN KEY (editado_por) REFERENCES usuarios(id)
);

-- Tabla de preguntas solo para respuestas de tipo texto
CREATE TABLE preguntas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_cuestionario INT NOT NULL,
  texto TEXT NOT NULL,  -- La pregunta en formato texto
  fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
  creado_por INT,
  fecha_edicion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  editado_por INT,
  FOREIGN KEY (id_cuestionario) REFERENCES cuestionarios(id) ON DELETE CASCADE,
  FOREIGN KEY (creado_por) REFERENCES usuarios(id),
  FOREIGN KEY (editado_por) REFERENCES usuarios(id)
);