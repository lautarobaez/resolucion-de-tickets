<?php
include_once("libreria/config.php");

// Evitar iniciar sesión dos veces
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username']) && !isset($_SESSION['userid'])) {

    // Conexión a la base de datos
    $idcnx = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (!$idcnx) {
        echo 0;
        exit;
    }

    // LOGIN
    if (isset($_POST['login_username']) && isset($_POST['login_userpass'])) {    
        $username = mysqli_real_escape_string($idcnx, $_POST['login_username']);
        $password = md5($_POST['login_userpass']); // Mantener md5 si tu tabla ya está así

        $sql = "SELECT id, user, rol 
                FROM personas 
                WHERE user='$username' AND passwd='$password' 
                LIMIT 1";

        $res = mysqli_query($idcnx, $sql);
        if ($res && mysqli_num_rows($res) == 1) {
            $user = mysqli_fetch_assoc($res);

            $_SESSION['username'] = $user['user'];
            $_SESSION['userid']   = $user['id'];
            $_SESSION['rol']      = $user['rol'];

            echo 1; // Login exitoso
        } else {
            echo 0; // Datos incorrectos
        }
    }

    // REGISTRO
    if (isset($_POST['rec_username']) && isset($_POST['rec_userpass']) && isset($_POST['rec_email'])) {
        $username = mysqli_real_escape_string($idcnx, $_POST['rec_username']);
        $password = md5($_POST['rec_userpass']); // Mantener md5
        $email    = mysqli_real_escape_string($idcnx, $_POST['rec_email']);

        $sql = "INSERT INTO personas (user, passwd, email, rol) VALUES (
                    '$username',
                    '$password',
                    '$email',
                    'estudiante'
                )";

        if (mysqli_query($idcnx, $sql)) {
            echo 1; // Registro exitoso
        } else {
            echo 0; // Error al registrar
        }
    }

    mysqli_close($idcnx);
} else {
    echo 0; // Ya hay sesión iniciada
}
?>
