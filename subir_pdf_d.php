<?php
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_FILES["fileToUpload"])) {

    $file = $_FILES["fileToUpload"];
    $fileName = basename($file["name"]);
    $fileSize = $file["size"];
    $fileTmp = $file["tmp_name"];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $errors = [];
    $messages = [];
    $uploadOk = 1;

    // Definir extensiones permitidas por tipo
    $documentExt = ['pdf','txt','doc','docx'];
    $imageExt = ['jpg','jpeg','png','gif'];
    $videoExt = ['mp4','avi','mov','mkv','webm','ogg'];
    $audioExt = ['mp3','wav','ogg','m4a']; // <- agregamos m4a

    // Inicializar variables
    $target_dir = "";
    $maxSize = 0;

    // Definir carpeta y tamaño máximo según tipo
    if(in_array($fileExt, $documentExt)){
        $target_dir = "libros_d/";
        $maxSize = 12 * 1024 * 1024; // 12 MB
    } elseif(in_array($fileExt, $imageExt)){
        $target_dir = "images/cartelera/";
        $maxSize = 5 * 1024 * 1024; // 5 MB
    } elseif(in_array($fileExt, $videoExt)){
        $target_dir = "videos/";
        $maxSize = 50 * 1024 * 1024; // 50 MB
    } elseif(in_array($fileExt, $audioExt)){
        $target_dir = "audios/";
        $maxSize = 50 * 1024 * 1024; // 50 MB
    } else {
        $errors[] = "Lo sentimos, el tipo de archivo no es permitido.";
        $uploadOk = 0;
    }

    // Crear carpeta si no existe y solo si $target_dir está definido
    if($uploadOk && !file_exists($target_dir)){
        mkdir($target_dir, 0777, true);
    }

    // Preparar ruta segura
    if($uploadOk){
        $safeFileName = rawurlencode($fileName);
        $target_file = $target_dir . $safeFileName;

        // Verificar tamaño
        if($fileSize > $maxSize){
            $errors[] = "Lo sentimos, el archivo es demasiado grande.";
            $uploadOk = 0;
        }

        // Verificar si ya existe
        if(file_exists($target_file)){
            $errors[] = "Lo sentimos, el archivo ya existe.";
            $uploadOk = 0;
        }

        // Subir archivo
        if($uploadOk){
            if(move_uploaded_file($fileTmp, $target_file)){
                $messages[] = "El archivo ha sido subido correctamente a $target_dir";
            } else {
                $errors[] = "Hubo un error subiendo el archivo.";
            }
        }
    }

    // Mostrar errores
    if(!empty($errors)){
        echo '<div class="alert alert-danger">';
        foreach($errors as $error){
            echo "<p>$error</p>";
        }
        echo '</div>';
    }

    // Mostrar mensajes de éxito
    if(!empty($messages)){
        echo '<div class="alert alert-success">';
        foreach($messages as $msg){
            echo "<p>$msg</p>";
        }
        echo '</div>';
    }

} else {
    echo '<div class="alert alert-warning">No se ha seleccionado ningún archivo.</div>';
}
?>
