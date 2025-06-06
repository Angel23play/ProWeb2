<?php
require_once "../personajes.php";
require_once "../obra.php";


$codigo_obra = $_GET['codigo_obra'];



$obra = Obra::ObtenerporCodigo($codigo_obra);





if (isset($_GET['accion']) && $_GET['accion'] === 'editar' && isset($_GET['cedula'])) {
    $cedula = $_GET['cedula'];
    $codigo = $_GET['codigo_obra'];


    foreach ($obra->personaje as $personaje) {
        if ($personaje['cedula'] === $cedula) {
            $personaje_editar = new Personaje($personaje);
            break;
        }
    }
}



if (isset($_POST['accion'])) {
    if ($_POST['accion'] === 'crear') {


        //Aqui recibe los datos mediante el post y hace la funcion guardar
        $personaje = new Personaje($_POST);

        if (Personaje::guardar($personaje, $codigo_obra)) {
            header("Location: personajes.php?codigo_obra={$codigo_obra}");
            exit();
        } else {
            $error = "El código ya existe.";
        }

    } elseif ($_POST['accion'] === 'editar') {

        $personaje_editar = new Personaje($_POST);


        //Aqui recibe los datos mediante el post y hace la accion editar
        if (Personaje::editar($codigo_obra, $personaje_editar)) {
            header("Location: personajes.php?codigo_obra={$codigo_obra}");
            exit;
        } else {
            $error = "No se encontró la obra para editar.";

            echo "{$error}";
        }
    }
}

if (isset($_GET['accion']) && $_GET['accion'] === 'eliminar' && isset($_GET['cedula'])) {
    $cedula = $_GET['cedula'];
    //Esto elimina y da una validacion
    if (Personaje::borrar($codigo_obra, $cedula)) {
        echo "<script>
        setTimeout(function() {
            window.location.href = 'personajes.php?codigo_obra={$codigo_obra}';
        }, 1000); // espera 2 segundos antes de redirigir
    </script><p class='text-success fw-bold mt-2'>Este Personaje se elimino correctamente!</p>";
    }
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
    <?php include("./Components/presentation.php");?>
    <h2 class="text-secondary text-center">Listado de Personajes </h2>
    <div class="my-5 d-flex align-items-center justify-content-between ">
        <div class="mb-6">
            <a href="../index.php" class="btn btn-sm btn-info mb-3">Volver</a>
        </div>
        <div>
            <a href="?accion=nueva&codigo_obra=<?php echo $codigo_obra ?>" class="btn btn-primary">Crear personaje</a>
        </div>
    </div>

    <?php if (isset($_GET['accion']) && $_GET['accion'] === "nueva"): ?>
        <form method="post" action="" class="mb-4 border border-success p-3">
            <input type="hidden" name="accion" value="crear">
            <div class="row mb-2">
                <div class="col">
                    <label for="cedula">Inserte la cedula</label>
                    <input type="text" name="cedula" class="form-control" placeholder="Cédula" required value="">
                </div>
                <div class="col">
                    <label for="foto_url">Inserte la foto</label>
                    <input type="text" name="foto_url" class="form-control" placeholder="URL de Foto" required value="">
                </div>
                <div class="col">
                    <label for="nombre">Inserte el Nombre</label>
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre" required value="">
                </div>
                <div class="col">
                    <label for="apellido">Inserte el apellido</label>
                    <input type="text" name="apellido" class="form-control" placeholder="Apellido" required value="">
                </div>
            </div>
            <div class="row mb-2">
                <div class="col">
                    <label for="fecha_nacimiento">Inserte su cumpleaños</label>
                    <input type="date" name="fecha_nacimiento" class="form-control" required value="">
                </div>
                <div class="col">
                    <label for="sexo">Seleccione su sexo</label>
                    <select name="sexo" class="form-control" required>
                        <option value="">Seleccione sexo</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                    </select>
                </div>
                <div class="col">
                    <label for="habilidades">Inserte sus Habilidades</label>
                    <input type="text" name="habilidades" class="form-control"
                        placeholder="Habilidades (separadas por coma)" required value="">
                </div>
                <div class="col">
                    <label for="comida_favorita">Inserte su comida favorita</label>
                    <input type="text" name="comida_favorita" class="form-control" placeholder="Comida Favorita" required
                        value="">
                </div>
            </div>
            <button class="btn btn-success ?>" type="submit">
                Guardar Personaje
            </button>
            <a href="personajes.php?codigo_obra=<?php echo $codigo_obra ?>" class="btn btn-danger">Cancelar</a>

        </form>
    <?php elseif (isset($_GET['accion']) && $_GET['accion'] === 'editar'): ?>
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">Editar Obra:
                <?php echo htmlspecialchars($personaje_editar->cedula) ?>
            </div>
            <div class="card-body">
                <form method="POST" action="" class=" mb-4 border border-warning p-3">
                    <input type="hidden" name="accion" value="editar">
                    <input type="hidden" name="cedula" value="<?= htmlspecialchars($personaje_editar->cedula) ?>">
                    <div class="row mb-2">
                        <div class="col">
                            <label for="foto_url">Inserte la foto</label>
                            <input type="text" name="foto_url" class="form-control" placeholder="URL de Foto"
                                value="<?= htmlspecialchars($personaje_editar->foto_url) ?>">
                        </div>
                        <div class="col">
                            <label for="nombre">Inserte el Nombre</label>
                            <input type="text" name="nombre" class="form-control" placeholder="Nombre"
                                value="<?= htmlspecialchars($personaje_editar->nombre) ?>">
                        </div>
                        <div class="col">
                            <label for="apellido">Inserte el apellido</label>
                            <input type="text" name="apellido" class="form-control" placeholder="Apellido"
                                value="<?= htmlspecialchars($personaje_editar->apellido) ?>">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <label for="fecha_nacimiento">Inserte su cumpleaños</label>
                            <input type="date" name="fecha_nacimiento" class="form-control"
                                value="<?= htmlspecialchars($personaje_editar->fecha_nacimiento) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="sexo">Seleccione su sexo</label>
                            <select name="sexo" class="form-control">
                                <option value="">Seleccione sexo</option>
                                <option value="Masculino" <?= $personaje_editar->sexo === 'Masculino' ? 'selected' : '' ?>>
                                    Masculino</option>
                                <option value="Femenino" <?= $personaje_editar->sexo === 'Femenino' ? 'selected' : '' ?>>
                                    Femenino
                                </option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="habilidades">Inserte sus Habilidades</label>
                            <input type="text" name="habilidades" class="form-control"
                                placeholder="Habilidades (separadas por coma)"
                                value="<?= htmlspecialchars($personaje_editar->habilidades) ?>">
                        </div>
                        <div class="col">
                            <label for="comida_favorita">Inserte su comida favorita</label>
                            <input type="text" name="comida_favorita" class="form-control" placeholder="Comida Favorita"
                                value="<?= htmlspecialchars($personaje_editar->comida_favorita) ?>">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning">Actualizar Obra</button>
                    <a href="personajes.php?codigo_obra=<?php echo $codigo_obra ?>" class="btn btn-danger">Cancelar</a>
                </form>
            </div>
        </div>

    <?php endif; ?>



    <div class="d-flex flex-row justify-content-between">

        <div class="mb-50 mt-50 p-5 border border-dark  ">
            <h2>Detalles de la Obra</h2> <br>
            <h5>
                Nombre de la Obra: <?php echo $obra->nombre ?>
            </h5>
            <p>
                Pais: <?php echo $obra->pais ?>

            </p>
            <p>

                Numero de personajes de la Obra: <?php echo sizeof($obra->personaje) ?>
            </p>
            <p>

                Foto:  
            </p>
                <img src=<?php echo $obra->foto_url ?> height="200" alt="">
            <p>

                Tipo de Obra: <?php echo $obra->tipo ?>
            </p>


        </div>
        <div class="mb-50 mt-50 p-5 border border-dark">
            <table class="table table-bordered table-striped table-hover align-middle ">
                <thead class="table-dark">
                    <tr>
                        <th>Foto</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Fecha Nac.</th>
                        <th>Sexo</th>
                        <th>Habilidades</th>
                        <th>Comida Favorita</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody class="border border-dark">
                    <?php foreach ($obra->personaje as $personaje) {
                        echo "
                        <tr>
                            <td><img src='{$personaje['foto_url']}' width='300' height ='300'></td>
                            <td>{$personaje['nombre']}</td>
                            <td>{$personaje['apellido']}</td>
                            <td>{$personaje['fecha_nacimiento']}</td>
                            <td>{$personaje['sexo']}</td>
                            <td>{$personaje['habilidades']}</td>
                            <td>{$personaje['comida_favorita']}</td>
                            <td>
                                <a href='?codigo_obra={$codigo_obra}&accion=editar&cedula={$personaje['cedula']}'
                                    class='btn btn-sm btn-warning'>Editar</a>
                                <a href='?codigo_obra={$codigo_obra}&accion=eliminar&cedula={$personaje['cedula']} '
                                    class='btn btn-sm btn-danger'
                                    onclick='return confirm('¿Eliminar este personaje?')'>Eliminar</a>
                            </td>
                        </tr>
                        ";

                        if ($obra->personaje == null) {
                            echo "Esto no tiene nada";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php include('./Components/footer.php');?>
</body>

</html>