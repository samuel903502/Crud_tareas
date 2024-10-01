<?php
include 'conexion.php';

// Manejar creación de tarea
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear'])) {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $estado = $_POST['estado'];

    $stmt = $conexion->prepare("INSERT INTO tareas (titulo, descripcion, estado) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $titulo, $descripcion, $estado);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    exit();
}

// Obtener todas las tareas
$resultado = $conexion->query("SELECT * FROM tareas ORDER BY fecha_creacion DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CRUD de Tareas Diarias</title>
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
        <h1>Mis Tareas Diarias</h1>

        <form action="index.php" method="POST" class="formulario">
            <h2>Agregar Nueva Tarea</h2>
            <input type="text" name="titulo" placeholder="Título de la tarea" required>
            <textarea name="descripcion" placeholder="Descripción"></textarea>
            <select name="estado">
                <option value="Pendiente">Pendiente</option>
                <option value="En Progreso">En Progreso</option>
                <option value="Completada">Completada</option>
            </select>
            <button type="submit" name="crear">Agregar Tarea</button>
        </form>

      
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Descripción</th>
            <th>Estado</th>
            <th>Fecha de Creación</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while($fila = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?php echo $fila['id']; ?></td>
                <td><?php echo htmlspecialchars($fila['titulo']); ?></td>
                <td><?php echo htmlspecialchars($fila['descripcion']); ?></td>
                <td>
                    <?php
                        // Asignar clase CSS según el estado
                        $estadoClase = '';
                        switch($fila['estado']) {
                            case 'Pendiente':
                                $estadoClase = 'estado-pendiente';
                                break;
                            case 'En Progreso':
                                $estadoClase = 'estado-en-progreso';
                                break;
                            case 'Completada':
                                $estadoClase = 'estado-completada';
                                break;
                        }
                    ?>
                    <span class="estado <?php echo $estadoClase; ?>">
                        <?php echo $fila['estado']; ?>
                    </span>
                </td>
                <td><?php echo $fila['fecha_creacion']; ?></td>
                <td>
                    <a href="actualizar.php?id=<?php echo $fila['id']; ?>" class="editar">Editar</a>
                    <a href="eliminar.php?id=<?php echo $fila['id']; ?>" class="eliminar" onclick="return confirm('¿Estás seguro de eliminar esta tarea?');">Eliminar</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>


    </div>
</body>
</html>
