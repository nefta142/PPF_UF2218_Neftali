<?php
$xmlPath = 'files/coches.xml';
$xsdPath = 'files/coches.xsd';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevoCoche = [
        'matricula' => strtoupper($_POST['matricula'] ?? ''),
        'marca'     => $_POST['marca'] ?? '',
        'modelo'    => $_POST['modelo'] ?? '',
        'puertas'   => $_POST['puertas'] ?? '',
        'color'     => $_POST['color'] ?? '',
        'precio'    => $_POST['precio'] ?? '',
        'venta'     => $_POST['venta'] ?? ''
    ];


    // Cargar XML existente
    $xml = simplexml_load_file($xmlPath);
    if ($xml === false) {
        die("❌ Error al cargar el XML original.");
    }

        foreach ($xml->coche as $cocheExistente) {
    if ((string)$cocheExistente['matricula'] === $nuevoCoche['matricula']) {
        echo "❌ Ya existe un coche con la matrícula <strong>{$nuevoCoche['matricula']}</strong>.
        <div style='margin-left: 2rem;'>
        <a href='index.php' class='btn btn-primary'>Volver al inicio</a>
        </div>";
exit;
;
        
    }
}
    // Insertar nuevo coche
    $coche = $xml->addChild('coche');
    $coche->addAttribute('matricula', $nuevoCoche['matricula']);
    $coche->addChild('marca', $nuevoCoche['marca']);
    $coche->addChild('modelo', $nuevoCoche['modelo']);
    $coche->addChild('puertas', $nuevoCoche['puertas']);
    $coche->addChild('color', $nuevoCoche['color']);
    $precio = $coche->addChild('precio', $nuevoCoche['precio']);
    $precio->addAttribute('venta', $nuevoCoche['venta']);

    // Guardar en archivo temporal
    $tempPath = 'files/temp_coches.xml';
    if (!$xml->asXML($tempPath)) {
        die("❌ Error al guardar archivo temporal '$tempPath'. Verifica permisos.");
    }

    // Verificar que el archivo temporal existe y tiene contenido
    if (!file_exists($tempPath) || filesize($tempPath) === 0) {
        die("❌ Archivo temporal no creado o está vacío.");
    }

    // Validar XML temporal con XSD
    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->load($tempPath);

    if ($dom->schemaValidate($xsdPath)) {
        // Guardar XML validado
        $xml->asXML($xmlPath);
        unlink($tempPath);

        echo "✅ Insertado y validado.";
        echo "</ul><a href='index.php'>Volver</a>";

      //  header("Location: index.php?msg=insertado");
      //  exit;
    } else {
        echo "<p style='color:red'>❌ Error de validación XML:</p><ul>";
        foreach (libxml_get_errors() as $error) {
            echo "<li>Línea {$error->line}: {$error->message}</li>";
        }
        echo "</ul><a href='index.php'>Volver</a>";
        libxml_clear_errors();
        unlink($tempPath);
    }
} else {
    echo "❌ Método no permitido.";
}
?>
