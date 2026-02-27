<?php
include("libreria/motor.php");
include_once("libreria/libro_d.php");

// iniciar sesion solo si no hay otra
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// validar que venga el POST
if (!isset($_POST['b'])) {
    exit("No se recibió parámetro de búsqueda");
}

$str_b = $_POST['b'];

// crear conexión (esto depende de tu motor.php)
$objConexion = new Conexion();

// buscar libros
$lib = Libro_d::buscar($objConexion->enlace, $str_b);
?>

<?php if ($lib && count($lib) > 0): ?>
<div class="panel panel-default">
    <div class="panel-heading">Publicaciones Encontradas</div> 
    <div style="overflow: scroll;height: 350px;">
        <table class="tabla_edicion table table-hover">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Tipo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($lib as $libros): ?>
                <tr>
                    <td><a href="libros_d/<?php echo $libros['Archivo']?>" target="_blank">
                        <?php echo $libros['Titulo'] ?>
                    </a></td>
                    <td><?php echo $libros['Autor'] ?></td>
                    <td><?php echo $libros['tipo'] ?></td>

                    <?php $file_l = $libros['Archivo']; ?>

                    <?php if (isset($_SESSION['username']) && $_SESSION['rol']=='administrador'): ?>
                        <td><button class="btn btn-primary btn-xs" onclick="editar(<?php echo $libros['id_libro']?>)">Editar</button></td>
                        <td><button class="btn btn-primary btn-xs" onclick="borrar(<?php echo $libros['id_libro']?>)">Borrar</button></td>
                    <?php else: ?>
                        <td><button class="btn btn-primary btn-xs" onclick="ver_info(<?php echo $libros['id_libro']?>)">Info</button></td>
                    <?php endif; ?>

                    <td><button class="btn btn-primary btn-xs" onclick="cargar_pdf('#capa_d','<?php echo $file_l?>')">Min</button></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>
