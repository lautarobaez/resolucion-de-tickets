<?php  
include_once("libreria/motor.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_libro = intval($_POST['id_libro']);
    $id_persona = intval($_POST['id_persona']);
    $fecha_prestamo = mysqli_real_escape_string($objConexion->enlace, $_POST['fecha_prestamo']);

    // Registrar préstamo en tabla prestamos
    $sql = "
        INSERT INTO prestamos (id_libro, id_persona, fecha_prestamo)
        VALUES ($id_libro, $id_persona, '$fecha_prestamo')
    ";

    if (mysqli_query($objConexion->enlace, $sql)) {

        // actualizar estado del libro
        mysqli_query(
            $objConexion->enlace,
            "UPDATE libros_d SET estado='prestado' WHERE id_libro = $id_libro"
        );

        $mensaje = "✅ Préstamo registrado correctamente.";

    } else {
        $mensaje = "❌ Error al registrar préstamo: " . mysqli_error($objConexion->enlace);
    }
}


// Obtener libros disponibles
$libros = mysqli_query(
    $objConexion->enlace,
    "SELECT id_libro, Titulo 
     FROM libros_d 
     WHERE estado='disponible'"
);

// Obtener personas
$personas = mysqli_query(
    $objConexion->enlace,
    "SELECT id, nombre, apellido FROM personas"
);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrar préstamo</title>
    <meta charset="UTF-8">
</head>
<body>

<h2>Registrar préstamo</h2>

<?php if ($mensaje != ""): ?>
    <p><strong><?= $mensaje ?></strong></p>
<?php endif; ?>

<form method="POST">

    <label>Libro:</label>
    <select name="id_libro" required>
        <option value="">Seleccionar</option>

        <?php while($l = mysqli_fetch_assoc($libros)): ?>
            <option value="<?= $l['id_libro'] ?>">
                <?= htmlspecialchars($l['Titulo']) ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>


    <label>Persona:</label>
    <select name="id_persona" required>
        <option value="">Seleccionar</option>

        <?php while($p = mysqli_fetch_assoc($personas)): ?>
            <option value="<?= $p['id'] ?>">
                <?= htmlspecialchars($p['nombre']." ".$p['apellido']) ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>


    <label>Fecha préstamo:</label>
    <input type="date" name="fecha_prestamo" required><br><br>

    <button type="submit">Registrar</button>

</form>

</body>
</html>
