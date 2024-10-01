<?php
include 'conexion.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

// Obtener la tarea existente
$stmt = $conexion->prepare("SELECT * FROM tareas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$tarea = $resultado->fetch_assoc();
$stmt->close();

// Manejar actualización de tarea
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar'])) {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $estado = $_POST['estado'];

    $stmt = $conexion->prepare("UPDATE tareas SET titulo = ?, descripcion = ?, estado = ? WHERE id = ?");
    $stmt->bind_param("sssi", $titulo, $descripcion, $estado, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Tarea</title>
    <link rel="stylesheet" href="estilos.css">
    <script>
    function actualizarColorSelect(selectElement) {
        const valor = selectElement.value;
        selectElement.classList.remove('estado-pendiente', 'estado-en-progreso', 'estado-completada');

        switch(valor) {
            case 'Pendiente':
                selectElement.classList.add('estado-pendiente');
                break;
            case 'En Progreso':
                selectElement.classList.add('estado-en-progreso');
                break;
            case 'Completada':
                selectElement.classList.add('estado-completada');
                break;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const selects = document.querySelectorAll('.formulario select');
        selects.forEach(function(select) {
            // Establecer color inicial
            actualizarColorSelect(select);

            // Cambiar color al cambiar la selección
            select.addEventListener('change', function() {
                actualizarColorSelect(select);
            });
        });
    });
</script>

</head>
<body>
    <div class="contenedor">
        <h1>Actualizar Tarea</h1>
        <form action="actualizar.php?id=<?php echo $id; ?>" method="POST" class="formulario">
            <input type="text" name="titulo" placeholder="Título de la tarea" value="<?php echo htmlspecialchars($tarea['titulo']); ?>" required>
            <textarea name="descripcion" placeholder="Descripción"><?php echo htmlspecialchars($tarea['descripcion']); ?></textarea>
            <select name="estado">
                <option value="Pendiente" <?php if($tarea['estado'] == 'Pendiente') echo 'selected'; ?>>Pendiente</option>
                <option value="En Progreso" <?php if($tarea['estado'] == 'En Progreso') echo 'selected'; ?>>En Progreso</option>
                <option value="Completada" <?php if($tarea['estado'] == 'Completada') echo 'selected'; ?>>Completada</option>
            </select>
            <button type="submit" name="actualizar">Actualizar Tarea</button>
        </form>
    </div>
</body>
</html>
