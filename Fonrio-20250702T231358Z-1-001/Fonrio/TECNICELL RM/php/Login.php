<?php
session_start();
include("conexion.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $documento = $_POST['usuario'];
    $clave = $_POST['contrasena'];

    // Consulta usando tus nombres reales
    $sql = "SELECT E.Documento_Empleado, E.Nombre_Usuario, E.ID_Rol, C.contrasena_hash
            FROM Empleados E
            INNER JOIN Contrasenas C ON E.Documento_Empleado = C.Documento_Empleado
            WHERE E.Documento_Empleado = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $documento);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $fila = $resultado->fetch_assoc();

    if ($fila && password_verify($clave, $fila['contrasena_hash'])) {
        // Guardar datos de sesión
        $_SESSION['documento'] = $fila['Documento_Empleado'];
        $_SESSION['nombre'] = $fila['Nombre_Usuario'];
        $_SESSION['rol'] = $fila['ID_Rol'];

        // Redirigir según rol (ajusta los valores según tu tabla de roles)
        if ($fila['ID_Rol'] == "ROL002") { // Por ejemplo, 2 = Admin
            header("Location: Inicio.php");
        } else {
            header("Location: Inicio.php");
        }
        exit();
    } else {
        $mensaje = "Usuario o contraseña incorrecta.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="icon" type="imagen" href="../Imagenes/Logo.webp">
  <title>Iniciar Sesión</title>
  <link href="../css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="../css/IniciarSesion.css">
  <link rel="icon" type="imagen" href="../Imagenes/Logo.webp">
</head>
<body>
  <div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
      <div class="col-md-4">
        <div class="InicioI">
          <div class="text-center mb-4">
            <h1>Bienvenido</h1>
            <p>Inicia sesión para continuar</p>
          </div>
          <?php if ($mensaje != ""): ?>
            <div class="alert alert-danger"><?php echo $mensaje; ?></div>
          <?php endif; ?>
          <form action="" method="post">
            <div class="mb-3">
              <label for="usuario" class="form-label">Documento</label>
              <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Ingrese su documento" required/>
            </div>
            <div class="mb-1">
              <label for="contrasena" class="form-label">Contraseña</label>
              <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Ingrese su contraseña" required/>
            </div>
            <div class="mb-3 text-end">
              <a href="RContraseña.html" class="link-recuperar">¿Olvidaste tu contraseña?</a>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary">Entrar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>