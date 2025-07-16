<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
?>
<?php 
$xmlPath = "files/coches.xml";
$cars = simplexml_load_file($xmlPath);
$resultados = [];

if ($_SERVER["REQUEST_METHOD"] === "POST"){
    $termino = strtolower(trim($_POST["busqueda"] ?? ''));

    if ($termino !== ''){
        foreach ($cars->coche as $coche){
            $marca = strtolower((string)$coche->marca);
            $modelo = strtolower((string)$coche->modelo);

            if (str_contains($marca, $termino) || str_contains($modelo, $termino)) {
                $resultados[] = $coche;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscar Coche</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2>Buscar Coches por Marca o Modelo</h2>
    
    <form method="post" class="mb-4 bg-white p-4 shadow rounded">
        <div class="input-group">
            <input type="text" name="busqueda" class="form-control" placeholder="Introduce marca o modelo" required>
            <button class="btn btn-primary" type="submit">Buscar</button>
        </div>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <h4>Resultados:</h4>
        <?php if (count($resultados) > 0): ?>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Matrícula</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Puertas</th>
                        <th>Color</th>
                        <th>Precio</th>
                        <th>Tipo de Venta</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultados as $coche): ?>
                        <tr>
                            <td><?= $coche['matricula'] ?></td>
                            <td><?= $coche->marca ?></td>
                            <td><?= $coche->modelo ?></td>
                            <td><?= $coche->puertas ?></td>
                            <td><?= $coche->color ?></td>
                            <td><?= $coche->precio ?></td>
                            <td><?= $coche->precio['venta'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="container py-5">
        <?php else: ?>
            <div class="alert alert-warning">No se encontraron coches con ese término.</div>
        <?php endif; ?>
    <?php endif; ?>
     <!-- Botón Volver -->
    <a href="index.php" class="btn btn-secondary mt-3">Volver</a>
</div>
</body>
</html>
