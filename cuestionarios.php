<?php
session_start();
// Aquí podrías validar que el usuario esté autenticado
// if (!isset($_SESSION['usuario_id'])) header('Location: login.php');

// Conexión
$mysqli = new mysqli("localhost", "root", "", "propdb");
if ($mysqli->connect_error) die("Error BD: " . $mysqli->connect_error);

// 👉 Proceso de inserción
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action']==='crear') {
    $nombre      = $mysqli->real_escape_string($_POST['nombre']);
    $descripcion = $mysqli->real_escape_string($_POST['descripcion']);
    $mysqli->query("INSERT INTO cuestionarios (nombre, descripcion) VALUES ('$nombre','$descripcion')");
    header("Location: cuestionarios.php");
    exit;
}

// Obtener todos
$res = $mysqli->query("SELECT * FROM cuestionarios ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Administrador de Cuestionarios</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <nav class="col-md-2 d-none d-md-block bg-dark sidebar text-white vh-100 p-3">
      <h4 class="text-light">Sistema</h4>
      <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link text-white" href="cuestionarios.php">📝 Cuestionarios</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="preguntas.php">❓ Preguntas</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="login.php">🚪 Cerrar sesión</a></li>
      </ul>
    </nav>

    <!-- Contenido -->
<main class="col-md-10 ms-sm-auto px-4">
  <!-- Barra superior -->
<nav class="navbar navbar-expand-lg navbar-light text-white bg-primary rounded mt-3 mb-4 px-3 shadow-sm">
  <div class="container-fluid">
    <span class="navbar-text me-auto text-white">
      👋 Bienvenido, <?= $_SESSION['usuario_nombre'] ?? 'Invitado' ?> | Administrador
    </span>
  </div>
</nav>

    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h2>Administrador de Cuestionarios</h2>
      </div>

      <!-- Crear -->
      <div class="card mb-4">
        <div class="card-header">Agregar Cuestionario</div>
        <div class="card-body">
          <form method="POST" action="cuestionarios.php">
            <input type="hidden" name="action" value="crear">
            <div class="mb-3">
              <label for="nombre" class="form-label">Título</label>
              <input type="text" class="form-control" name="nombre" id="nombre" required>
            </div>
            <div class="mb-3">
              <label for="descripcion" class="form-label">Descripción</label>
              <textarea class="form-control" name="descripcion" id="descripcion" rows="2" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cuestionario</button>
          </form>
        </div>
      </div>

      <!-- Listado -->
      <div class="card">
        <div class="card-header">Lista de Cuestionarios</div>
        <div class="card-body">
          <table class="table table-bordered table-hover">
            <thead class="table-dark">
              <tr>
                <th>Título</th>
                <th>Descripción</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php while($row = $res->fetch_assoc()): ?>
              <tr data-id="<?= $row['id'] ?>"
                  data-nombre="<?= htmlspecialchars($row['nombre'], ENT_QUOTES) ?>"
                  data-descripcion="<?= htmlspecialchars($row['descripcion'], ENT_QUOTES) ?>">
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td><?= htmlspecialchars($row['descripcion']) ?></td>
                <td>
                  <button class="btn btn-sm btn-warning btn-editar" data-bs-toggle="modal" data-bs-target="#modalEditar">
                    ✏️ Editar
                  </button>
                  <a href="eliminar_cuestionario.php?id=<?= $row['id'] ?>"
                     class="btn btn-sm btn-danger"
                     onclick="return confirm('¿Eliminar cuestionario?')">
                    🗑️ Eliminar
                  </a>
                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</div>

<!-- Modal Editar -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formEditar" method="POST" action="editar_cuestionario.php">
        <input type="hidden" name="id" id="editId">
        <div class="modal-header">
          <h5 class="modal-title" id="modalEditarLabel">Editar Cuestionario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="editNombre" class="form-label">Título</label>
            <input type="text" class="form-control" name="nombre" id="editNombre" required>
          </div>
          <div class="mb-3">
            <label for="editDescripcion" class="form-label">Descripción</label>
            <textarea class="form-control" name="descripcion" id="editDescripcion" rows="2" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Rellenar modal al hacer clic en "Editar"
document.querySelectorAll('.btn-editar').forEach(btn => {
  btn.addEventListener('click', e => {
    const tr = e.target.closest('tr');
    document.getElementById('editId').value          = tr.dataset.id;
    document.getElementById('editNombre').value      = tr.dataset.nombre;
    document.getElementById('editDescripcion').value = tr.dataset.descripcion;
  });
});
</script>
</body>
</html>
