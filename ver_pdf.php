<?php
if (!empty($_POST['archivo'])) {

    $archivo = $_POST['archivo']; // Ej: "videooo.mp4" o "demian.pdf"
    $ext = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
    $file_encoded = rawurlencode(basename($archivo));

    // Determinar carpeta según tipo
    if (in_array($ext, ['mp4','webm','ogg'])) {
        $archivo_url = 'videos/' . $file_encoded;
    } elseif (in_array($ext, ['mp3','m4a','wav'])) {
        $archivo_url = 'audios/' . $file_encoded;
    } elseif ($ext === 'pdf' || in_array($ext, ['txt','doc','docx'])) {
        $archivo_url = 'libros_d/' . $file_encoded;
    } elseif (in_array($ext, ['jpg','jpeg','png','gif'])) {
        $archivo_url = 'images/' . $file_encoded;
    } else {
        $archivo_url = ''; // tipo no soportado
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div id="capa_d">
                <?php if (!empty($archivo_url) && file_exists($archivo_url)): ?>
                    <?php if ($ext === 'pdf'): ?>
                        <!-- PDF -->
                        <object data="<?= htmlspecialchars($archivo_url) ?>" type="application/pdf" width="640" height="500">
                            <p>Tu navegador no soporta PDF. <a href="<?= htmlspecialchars($archivo_url) ?>">Descargar PDF</a></p>
                        </object>

                    <?php elseif (in_array($ext, ['mp4','webm','ogg'])): ?>
                        <!-- Video -->
                        <?php $mime = $ext === 'mp4' ? 'video/mp4' : ($ext === 'webm' ? 'video/webm' : 'video/ogg'); ?>
                        <video width="640" height="360" controls>
                            <source src="<?= htmlspecialchars($archivo_url) ?>" type="<?= $mime ?>">
                            Tu navegador no soporta el elemento de video.
                        </video>

                    <?php elseif (in_array($ext, ['mp3','m4a','wav'])): ?>
                        <!-- Audio -->
                        <?php 
                        $mime = $ext === 'mp3' ? 'audio/mpeg' : ($ext === 'wav' ? 'audio/wav' : 'audio/mp4'); 
                        ?>
                        <audio controls>
                            <source src="<?= htmlspecialchars($archivo_url) ?>" type="<?= $mime ?>">
                            Tu navegador no soporta el elemento de audio.
                        </audio>

                    <?php elseif (in_array($ext, ['jpg','jpeg','png','gif'])): ?>
                        <!-- Imagen -->
                        <img src="<?= htmlspecialchars($archivo_url) ?>" alt="Imagen" style="max-width:100%;">

                    <?php elseif (in_array($ext, ['txt','doc','docx'])): ?>
                        <!-- Texto o Word -->
                        <p>Archivo <?= htmlspecialchars($archivo) ?>. <a href="<?= htmlspecialchars($archivo_url) ?>">Descargar</a></p>

                    <?php else: ?>
                        <p>Tipo de archivo no soportado. <a href="<?= htmlspecialchars($archivo_url) ?>">Descargar</a></p>
                    <?php endif; ?>
                <?php else: ?>
                    <p>No se seleccionó ningún archivo o el archivo no existe.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
