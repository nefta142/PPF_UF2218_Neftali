<?php
$xmlPath = 'files/coches.xml';
$cars = simplexml_load_file($xmlPath);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Coches</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
<!--Mostramos Mensaje de eliminar coche -->
    <?php if (isset($_GET['msg'])): ?>
        <?php
        $msg = htmlspecialchars($_GET['msg']);
        $alert = '';
        switch ($msg) {
            case 'eliminado':
                $alert = '✅ Coche eliminado correctamente.';
                break; }
        ?>
        <?php if ($alert): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?= $alert ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php endif; ?>
    <?php endif; ?>
        <!--Formulario de inserción de nuevos coches-->
    <h2 class="mb-4">Insertar Nuevo Coche</h2>
    <form action="insertar_coche.php" method="post" class="bg-white p-4 shadow rounded mb-5">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Matrícula</label>
                <input type="text" name="matricula" class="form-control" required pattern="\d{4}[A-Z]{3}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Marca</label>
                <input type="text" name="marca" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Modelo</label>
                <input type="text" name="modelo" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Puertas</label>
                <input type="number" name="puertas" class="form-control" required min="2" max="5">
            </div>
            <div class="col-md-3">
                <label class="form-label">Color</label>
                <input type="text" name="color" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Precio</label>
                <input type="number" name="precio" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tipo de venta</label>
                <select name="venta" class="form-select" required>
                    <option value="">Selecciona</option>
                    <option value="nuevo">Nuevo</option>
                    <option value="ocasión">Ocasión</option>
                    <option value="segunda mano">Segunda Mano</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-4">Insertar</button>
    </form>

    <h2 class="mb-3">Listado de Coches</h2>
        <!-- Creación de la tabla-->
    <table id="tabla-coches" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Matrícula</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Puertas</th>
                <th>Color</th>
                <th>Precio</th>
                <th>Tipo de Venta</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cars->coche as $coche): ?>
            <tr>
            <!--Mostrar los datos de xml-->
                <td><?= $coche['matricula'] ?></td>
                <td><?= $coche->marca ?></td>
                <td><?= $coche->modelo ?></td>
                <td><?= $coche->puertas ?></td>
                <td><?= $coche->color ?></td>
                <td><?= $coche->precio ?></td>
                <td><?= $coche->precio['venta'] ?></td>
                <td class="d-flex gap-2">
                    <!-- Eliminar-->
                    <form action="eliminar_coche.php" method="post" onsubmit="return confirm('¿Estás seguro de eliminar este coche?');">
                        <input type="hidden" name="matricula" value="<?= $coche['matricula'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                     <!-- Botón Modificar -->
                    <form action="modificar_coche.php" method="get">
                        <input type="hidden" name="matricula" value="<?= $coche['matricula'] ?>">
                        <button type="submit" class="btn btn-warning btn-sm">Modificar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Añadimos Bootstrap y DataTables -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#tabla-coches').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            }
        });
    });
</script>
</body>
</html>
