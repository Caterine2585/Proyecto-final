<?php
include("conexion.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar datos del formulario
    $nombres    = $_POST["nombres"];
    $apellidos  = $_POST["apellidos"];
    $tipo_doc   = $_POST["tipodocumento"];
    $num_doc    = $_POST["numerodocumento"];
    $edad       = $_POST["edad"];
    $genero     = $_POST["sexo"] === "femenino" ? "F" : "M";
    $correo     = $_POST["correo"];
    $telefono   = $_POST["telefono"];
    $rol        = $_POST["rol"] === "Administrador" ? "ROL001" : "ROL002";
    $estado     = "EST001"; // Por defecto Activo


    // Manejo de la foto
    $foto_nombre = "";
    if(isset($_FILES["Fotos"]) && $_FILES["Fotos"]["error"] == 0){
        $foto_nombre = "fotos_empleados/" . uniqid() . "_" . basename($_FILES["Fotos"]["name"]);
        move_uploaded_file($_FILES["Fotos"]["tmp_name"], $foto_nombre);
    }

    // Contraseña (Hash)
    $password   = password_hash($_POST["contraseña"], PASSWORD_DEFAULT);

    // Insertar en empleados
    $sql_emp = "INSERT INTO empleados (Documento_Empleado, Tipo_Documento, Nombre_Usuario, Apellido_Usuario, Edad, Correo_Electronico, Telefono, Genero, ID_Estado, ID_Rol, Fotos) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql_emp);
    $stmt->bind_param("ssssissssss", $num_doc, $tipo_doc, $nombres, $apellidos, $edad, $correo, $telefono, $genero, $estado, $rol, $foto_nombre);

    if($stmt->execute()){
        // Insertar en contrasenas
        $sql_pass = "INSERT INTO contrasenas (Documento_Empleado, Contrasena_Hash) VALUES (?, ?)";
        $stmt2 = $conexion->prepare($sql_pass);
        $stmt2->bind_param("ss", $num_doc, $password);
        $stmt2->execute();
        $mensaje = "<div class='alert alert-success'>Registro exitoso. ¡Ya puedes iniciar sesión!</div>";
    } else {
        $mensaje = "<div class='alert alert-danger'>Error: ".$stmt->error."</div>";
    }
    header("Location: iniciar_sesion.php"); /*Cambiar a PHP cuando allá*/
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Registro</title>
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/Registro.css" rel="stylesheet">
<link rel="icon" type="image/webp" href="../Imagenes/Logo.webp">
</head>
<body>
<img src="../Imagenes/Logo.webp" alt="Logo" class="logo">
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-6">
    <div class="card shadow-sm mb-4">
    <div class="card-header text-white text-center">
        <h1 class="h1 mb-0">Registrarse</h1>
    </div>
    <div class="card-body">
        <?php if($mensaje != "") echo $mensaje; ?>
        <form action="Registro.php" method="post" enctype="multipart/form-data">

        <div class="mb-3">
            <label for="nombres" class="form-label">Nombres</label>
            <input type="text" id="nombres" name="nombres" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="apellidos" class="form-label">Apellidos</label>
            <input type="text" id="apellidos" name="apellidos" class="form-control" required>
        </div>
        <div class="row">
            <div class="mb-3 col-md-6">
            <label for="tipodocumento" class="form-label">Tipo de Documento</label>
            <select id="tdocumento" name="tipodocumento" class="form-select" required>
                <option value="">Seleccione...</option>
                <option value="CC">Cédula de Ciudadanía (CC)</option>
                <option value="CE">Cédula de Extranjería (CE)</option>
                <option value="PP">Pasaporte (PP)</option>
            </select>
            </div>
            <div class="mb-3 col-md-6">
            <label for="numerodocumento" class="form-label">N° de Documento</label>
            <input type="number" id="ndocumento" name="numerodocumento" class="form-control" required>
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-md-4">
            <label for="edad" class="form-label">Edad</label>
            <input type="number" id="edad" name="edad" class="form-control" min="0" required>
            </div>
            <div class="mb-3 col-md-8">
            <label for="genero" class="form-label">Género</label>
            <select id="genero" name="sexo" class="form-select" required>
                <option value="">Seleccione...</option>
                <option value="femenino">Femenino</option>
                <option value="masculino">Masculino</option>
            </select>
            </div>
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">Correo Electrónico</label>
            <input type="email" id="correo" name="correo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Número Telefónico</label>
            <input type="tel" id="telefono" name="telefono" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="rol" class="form-label">Selecciona tu rol</label>
            <select id="rol" name="rol" class="form-select" required>
            <option value="">Seleccione...</option>
            <option value="Empleado">Empleado</option>
            <option value="Administrador">Administrador</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="fotografia" class="form-label">Fotografía</label>
            <input type="file" id="fotografia" name="fotografia" class="form-control" required accept="image/*">
        </div>
        <div class="row">
            <div class="mb mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" id="password" name="contraseña" class="form-control" required>
            </div>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn">Registrarse</button>
        </div>
        </form>
    </div>
    </div>
</div>
</div>
</div>
<footer class="footer">
    <p>Copyright © 2025 Fonrio</p>
</footer>
</body>
</html>