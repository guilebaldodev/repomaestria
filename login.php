<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // Establece la conexión con la base de datos
    $conexion = new mysqli("localhost", "root", "", "propdb");

    // Verifica si la conexión fue exitosa
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    // Consulta para verificar si el usuario existe en la base de datos
    $stmt = $conexion->prepare("SELECT id, correo, contrasena, nombre FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        // Usuario encontrado
        $usuario = $resultado->fetch_assoc();
        
        // Verifica si la contraseña es correcta
        if (password_verify($contrasena, $usuario['contrasena'])) {
            // Iniciar sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['correo'] = $usuario['correo'];
            $_SESSION['usuario_nombre'] = $usuario['nombre']; // Guardamos el nombre del usuario
            header("Location: cuestionarios.php"); // Redirige al panel de control
            exit();
        } else {
            $error = "La contraseña es incorrecta.";
        }
    } else {
        $error = "El correo no está registrado.";
    }

    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilo.css">
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Iniciar sesión</a>
        </div>
    </nav>

    <div class="container">
        <!-- Formulario de inicio de sesión -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Accede a tu cuenta</h5>
            </div>
            <div class="card-body">
                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo</label>
                        <input type="email" class="form-control" id="correo" name="correo" placeholder="Correo electrónico" required>
                    </div>
                    <div class="mb-3">
                        <label for="contrasena" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Contraseña" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Iniciar sesión</button>
                    </div>
                </form>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger mt-3">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                <div class="mt-3">
                    <a href="#" class="text-primary">¿Olvidaste tu contraseña?</a>
                </div>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
