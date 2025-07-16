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

    // Cargar XML
    $xml = simplexml_load_file($xmlPath);
    if ($xml === false) {
        die("❌ Error al cargar el XML.");
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
    $xml->asXML($tempPath);

    // Validar con XSD
    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->load($tempPath);

    if ($dom->schemaValidate($xsdPath)) {
        $xml->asXML($xmlPath);
        unlink($tempPath);
        echo "<p style='color:green'>✅ Coche insertado correctamente y XML válido.</p>";
        echo "<a href='formulario.html'>Volver</a>";
    } else {
        echo "<p style='color:red'>❌ Error de validación XML:</p><ul>";
        foreach (libxml_get_errors() as $error) {
            echo "<li>Línea {$error->line}: {$error->message}</li>";
        }
        echo "</ul><a href='formulario.html'>Volver</a>";
        libxml_clear_errors();
        unlink($tempPath);
    }
} else {
    echo "Método no permitido.";
}
?>
