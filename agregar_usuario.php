<?php
$conexion = new mysqli("localhost", "root", "", "propdb");

$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

$stmt = $conexion->prepare("INSERT INTO usuarios (nombre, correo, contrasena) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nombre, $correo, $contrasena);

if ($stmt->execute()) {
    echo "Usuario agregado correctamente.";
} else {
    echo "Error al agregar usuario: " . $conexion->error;
}