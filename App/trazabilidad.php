<?php
    session_start(); 	

    if (!isset($_SESSION["cedula"]) || !isset($_SESSION["nombres"])) {
        header("Location: index.php");
        exit();
    }

    $permiso1 = "Admin";
    $permiso2 = "Reportes";

    if (!(strpos($_SESSION['permisos'], $permiso1) || strpos($_SESSION['permisos'], $permiso2))) {
        header("Location: dashboard.php");
        exit();
    }
?>
<!doctype html>
<html lang="es" data-bs-theme="auto">
    <head>
        <?php
            include('partes/head.php')
        ?>
        <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
        <style>
        .invisible-table {
            border-collapse: collapse;
            width: 100%;
        }

        .invisible-table th, .invisible-table td {
            border: none;
            padding: 8px;
            text-align: left;
        }

        .invisible-table th:last-child,
        .invisible-table td:last-child {
            border-right: none;
        }
        </style>
    </head>
    <body>
        <div class="layout has-sidebar fixed-sidebar fixed-header">
            <?php
                $activado = "Trazabilidad";
                include('partes/sidebar.php')
            ?>  
            <div id="overlay" class="overlay"></div>
            <div class="layout">
                <main class="content">
                    <br>
                    <h2>Consulta de trazabilidad</h2>
                    <br>
                    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                        <div style="width: 45%;">
                            <label for="campo5"><strong>Prefijo</strong></label>
                            <input type="email" id="campo5" name="campo5" min="0" max="100" step="1">
                        </div>
                        <div style="width: 45%;">
                            <label for="campo5"><strong># Documento</strong></label>
                            <input type="password" id="campo5" name="campo5" min="0" max="100" step="1">
                        </div>
                    </div>
                    <br>
                    <button type="button" class="btn btn-success">Buscar</button>
                    <br>
                    <br>
                    <table class="invisible-table">
                        <tr>
                            <th>Factura - Prefijo</th>
                            <th>Nombre - Razón Social</th>
                            <th>Hora Factura</th>
                        </tr>
                        <tr>
                            <td>[Datos]</td>
                            <td>[Datos]</td>
                            <td>[Datos]</td>
                        </tr>
                        <tr>
                            <th>Vendedor</th>
                            <th>Alistador - Hora FECHA alistamiento</th>
                            <th>Duración Alistamiento</th>
                        </tr>
                        <tr>
                            <td>[Datos]</td>
                            <td>[Datos]</td>
                            <td>[Datos]</td>
                        </tr>
                        <tr>
                            <th>Verificador - Hora FECHA verificación</th>
                            <th>Duración verificación</th>
                            <th>Entregado - hora FECHA entrega</th>
                        </tr>
                        <tr>
                            <td>[Datos]</td>
                            <td>[Datos]</td>
                            <td>[Datos]</td>
                        </tr>
                        <tr>
                            <th>DURACION ENTREGA</th>
                            <th>EMBALAJE</th>
                            <th>TOtal de embalajes</th>
                        </tr>
                        <tr>
                            <td>[Datos]</td>
                            <td>[Datos]</td>
                            <td>[Datos]</td>
                        </tr>
                        <tr>
                            <th colspan="2">Estado documento</th>
                            <td>[Datos]</td>
                        </tr>
                    </table>

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
            $(document).ready( function () {
                $('#miTabla').DataTable();
            } );
        </script>
    </body>
</html>