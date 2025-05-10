<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "propdb");
if ($mysqli->connect_error) die("Error de conexión: " . $mysqli->connect_error);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = (int)$_POST['id'];
  $texto = $mysqli->real_escape_string($_POST['texto']);
  $editado_por = $_SESSION['usuario_id'] ?? null;

  $query = "UPDATE preguntas 
            SET texto = '$texto', editado_por = " . ($editado_por ?? 'NULL') . " 
            WHERE id = $id";
  $mysqli->query($query);
}

header("Location: preguntas.php");
exit;

