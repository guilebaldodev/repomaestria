<?php
// Conexión
$mysqli = new mysqli("localhost", "root", "", "propdb");
if ($mysqli->connect_error) die("Error BD: ".$mysqli->connect_error);

// Procesar edición
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $id          = (int) $_POST['id'];
  $nombre      = $mysqli->real_escape_string($_POST['nombre']);
  $descripcion = $mysqli->real_escape_string($_POST['descripcion']);
  $mysqli->query("UPDATE cuestionarios 
                  SET nombre='$nombre', descripcion='$descripcion' 
                  WHERE id=$id");
}

header("Location: cuestionarios.php");
exit;
