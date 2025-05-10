<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "propdb");
if ($mysqli->connect_error) die("Error de conexión: " . $mysqli->connect_error);

if (isset($_GET['id'])) {
  $id = (int)$_GET['id'];
  $mysqli->query("DELETE FROM preguntas WHERE id = $id");
}

header("Location: preguntas.php");
exit;
