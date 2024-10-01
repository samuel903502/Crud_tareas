<?php
include 'conexion.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

// Eliminar la tarea
$stmt = $conexion->prepare("DELETE FROM tareas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

header("Location: index.php");
exit();
?>
