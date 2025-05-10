<?php
// Conexión
$mysqli = new mysqli("localhost", "root", "", "propdb");
if ($mysqli->connect_error) die("Error BD: ".$mysqli->connect_error);

// Procesar eliminación
if (isset($_GET['id'])) {
  $id = (int) $_GET['id'];
  $mysqli->query("DELETE FROM cuestionarios WHERE id=$id");
}

header("Location: cuestionarios.php");
exit;
