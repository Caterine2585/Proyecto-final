<?php
// Ajusta estos datos para tu servidor:
$conexion = new mysqli("localhost", "root", "", "fonrio"); 
if ($conexion->connect_error) {
  die("Error en la conexión: " . $conexion->connect_error);
}

// Inicializar variables
$id = $nombre = $correo = $telefono = $estado = "";
$editar = false;

// Insertar o Actualizar proveedor
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id = $_POST["ID_Proveedor"];
  $nombre = $_POST["Nombre_Proveedor"];
  $correo = $_POST["Correo_Electronico"];
  $telefono = $_POST["Telefono"];
  $estado = $_POST["ID_Estado"];
  $editar = isset($_POST["editar"]) && $_POST["editar"] === "1";

  if ($editar) {
    $sql = "UPDATE Proveedores SET Nombre_Proveedor=?, Correo_Electronico=?, Telefono=?, ID_Estado=? WHERE ID_Proveedor=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssss", $nombre, $correo, $telefono, $estado, $id);
  } else {
    $sql = "INSERT INTO Proveedores (ID_Proveedor, Nombre_Proveedor, Correo_Electronico, Telefono, ID_Estado) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssss", $id, $nombre, $correo, $telefono, $estado);
  }
  $stmt->execute();
  $stmt->close();
  header("Location: proveedores.php");
  exit();
}

// Cargar datos para editar
if (isset($_GET["editar"])) {
  $editar = true;
  $id = $_GET["editar"];
  $sql = "SELECT * FROM Proveedores WHERE ID_Proveedor=?";
  $stmt = $conexion->prepare($sql);
  $stmt->bind_param("s", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows == 1) {
    $fila = $result->fetch_assoc();
    $nombre = $fila["Nombre_Proveedor"];
    $correo = $fila["Correo_Electronico"];
    $telefono = $fila["Telefono"];
    $estado = $fila["ID_Estado"];
  }
  $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Proveedores</title>
  <link rel="stylesheet" href="../css/bootstrap.min.css" />
  <link rel="stylesheet" href="../css/proveedores.css" />

</head>
<body>
    <div class="cabecera  text-white bg-primary text-center p-4">
  <a href="../html/Inicio.html">
    <img src="../Imagenes/Logo.webp" alt="Logo esquina" class="logo" />
  </a>
  <h1 class="titulo ">Registro de Proveedores</h1>
</div>


  <div class="container mt-4">
    <table class="table table-bordered table-hover table-striped">
      <thead class="table-dark text-center">
        <tr>
          <th>ID_Proveedor</th>
          <th>Nombre_Proveedor</th>
          <th>Correo_Electronico</th>
          <th>Teléfono</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $consulta = $conexion->query("SELECT * FROM Proveedores");
        while ($prov = $consulta->fetch_assoc()) {
          echo "<tr>
            <td>{$prov['ID_Proveedor']}</td>
            <td>{$prov['Nombre_Proveedor']}</td>
            <td>{$prov['Correo_Electronico']}</td>
            <td>{$prov['Telefono']}</td>
            <td>{$prov['ID_Estado']}</td>
            <td class='text-center'>
              <a href='?editar={$prov['ID_Proveedor']}' class='btn btn-warning btn-sm me-1' title='Editar'>
                <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil-square' viewBox='0 0 16 16'>
                  <path d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z'/>
                  <path fill-rule='evenodd' d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z'/>
                </svg>
                Editar
              </a>
            </td>
          </tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <div class="container mt-5">
    <h2 class="text-center"><?= $editar ? 'Editar Proveedor' : 'Añadir Proveedor' ?></h2>
    <form method="POST" class="row g-3">
      <input type="hidden" name="editar" value="<?= $editar ? '1' : '0' ?>">
      <div class="col-md-6">
        <label class="form-label">ID_Proveedor</label>
        <input type="text" name="ID_Proveedor" class="form-control" value="<?= htmlspecialchars($id) ?>" required <?= $editar ? 'readonly' : '' ?>>
      </div>
      <div class="col-md-6">
        <label class="form-label">Nombre_Proveedor</label>
        <input type="text" name="Nombre_Proveedor" class="form-control" value="<?= htmlspecialchars($nombre) ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Correo_Electronico</label>
        <input type="email" name="Correo_Electronico" class="form-control" value="<?= htmlspecialchars($correo) ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Teléfono</label>
        <input type="text" name="Telefono" class="form-control" value="<?= htmlspecialchars($telefono) ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Estado</label>
        <select name="ID_Estado" class="form-select" required>
          <option value="EST001" <?= $estado == 'EST001' ? 'selected' : '' ?>>Activo</option>
          <option value="EST002" <?= $estado == 'EST002' ? 'selected' : '' ?>>Inactivo</option>
          <option value="EST003" <?= $estado == 'EST003' ? 'selected' : '' ?>>En Proceso</option>
        </select>
      </div>
      <div class="col-12 text-center mt-3">
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="proveedores.php" class="btn btn-secondary">Cancelar</a>
      </div>
    </form>
  </div>

  <footer class="footer mt-5">
    <p>Copyright © 2025 Fonrio</p>
  </footer>
</body>
</html>