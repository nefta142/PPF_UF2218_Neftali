<?php
session_start();
$xmlPath = 'control/usuarios.xml';
$xsdPath = 'control/usuarios.xsd';

if($_SERVER ['REQUEST_METHOD'] === 'POST'){
    $usuario = $_POST ['usuario'] ?? '';
    $clave = $_POST ['clave'] ?? '';

    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->load($xmlPath);
    if (!$dom->schemaValidate($xsdPath)){
        $error = "Usuario inválido";
    }
    else {
        $xml = simplexml_load_file($xmlPath);
        $encontrado = false;
        foreach($xml->usuario as $u){
            if((string)$u->nombre === $usuario && (string)$u->contraseña === $clave){
            $_SESSION['usuario'] = (string)$u->nombre;
            $_SESSION['rol'] = (string)$u->tipo;
            $encontrado = true;
            break;
            }
        }
    }
    if($encontrado) {
        header("Location:index.php");
        exit;
    }else {
        $error = "Usuario o contraseña erroneo";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login XML</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2>Iniciar sesión</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="post" class="bg-white p-4 rounded shadow">
        <div class="mb-3">
            <label>Usuario</label>
            <input type="text" name="usuario" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Contraseña</label>
            <input type="password" name="clave" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Entrar</button>
    </form>
</div>
</body>
</html>
