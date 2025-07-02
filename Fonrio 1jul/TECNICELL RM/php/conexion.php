<?php
$host = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "fonrio";

// Crear conexión
$conexion = new mysqli($host, $usuario, $contrasena, $base_datos);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>