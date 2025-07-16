<?php
$xmlPath = 'files/coches.xml';
$xsdPath = 'files/coches.xsd';

// Función para mostrar errores de validación XML
function mostrarErroresLibxml() {
    echo "<ul style='color:red;'>";
    foreach (libxml_get_errors() as $error) {
        echo "<li>Línea {$error->line}: {$error->message}</li>";
    }
    echo "</ul>";
    libxml_clear_errors();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Mostrar formulario con datos del coche a modificar

    if (!isset($_GET['matricula'])) {
        die("❌ Matrícula no especificada.");
    }
    $matricula = strtoupper($_GET['matricula']);

    if (!file_exists($xmlPath)) {
        die("❌ Archivo XML no encontrado.");
    }

    $xml = simplexml_load_file($xmlPath);

    // Buscar coche por matrícula
    $cocheEncontrado = null;
    foreach ($xml->coche as $coche) {
        if ((string)$coche['matricula'] === $matricula) {
            $cocheEncontrado = $coche;
            break;
        }
    }

    if (!$cocheEncontrado) {
        die("❌ Coche con matrícula $matricula no encontrado.");
    }

    // Mostrar formulario con valores precargados
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Modificar Coche <?= htmlspecialchars($matricula) ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
    <div class="container py-5">
        <h2>Modificar Coche <?= htmlspecialchars($matricula) ?></h2>
<!--Formulario de modificacion-->
        <form action="modificar_coche.php" method="post" class="bg-white p-4 shadow rounded">
            <input type="hidden" name="matricula" value="<?= htmlspecialchars($matricula) ?>">

            <div class="mb-3">
                <label class="form-label">Marca</label>
                <input type="text" name="marca" class="form-control" required value="<?= htmlspecialchars($cocheEncontrado->marca) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Modelo</label>
                <input type="text" name="modelo" class="form-control" required value="<?= htmlspecialchars($cocheEncontrado->modelo) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Puertas</label>
                <input type="number" name="puertas" min="2" max="5" class="form-control" required value="<?= htmlspecialchars($cocheEncontrado->puertas) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Color</label>
                <input type="text" name="color" class="form-control" required value="<?= htmlspecialchars($cocheEncontrado->color) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Precio</label>
                <input type="number" name="precio" class="form-control" required value="<?= htmlspecialchars($cocheEncontrado->precio) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Tipo de Venta</label>
                <select name="venta" class="form-select" required>
                    <option value="">Selecciona</option>
                    <option value="nuevo" <?= $cocheEncontrado->precio['venta'] == 'nuevo' ? 'selected' : '' ?>>Nuevo</option>
                    <option value="ocasión" <?= $cocheEncontrado->precio['venta'] == 'ocasión' ? 'selected' : '' ?>>Ocasión</option>
                    <option value="segunda mano" <?= $cocheEncontrado->precio['venta'] == 'segunda mano' ? 'selected' : '' ?>>Segunda Mano</option>
                </select>
            </div>
<!--Boton de guardar-->
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
    </body>
    </html>

    <?php
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Guardar cambios

    $matricula = strtoupper($_POST['matricula'] ?? '');
    $marca     = $_POST['marca'] ?? '';
    $modelo    = $_POST['modelo'] ?? '';
    $puertas   = $_POST['puertas'] ?? '';
    $color     = $_POST['color'] ?? '';
    $precioVal = $_POST['precio'] ?? '';
    $venta     = $_POST['venta'] ?? '';

    if (!file_exists($xmlPath)) {
        die("❌ Archivo XML no encontrado.");
    }

    $xml = simplexml_load_file($xmlPath);

    // Buscar coche a modificar
    $cocheEncontrado = null;
    foreach ($xml->coche as $coche) {
        if ((string)$coche['matricula'] === $matricula) {
            $cocheEncontrado = $coche;
            break;
        }
    }

    if (!$cocheEncontrado) {
        die("❌ Coche con matrícula $matricula no encontrado.");
    }

    // Actualizar valores
    $cocheEncontrado->marca = $marca;
    $cocheEncontrado->modelo = $modelo;
    $cocheEncontrado->puertas = $puertas;
    $cocheEncontrado->color = $color;
    $cocheEncontrado->precio = $precioVal;
    $cocheEncontrado->precio['venta'] = $venta;

    // Guardar en temporal para validar
    $tempPath = 'files/temp_coches.xml';
    if (!$xml->asXML($tempPath)) {
        die("❌ Error al guardar archivo temporal.");
    }

    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->load($tempPath);

    if ($dom->schemaValidate($xsdPath)) {
        // Validado, guardamos en definitivo
        if ($xml->asXML($xmlPath)) {
            unlink($tempPath);
            echo "✅ Coche modificado y validado correctamente.";
            echo "<br><a href='index.php'>Volver</a>";
        } else {
            echo "❌ No se pudo guardar el XML final.";
        }
    } else {
        echo "<p style='color:red'>❌ Error de validación XML:</p>";
        mostrarErroresLibxml();
        unlink($tempPath);
        echo "<a href='index.php'>Volver</a>";
    }
} else {
    echo "❌ Método no permitido.";
}
?>
