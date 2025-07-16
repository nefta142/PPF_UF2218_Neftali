<?php
$xmlPath = 'files/coches.xml';
$xsdPath = 'files/coches.xsd';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matriculaEliminar = $_POST['matricula'] ?? '';

    $xml = simplexml_load_file($xmlPath);
    if ($xml === false) {
        die("❌ Error al cargar el XML.");
    }

    // Buscar y eliminar el coche
    $index = 0;
    foreach ($xml->coche as $coche) {
        if ((string)$coche['matricula'] === $matriculaEliminar) {
            unset($xml->coche[$index]);
            break;
        }
        $index++;
    }

    // Validar antes de guardar
    $tempPath = 'files/temp_coches.xml';
    $xml->asXML($tempPath);

    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->load($tempPath);

    if ($dom->schemaValidate($xsdPath)) {
        $xml->asXML($xmlPath);
        unlink($tempPath);
        header("Location: index.php?msg=eliminado");
        exit;
    } else {
        echo "<p style='color:red'>❌ XML inválido después de eliminar:</p><ul>";
        foreach (libxml_get_errors() as $error) {
            echo "<li>{$error->message}</li>";
        }
        echo "</ul><a href='index.php'>Volver</a>";
        unlink($tempPath);

        if ($xml->asXML($xmlPath)) {
    echo "✅ XML guardado correctamente en $xmlPath";
} else {
    echo "❌ No se pudo guardar en $xmlPath";
}

    }
} else {
    echo "Método no permitido.";
}
