<?php
session_start();
if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2 || $_SESSION['rol'] == 3) {
    include "../conexion.php";
    if (!empty($_POST)) {
        $alert = "";
        $id = $_POST['id'];
        $mensaje = $_POST['mensaje'];
        $posdata = $_POST['postada'];
        $foto_actual = $_POST['foto_actual'];

        $fecha = date('YmdHis');
        if (empty($mensaje) || empty($posdata) < 0) {
            $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Todo los campos son obligatorios
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
        } else {
            $nombre = null;
            if (!empty($foto['name'])) {
                $nombre = '../assets/img/platos/' . $fecha . '.jpg';
            } else if (!empty($foto_actual) && empty($foto['name'])) {
                $nombre = $foto_actual;
            }

            if (empty($id)) {
                $query = mysqli_query($conexion, "SELECT * FROM mensajes WHERE mensaje = '$mensaje' AND estado = 1 ");
                $result = mysqli_fetch_array($query);
                if ($result > 0) {
                    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        El Mensaje ya existe
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                } else {
                    $query_insert = mysqli_query($conexion, "INSERT INTO mensajes (id,mensaje,postada) VALUES ('$id', '$mensaje', '$posdata')");
                    if ($query_insert) {
                        if (!empty($foto['name'])) {
                            move_uploaded_file($foto['tmp_name'], $nombre);
                        }
                        $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Mensaje enviado
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                    } else {
                        $alert = '<div class="alert alert-danger" role="alert">
                    Error al registrar el plato
                  </div>';
                    }
                }
            } else {
                $query_update = mysqli_query($conexion, "UPDATE mensajes SET mensaje = '$mensaje', posdata=$posdata, imagen='$nombre' WHERE id = $id");
                if ($query_update) {
                    if (!empty($foto['name'])) {
                        move_uploaded_file($foto['tmp_name'], $nombre);
                    }
                    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Plato Modificado
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                } else {
                    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Error al modificar
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                }
            }
        }
    }
    include_once "includes/header.php";
?>
    <div class="card shadow-lg">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" autocomplete="off" id="formulario" enctype="multipart/form-data">
                                <?php echo isset($alert) ? $alert : ''; ?>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="hidden" id="id" name="id">
                                            <input type="hidden" id="foto_actual" name="foto_actual">
                                            <label for="plato" class=" text-dark font-weight-bold">Mensaje</label>
                                            <input type="text" placeholder="Ingrese mensaje" name="mensaje" id="id" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="postada" class=" text-dark font-weight-bold">Posdata</label>
                                            <input type="text" placeholder="Ingrese posdata" class="form-control" name="postada" id="id">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 form-group">
                                        <label for="">Acciones</label> <br>
                                        <input type="submit" value="Registrar" class="btn btn-primary" id="btnAccion">
                                        <input type="button" value="Nuevo" onclick="limpiar()" class="btn btn-success" id="btnNuevo">
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="tbl">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Mensaje</th>
                                        <th>Posdata</th>
                                        <th>usuario</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include "../conexion.php";
                                   
                                    $sql = "SELECT nombre FROM usuarios";
                                    $result = mysqli_query($conexion, $sql);
                                    if (mysqli_num_rows($result) > 0) {
                                    
                                        $row = mysqli_fetch_assoc($result);
                                        $emisor = $row["nombre"];
                                        
                                  
                                    $query = mysqli_query($conexion, "SELECT * FROM mensajes WHERE estado = 1 ");
                                    $result = mysqli_num_rows($query);
                                    if ($result > 0) {
                                        while ($data = mysqli_fetch_assoc($query)) { ?>
                                            <tr>
                                                <td><?php echo $data['id']; ?></td>
                                                <td><?php echo $data['mensaje']; ?></td>
                                                <td><?php echo $data['postada']; ?></td>
                                                <td><?php echo "$emisor"; ?></td>
        
                                    <?php }
                                    } ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php 
include_once "includes/footer.php";
} else {
    header('Location: mensaje.php');
}
}
?>