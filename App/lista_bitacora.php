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
?>
<!doctype html>
<html lang="es" data-bs-theme="auto">
    <head>
        <?php
            include('partes/head.php')
        ?>
        <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
    </head>
    <body>
        <div class="layout has-sidebar fixed-sidebar fixed-header">
            <?php
                $activado = "Bitacora";
                include('partes/sidebar.php')
            ?>  
            <div id="overlay" class="overlay"></div>
            <div class="layout">
                <main class="content">
                    <br>
                    <h2>Bitacora</h2>
                    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                        <div style="width: 30%;">
                            <label for="campo5"><strong>Fecha inicio</strong></label>
                            <input type="date" id="campo5" name="campo5">
                        </div>
                        <div style="width: 30%;">
                            <label for="campo5"><strong>Fecha fin</strong></label>
                            <input type="date" id="campo5" name="campo5">
                        </div>
                        <div style="width: 30%;">
                            <label for="campo4"><strong>Usuario</strong></label>
                            <select id="campo4" class="select2">
                                <option value="opcion4">Todos</option>
                                <option value="opcion4">Richard</option>
                                <option value="opcion5">Jessica</option>
                                <option value="opcion6">Sharon</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <button type="button" class="btn btn-secondary">Vaciar</button>
                    <br>
                    <table id="miTabla" class="display">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Usuario</th>
                                <th>Proceso</th>
                                <th>Resultado</th>
                                <th>Factura</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>10/08/23</td>
                                <td>14:37:23</td>
                                <td>Cindy Lorena</td>
                                <td>Alistado</td>
                                <td>Cierre forzado</td>
                                <td>REF123</td>
                            </tr>
                            <tr>
                                <td>10/08/23</td>
                                <td>14:37:23</td>
                                <td>Cindy Lorena</td>
                                <td>Alistado</td>
                                <td>Cierre forzado</td>
                                <td>REF123</td>
                            </tr>
                            <tr>
                                <td>10/08/23</td>
                                <td>14:37:23</td>
                                <td>Cindy Lorena</td>
                                <td>Alistado</td>
                                <td>Cierre forzado</td>
                                <td>REF123</td>
                            </tr>
                            <tr>
                                <td>10/08/23</td>
                                <td>14:37:23</td>
                                <td>Cindy Lorena</td>
                                <td>Alistado</td>
                                <td>Cierre forzado</td>
                                <td>REF123</td>
                            </tr>
                            <tr>
                                <td>10/08/23</td>
                                <td>14:37:23</td>
                                <td>Cindy Lorena</td>
                                <td>Alistado</td>
                                <td>Cierre forzado</td>
                                <td>REF123</td>
                            </tr>
                            <tr>
                                <td>10/08/23</td>
                                <td>14:37:23</td>
                                <td>Cindy Lorena</td>
                                <td>Alistado</td>
                                <td>Cierre forzado</td>
                                <td>REF123</td>
                            </tr>
                            <tr>
                                <td>10/08/23</td>
                                <td>14:37:23</td>
                                <td>Cindy Lorena</td>
                                <td>Alistado</td>
                                <td>Cierre forzado</td>
                                <td>REF123</td>
                            </tr>
                            <tr>
                                <td>10/08/23</td>
                                <td>14:37:23</td>
                                <td>Cindy Lorena</td>
                                <td>Alistado</td>
                                <td>Cierre forzado</td>
                                <td>REF123</td>
                            </tr>
                            <tr>
                                <td>10/08/23</td>
                                <td>14:37:23</td>
                                <td>Cindy Lorena</td>
                                <td>Alistado</td>
                                <td>Cierre forzado</td>
                                <td>REF123</td>
                            </tr>
                            <tr>
                                <td>10/08/23</td>
                                <td>14:37:23</td>
                                <td>Cindy Lorena</td>
                                <td>Alistado</td>
                                <td>Cierre forzado</td>
                                <td>REF123</td>
                            </tr>
                            <tr>
                                <td>10/08/23</td>
                                <td>14:37:23</td>
                                <td>Cindy Lorena</td>
                                <td>Alistado</td>
                                <td>Cierre forzado</td>
                                <td>REF123</td>
                            </tr>
                            <tr>
                                <td>10/08/23</td>
                                <td>14:37:23</td>
                                <td>Cindy Lorena</td>
                                <td>Alistado</td>
                                <td>Cierre forzado</td>
                                <td>REF123</td>
                            </tr>
                            <tr>
                                <td>10/08/23</td>
                                <td>14:37:23</td>
                                <td>Cindy Lorena</td>
                                <td>Alistado</td>
                                <td>Cierre forzado</td>
                                <td>REF123</td>
                            </tr>
                            <tr>
                                <td>10/08/23</td>
                                <td>14:37:23</td>
                                <td>Cindy Lorena</td>
                                <td>Alistado</td>
                                <td>Cierre forzado</td>
                                <td>REF123</td>
                            </tr>
                            <tr>
                                <td>10/08/23</td>
                                <td>14:37:23</td>
                                <td>Cindy Lorena</td>
                                <td>Alistado</td>
                                <td>Cierre forzado</td>
                                <td>REF123</td>
                            </tr>
                            <tr>
                                <td>10/08/23</td>
                                <td>14:37:23</td>
                                <td>Cindy Lorena</td>
                                <td>Alistado</td>
                                <td>Cierre forzado</td>
                                <td>REF123</td>
                            </tr>
                        </tbody>
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