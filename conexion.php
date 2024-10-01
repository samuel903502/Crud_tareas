<?php
$host = "localhost";
$usuario = "root";     
$contraseña = ""; 
$base_de_datos = "crud_tareas";

$conexion = new mysqli($host, $usuario, $contraseña, $base_de_datos);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>
