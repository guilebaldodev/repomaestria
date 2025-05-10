<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "propdb");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Obtener datos del formulario
$id = $_POST['id'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$correo = $_POST['correo'] ?? '';

// Validar que los datos no estén vacíos
if ($id && $nombre && $correo) {
    $stmt = $conexion->prepare("UPDATE usuarios SET nombre = ?, correo = ? WHERE id = ?");
    $stmt->bind_param("ssi", $nombre, $correo, $id);

    if ($stmt->execute()) {
        echo "Usuario actualizado correctamente";
    } else {
        echo "Error al actualizar usuario";
    }

    $stmt->close();
} else {
    echo "Faltan datos";
}

$conexion->close();
?>
