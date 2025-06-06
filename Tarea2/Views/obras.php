<?php

require_once "obra.php";


// --- PROCESAR ACCIONES ---
$ListadoObra = Obra::cargar();

if (isset($_GET['accion']) && $_GET['accion'] === 'editar' && isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];
    $obras = Obra::cargar();

    foreach ($obras as $obra) {
        if ($obra['codigo'] === $codigo) {
            $obraEditar = new Obra($obra);
            break;
        }
    }
}




if (isset($_POST['accion'])) {
    if ($_POST['accion'] === 'crear') {

        //Aqui recibe los datos mediante el post
        $nuevaObra = new Obra($_POST);

        if (Obra::guardar($nuevaObra)) {
            header("Location: index.php");
            exit();
        } else {
            $error = "El código ya existe.";
        }

    } elseif ($_POST['accion'] === 'editar') {
        $obraEditar = new Obra($_POST);
        var_dump($obraEditar);
        //Aqui recibe los datos mediante el post
        if (Obra::editar($obraEditar)) {
            header("Location: index.php");
            exit;
        } else {
            $error = "No se encontró la obra para editar.";
            echo $error;
        }
    }
}

if (isset($_GET['accion']) && $_GET['accion'] === 'eliminar' && isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];
    //Esto elimina y da una validacion
    if (Obra::borrar($codigo)) {
        echo  "<script>
        setTimeout(function() {
            window.location.href = 'index.php';
        }, 2000); // espera 2 segundos antes de redirigir
    </script><p class='text-success fw-bold mt-2'>Esta Obra se elimino correctamente!</p>";
    }
    header('index.php');
    exit();
}

?>
    <h2 class="text-secondary text-center">Listado de Obras</h2>
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">📚 Obras Registradas</h2>
        <a href="?accion=nueva" class="btn btn-success">
            <img src="./Views/Components/add-file-8-svgrepo-com.svg" width="30" height="30" alt="add"> Añadir Obra
        </a>
    </div>

    <?php if (isset($_GET['accion']) && $_GET['accion'] === 'nueva'): ?>
        <div class="card mb-4">
            <div class="card-header bg-success text-white">Nueva Obra</div>
            <div class="card-body">
                <form method="POST" action="">
                    <input type="hidden" name="accion" value="crear">

                    <div class="mb-3">
                        <label for="codigo" class="form-label">Código</label>
                        <input type="text" class="form-control" id="codigo" name="codigo" required>
                    </div>
                    <div class="mb-3">
                        <label for="foto_url" class="form-label">URL o ruta de la foto</label>
                        <input type="text" class="form-control" id="foto_url" name="foto_url">
                    </div>
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo</label>
                        <select id="tipo" name="tipo" class="form-select" required>
                            <option value="">Selecciona</option>
                            <option value="Serie">Serie</option>
                            <option value="Película">Película</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="pais" class="form-label">País</label>
                        <input type="text" class="form-control" id="pais" name="pais">
                    </div>
                    <div class="mb-3">
                        <label for="autor" class="form-label">Autor</label>
                        <input type="text" class="form-control" id="autor" name="autor" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar Obra</button>
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    <?php elseif (isset($_GET['accion']) && $_GET['accion'] === 'editar'): ?>
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">Editar Obra: <?php echo htmlspecialchars($obraEditar->codigo) ?>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <input type="hidden" name="accion" value="editar">
                    <input type="hidden" name="codigo" value="<?= htmlspecialchars($obraEditar->codigo) ?>">

                    <div class="mb-3">
                        <label for="foto_url" class="form-label">URL o ruta de la foto</label>
                        <input type="text" class="form-control" id="foto_url" name="foto_url"
                            value="<?= htmlspecialchars($obraEditar->foto_url) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo</label>
                        <select id="tipo" name="tipo" class="form-select" required>
                            <option value="">Selecciona</option>
                            <option value="Serie" <?= $obraEditar->tipo === 'Serie' ? 'selected' : '' ?>>Serie</option>
                            <option value="Película" <?= $obraEditar->tipo === 'Película' ? 'selected' : '' ?>>Película
                            </option>
                            <option value="Otro" <?= $obraEditar->tipo === 'Otro' ? 'selected' : '' ?>>Otro</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre"
                            value="<?= htmlspecialchars($obraEditar->nombre) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion"
                            rows="3"><?= htmlspecialchars($obraEditar->descripcion) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="pais" class="form-label">País</label>
                        <input type="text" class="form-control" id="pais" name="pais"
                            value="<?= htmlspecialchars($obraEditar->pais) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="autor" class="form-label">Autor</label>
                        <input type="text" class="form-control" id="autor" name="autor"
                            value="<?= htmlspecialchars($obraEditar->autor) ?>" required>
                    </div>

                    <button type="submit" class="btn btn-warning">Actualizar Obra</button>
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Código</th>
                    <th>Foto</th>
                    <th>Tipo</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>País</th>
                    <th>Autor</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ListadoObra as $obra): ?>
                    <tr>
                        <td><?= htmlspecialchars($obra['codigo']) ?></td>
                        <td>
                            <?php if (!empty($obra['foto_url'])): ?>
                                <img src="<?= htmlspecialchars($obra['foto_url']) ?>" alt="Foto" width="300" height="250">
                            <?php else: ?>
                                Sin imagen
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($obra['tipo']) ?></td>
                        <td><?= htmlspecialchars($obra['nombre']) ?></td>
                        <td><?= nl2br(htmlspecialchars($obra['descripcion'])) ?></td>
                        <td><?= htmlspecialchars($obra['pais']) ?></td>
                        <td><?= htmlspecialchars($obra['autor']) ?></td>
                        <td>
                            <a href="?accion=editar&codigo=<?= urlencode($obra['codigo']) ?>"
                                class="btn btn-sm btn-warning mb-1">Editar</a>
                            <a href="?accion=eliminar&codigo=<?= urlencode($obra['codigo']) ?>"
                                class="btn btn-sm btn-danger mb-1"
                                onclick="return confirm('¿Eliminar obra y sus personajes relacionados?')">Eliminar</a>
                            <a href="Views/personajes.php?codigo_obra=<?= urlencode($obra['codigo']) ?>"
                                class="btn btn-sm btn-info">Personajes</a>
                        </td>

                    </tr>
                <?php endforeach; ?>
                <?php if (count($ListadoObra) === 0): ?>
                    <tr>
                        <td colspan="8" class="text-center">No hay obras registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>