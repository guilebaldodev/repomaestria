<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "propdb");
if ($mysqli->connect_error) die("Error de conexión: " . $mysqli->connect_error);

// Crear pregunta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'crear') {
  $id_cuestionario = (int)$_POST['id_cuestionario'];
  $texto = $mysqli->real_escape_string($_POST['texto']);
  $creado_por = $_SESSION['usuario_id'] ?? 1; // Valor por defecto
  $fecha_creacion = date('Y-m-d H:i:s');

  $query = "INSERT INTO preguntas (id_cuestionario, texto, fecha_creacion, creado_por, fecha_edicion, editado_por)
            VALUES ($id_cuestionario, '$texto', '$fecha_creacion', $creado_por, NULL, NULL)";

  if (!$mysqli->query($query)) {
    die("Error al insertar: " . $mysqli->error);
  }

  header("Location: preguntas.php");
  exit;
}

// Obtener preguntas con nombre del cuestionario
$res = $mysqli->query("SELECT p.id, p.texto, c.nombre AS cuestionario 
                       FROM preguntas p
                       JOIN cuestionarios c ON p.id_cuestionario = c.id
                       ORDER BY p.id DESC");

// Obtener cuestionarios para el formulario
$cuestionarios = $mysqli->query("SELECT id, nombre FROM cuestionarios ORDER BY nombre ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Administrador de Preguntas</title>
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
      <nav class="navbar navbar-expand-lg navbar-light text-white bg-primary rounded mt-3 mb-4 px-3 shadow-sm">
        <div class="container-fluid">
          <span class="navbar-text me-auto text-white">
            👋 Bienvenido, <?= $_SESSION['usuario_nombre'] ?? 'Invitado' ?> | Administrador
          </span>
        </div>
      </nav>

      <h2 class="mb-4">Administrador de Preguntas</h2>

      <!-- Formulario Crear Pregunta -->
      <div class="card mb-4">
        <div class="card-header">Agregar Pregunta</div>
        <div class="card-body">
          <form method="POST" action="preguntas.php">
            <input type="hidden" name="action" value="crear">
            <div class="mb-3">
              <label for="id_cuestionario" class="form-label">Cuestionario</label>
              <select class="form-select" name="id_cuestionario" id="id_cuestionario" required>
                <option value="" disabled selected>Selecciona un cuestionario</option>
                <?php while($c = $cuestionarios->fetch_assoc()): ?>
                  <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="texto" class="form-label">Texto de la Pregunta</label>
              <textarea class="form-control" name="texto" id="texto" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Pregunta</button>
          </form>
        </div>
      </div>

      <!-- Lista de Preguntas -->
      <div class="card">
        <div class="card-header">Lista de Preguntas</div>
        <div class="card-body">
          <table class="table table-bordered table-hover">
            <thead class="table-dark">
              <tr>
                <th>Pregunta</th>
                <th>Cuestionario</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php while($row = $res->fetch_assoc()): ?>
              <tr data-id="<?= $row['id'] ?>"
                  data-texto="<?= htmlspecialchars($row['texto'], ENT_QUOTES) ?>">
                <td><?= htmlspecialchars($row['texto']) ?></td>
                <td><?= htmlspecialchars($row['cuestionario']) ?></td>
                <td>
                  <button class="btn btn-sm btn-warning btn-editar" data-bs-toggle="modal" data-bs-target="#modalEditar">
                    ✏️ Editar
                  </button>
                  <a href="eliminar_pregunta.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                     onclick="return confirm('¿Eliminar esta pregunta?')">
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
      <form id="formEditar" method="POST" action="editar_pregunta.php">
        <input type="hidden" name="id" id="editId">
        <div class="modal-header">
          <h5 class="modal-title" id="modalEditarLabel">Editar Pregunta</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="editTexto" class="form-label">Texto de la Pregunta</label>
            <textarea class="form-control" name="texto" id="editTexto" rows="3" required></textarea>
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
document.querySelectorAll('.btn-editar').forEach(btn => {
  btn.addEventListener('click', e => {
    const tr = e.target.closest('tr');
    document.getElementById('editId').value = tr.dataset.id;
    document.getElementById('editTexto').value = tr.dataset.texto;
  });
});
</script>
</body>
</html>

