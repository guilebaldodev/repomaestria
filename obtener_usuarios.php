<?php
$conexion = new mysqli("localhost", "root", "", "propdb");
$resultado = $conexion->query("SELECT * FROM usuarios");
$usuarios = [];

while ($fila = $resultado->fetch_assoc()) {
    $usuarios[] = $fila;
}

echo json_encode($usuarios);