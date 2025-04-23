<?php
    session_start(); 	

    if (!isset($_SESSION["cedula"]) || !isset($_SESSION["nombres"])) {
        header("Location: index.php");
        exit();
    }

    $permiso1 = "Admin";

    if (!(strpos($_SESSION['permisos'], $permiso1))) {
        header("Location: dashboard.php");
        exit();
    }

    require_once 'controladores/Connection.php';

?>
<!doctype html>
<html lang="es" data-bs-theme="auto">
    <head>
        <?php
            include('partes/head.php')
        ?>
        <style>
            .select option {
                background-color: #f2f2f2 !important;
                color: #333 !important;
                padding: 5px !important;
            }
        </style>
        <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
    </head>
    <body>
        <div class="layout has-sidebar fixed-sidebar fixed-header">
            <?php
                $activado = "borrarEvidencia";
                include('partes/sidebar.php')
            ?>  
            <div id="overlay" class="overlay"></div>
            <div class="layout">
                <main class="content">
                    <br>
                    <h2>Borrar Evidencia</h2>
                    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                        <div style="width: 45%;">
                            <label><strong>Fecha inicio</strong></label>
                            <input type="date" id="fechaInicio" name="fechaInicio">
                        </div>
                        <div style="width: 45%;">
                            <label><strong>Fecha fin</strong></label>
                            <input type="date" id="fechaFin" name="fechaFin">
                        </div>
                    </div>
                    <br>
                    <button type="button" class="btn btn-danger" id = "botonBorrarEvidencia">Borrar Evidencia</button>
                </main>
            </div>
        </div>
        <?php
            include('partes/foot.php')
        ?>  
        <!-- Incluye la biblioteca jQuery -->
        <script src="js/jquery-3.6.0.min.js"></script>
        <!-- Incluye la biblioteca DataTables -->
        <script type="text/javascript" charset="utf8" src="js/jquery.dataTables.js"></script>

        <script>

            $(document).ready(function () {
                $('#botonBorrarEvidencia').on('click', function () {
                    const fechaInicio = $('#fechaInicio').val();
                    const fechaFin = $('#fechaFin').val();

                    if (!fechaInicio || !fechaFin) {
                        alert("Debe seleccionar ambas fechas.");
                        return;
                    }

                    if (fechaFin < fechaInicio) {
                        alert("La fecha fin no puede ser anterior a la fecha inicio.");
                        return;
                    }

                    if (!confirm("¿ESTÁ ABSOLUTAMENTE SEGURO que desea borrar la evidencia?")) {
                        return;
                    }

                    $.post('controladores/borrarEvidencia.php', {
                        fechaInicio: fechaInicio,
                        fechaFin: fechaFin
                    }, function (response) {
                        if (response.exito) {
                            alert(response.mensaje);
                        } else {
                            alert("Error: " + response.mensaje);
                        }
                    }, 'json').fail(function () {
                        alert("Error en la comunicación con el servidor.");
                    });
                });
            });

        </script>
    </body>
</html>