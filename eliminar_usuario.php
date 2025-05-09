<?php
$conexion = new mysqli("localhost", "root", "", "propdb");
$id = $_GET['id'];
$conexion->query("DELETE FROM usuarios WHERE id = $id");
echo "Usuario eliminado.";