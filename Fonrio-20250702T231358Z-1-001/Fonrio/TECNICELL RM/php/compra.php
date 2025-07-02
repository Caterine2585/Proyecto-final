<?php
$conexion = new mysqli("localhost", "root", "", "fonrio");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// AGREGAR
if (isset($_POST['agregar'])) {
    $ID_Entrada = $_POST['ID_Entrada'];
    $Precio_Compra = $_POST['Precio_Compra'];
    $ID_Producto = $_POST['ID_Producto'];
    $Documento_Empleado = $_POST['Documento_Empleado'];

    // Detalle
    $Cantidad = $_POST['Cantidad'];
    $Fecha_Entrada = $_POST['Fecha_Entrada'];
    $ID_Proveedor = $_POST['ID_Proveedor'];

    // Agregar a Compras
    $conexion->query("INSERT INTO Compras (ID_Entrada, Precio_Compra, ID_Producto, Documento_Empleado) VALUES ('$ID_Entrada', '$Precio_Compra', '$ID_Producto', '$Documento_Empleado')");
    // Agregar a Detalle_Compras
    $conexion->query("INSERT INTO Detalle_Compras (Cantidad, ID_Entrada, Fecha_Entrada, ID_Proveedor) VALUES ('$Cantidad', '$ID_Entrada', '$Fecha_Entrada', '$ID_Proveedor')");
    
    header("Location: Proveedor.php");
    exit();
}

// ELIMINAR
if (isset($_GET['eliminar'])) {
    $ID_Entrada = $_GET['eliminar'];

    // Primero elimina de Detalle_Compras (por FK)
    $conexion->query("DELETE FROM Detalle_Compras WHERE ID_Entrada='$ID_Entrada'");
    // Luego de Compras
    $conexion->query("DELETE FROM Compras WHERE ID_Entrada='$ID_Entrada'");
    header("Location: Proveedor.php");
    exit();
}

// EDITAR / ACTUALIZAR
if (isset($_POST['actualizar'])) {
    $ID_Entrada = $_POST['ID_Entrada'];
    $Precio_Compra = $_POST['Precio_Compra'];
    $ID_Producto = $_POST['ID_Producto'];
    $Documento_Empleado = $_POST['Documento_Empleado'];

    // Detalle
    $Cantidad = $_POST['Cantidad'];
    $Fecha_Entrada = $_POST['Fecha_Entrada'];
    $ID_Proveedor = $_POST['ID_Proveedor'];

    // Actualiza Compras
    $conexion->query("UPDATE Compras SET Precio_Compra='$Precio_Compra', ID_Producto='$ID_Producto', Documento_Empleado='$Documento_Empleado' WHERE ID_Entrada='$ID_Entrada'");
    // Actualiza Detalle_Compras
    $conexion->query("UPDATE Detalle_Compras SET Cantidad='$Cantidad', Fecha_Entrada='$Fecha_Entrada', ID_Proveedor='$ID_Proveedor' WHERE ID_Entrada='$ID_Entrada'");

    header("Location: Proveedor.php");
    exit();
}

// MOSTRAR LOS DATOS (join para mostrar todo)
$registros = $conexion->query(
    "SELECT c.*, dc.Cantidad, dc.Fecha_Entrada, dc.ID_Proveedor
    FROM Compras c
    JOIN Detalle_Compras dc ON c.ID_Entrada = dc.ID_Entrada
    ORDER BY c.ID_Entrada DESC"
);

// Para editar
$editando = false;
if (isset($_GET['editar'])) {
    $editando = true;
    $ID_Entrada = $_GET['editar'];
    $res = $conexion->query(
        "SELECT c.*, dc.Cantidad, dc.Fecha_Entrada, dc.ID_Proveedor
        FROM Compras c
        JOIN Detalle_Compras dc ON c.ID_Entrada = dc.ID_Entrada
        WHERE c.ID_Entrada='$ID_Entrada'"
    );
    $filaEditar = $res->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CRUD de Compras</title>
    <link rel="stylesheet" href="../css/compra.css">
</head>
<body>
<div class="container">
    <h2 class="titulo">CRUD de Compras</h2>

    <?php if ($editando): ?>
        <h4>Editando registro: <?= htmlspecialchars($filaEditar['ID_Entrada']) ?></h4>
        <form method="POST">
            <input type="hidden" name="ID_Entrada" value="<?= htmlspecialchars($filaEditar['ID_Entrada']) ?>">
            <input type="number" step="0.01" name="Precio_Compra" value="<?= htmlspecialchars($filaEditar['Precio_Compra']) ?>" required placeholder="Precio Compra">
            <input type="text" name="ID_Producto" value="<?= htmlspecialchars($filaEditar['ID_Producto']) ?>" required placeholder="ID Producto">
            <input type="text" name="Documento_Empleado" value="<?= htmlspecialchars($filaEditar['Documento_Empleado']) ?>" required placeholder="Documento Empleado">
            
            <!-- Detalle -->
            <input type="number" name="Cantidad" value="<?= htmlspecialchars($filaEditar['Cantidad']) ?>" required placeholder="Cantidad">
            <input type="date" name="Fecha_Entrada" value="<?= htmlspecialchars($filaEditar['Fecha_Entrada']) ?>" required placeholder="Fecha Entrada">
            <input type="text" name="ID_Proveedor" value="<?= htmlspecialchars($filaEditar['ID_Proveedor']) ?>" required placeholder="ID Proveedor">

            <button type="submit" name="actualizar" class="boton naranja">Actualizar</button>
            <a href="Proveedor.php" class="boton">Cancelar</a>
        </form>
    <?php else: ?>
        <h4>Agregar nueva compra</h4>
        <form method="POST">
            <input type="text" name="ID_Entrada" required placeholder="ID Entrada">
            <input type="number" step="0.01" name="Precio_Compra" required placeholder="Precio Compra">
            <input type="text" name="ID_Producto" required placeholder="ID Producto">
            <input type="text" name="Documento_Empleado" required placeholder="Documento Empleado">
            
            <!-- Detalle -->
            <input type="number" name="Cantidad" required placeholder="Cantidad">
            <input type="date" name="Fecha_Entrada" required placeholder="Fecha Entrada">
            <input type="text" name="ID_Proveedor" required placeholder="ID Proveedor">

            <button type="submit" name="agregar" class="boton">Agregar</button>
        </form>
    <?php endif; ?>

    <table>
        <tr>
            <th>ID Entrada</th>
            <th>Precio Compra</th>
            <th>ID Producto</th>
            <th>Documento Empleado</th>
            <th>Cantidad</th>
            <th>Fecha Entrada</th>
            <th>ID Proveedor</th>
            <th>Acciones</th>
        </tr>
        <?php while($row = $registros->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['ID_Entrada']) ?></td>
            <td>$<?= htmlspecialchars(number_format($row['Precio_Compra'],2)) ?></td>
            <td><?= htmlspecialchars($row['ID_Producto']) ?></td>
            <td><?= htmlspecialchars($row['Documento_Empleado']) ?></td>
            <td><?= htmlspecialchars($row['Cantidad']) ?></td>
            <td><?= htmlspecialchars($row['Fecha_Entrada']) ?></td>
            <td><?= htmlspecialchars($row['ID_Proveedor']) ?></td>
            <td class="acciones">
                <a href="Proveedor.php?editar=<?= urlencode($row['ID_Entrada']) ?>" class="boton naranja">Editar</a> 
                <a href="Proveedor.php?eliminar=<?= urlencode($row['ID_Entrada']) ?>" class="boton rojo" onclick="return confirm('¿Seguro que deseas eliminar este registro?');">Eliminar</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>