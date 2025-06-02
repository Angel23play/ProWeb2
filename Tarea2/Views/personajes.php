<?php
$archivo = 'Data/personajes/' . $_GET{'codigo'};
$personajes = file_exists($archivo) ? json_decode(file_get_contents($archivo), true) : [];

$codigo_obra = $_GET['codigo_obra'] ?? null;
if (!$codigo_obra) {
    die("Código de obra no especificado.");
}

$accion = $_GET['accion'] ?? null;
$personaje_editando = null;

if ($_POST) {
    $cedula = $_POST['cedula'];
    $nuevo = new Personaje(
        $cedula,
        $_POST['foto_url'],
        $_POST['nombre'],
        $_POST['apellido'],
        $_POST['fecha_nacimiento'],
        $_POST['sexo'],
        $_POST['habilidades'],
        $_POST['comida_favorita'],
        $codigo_obra
    );

    $personajes[$cedula] = (array)$nuevo;
    file_put_contents($archivo, json_encode($personajes, JSON_PRETTY_PRINT));
    header("Location: personajes.php?codigo_obra=$codigo_obra");
    exit;
} elseif ($accion === 'eliminar') {
    $cedula = $_GET['cedula'];
    unset($personajes[$cedula]);
    file_put_contents($archivo, json_encode($personajes, JSON_PRETTY_PRINT));
    header("Location: personajes.php?codigo_obra=$codigo_obra");
    exit;
} elseif ($accion === 'editar') {
    $cedula = $_GET['cedula'];
    $personaje_editando = $personajes[$cedula] ?? null;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Personajes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <h2>Personajes de la Obra <?= htmlspecialchars($codigo_obra) ?></h2>
    <a href="index.php" class="btn btn-secondary mb-3">Volver</a>

    <form method="post" class="mb-4">
        <div class="row mb-2">
            <div class="col">
                <input type="text" name="cedula" class="form-control" placeholder="Cédula" required
                    value="<?= $personaje_editando['cedula'] ?? '' ?>" <?= $personaje_editando ? 'readonly' : '' ?>>
            </div>
            <div class="col">
                <input type="text" name="foto_url" class="form-control" placeholder="URL de Foto" required
                    value="<?= $personaje_editando['foto_url'] ?? '' ?>">
            </div>
            <div class="col">
                <input type="text" name="nombre" class="form-control" placeholder="Nombre" required
                    value="<?= $personaje_editando['nombre'] ?? '' ?>">
            </div>
            <div class="col">
                <input type="text" name="apellido" class="form-control" placeholder="Apellido" required
                    value="<?= $personaje_editando['apellido'] ?? '' ?>">
            </div>
        </div>
        <div class="row mb-2">
            <div class="col">
                <input type="date" name="fecha_nacimiento" class="form-control" required
                    value="<?= $personaje_editando['fecha_nacimiento'] ?? '' ?>">
            </div>
            <div class="col">
                <select name="sexo" class="form-control" required>
                    <option value="">Seleccione sexo</option>
                    <option value="Masculino" <?= (isset($personaje_editando['sexo']) && $personaje_editando['sexo'] === 'Masculino') ? 'selected' : '' ?>>Masculino</option>
                    <option value="Femenino" <?= (isset($personaje_editando['sexo']) && $personaje_editando['sexo'] === 'Femenino') ? 'selected' : '' ?>>Femenino</option>
                </select>
            </div>
            <div class="col">
                <input type="text" name="habilidades" class="form-control" placeholder="Habilidades (separadas por coma)" required
                    value="<?= $personaje_editando['habilidades'] ?? '' ?>">
            </div>
            <div class="col">
                <input type="text" name="comida_favorita" class="form-control" placeholder="Comida Favorita" required
                    value="<?= $personaje_editando['comida_favorita'] ?? '' ?>">
            </div>
        </div>
        <button class="btn btn-<?= $personaje_editando ? 'warning' : 'success' ?>" type="submit">
            <?= $personaje_editando ? 'Actualizar Personaje' : 'Guardar Personaje' ?>
        </button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Foto</th>
                <th>Nombre</th>
                <th>Fecha Nac.</th>
                <th>Sexo</th>
                <th>Habilidades</th>
                <th>Comida Favorita</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($personajes as $personaje): ?>
                <?php if ($personaje['codigo_obra'] === $codigo_obra): ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($personaje['foto_url']) ?>" width="50"></td>
                        <td><?= htmlspecialchars($personaje['nombre']) . ' ' . htmlspecialchars($personaje['apellido']) ?></td>
                        <td><?= htmlspecialchars($personaje['fecha_nacimiento']) ?></td>
                        <td><?= htmlspecialchars($personaje['sexo']) ?></td>
                        <td><?= htmlspecialchars($personaje['habilidades']) ?></td>
                        <td><?= htmlspecialchars($personaje['comida_favorita']) ?></td>
                        <td>
                            <a href="?codigo_obra=<?= urlencode($codigo_obra) ?>&accion=editar&cedula=<?= urlencode($personaje['cedula']) ?>" class="btn btn-sm btn-warning">Editar</a>
                            <a href="?codigo_obra=<?= urlencode($codigo_obra) ?>&accion=eliminar&cedula=<?= urlencode($personaje['cedula']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este personaje?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
