<?php
    session_start(); 	

    if (!isset($_SESSION["cedula"]) || !isset($_SESSION["nombres"])) {
        header("Location: index.php");
        exit();
    }

    $permiso1 = "Admin";
    $permiso2 = "Verificacion";

    if (!(strpos($_SESSION['permisos'], $permiso1) || strpos($_SESSION['permisos'], $permiso2))) {
        header("Location: dashboard.php");
        exit();
    }

    require_once 'controladores/Connection.php';

    $con = Connection::getInstance()->getConnection();
    $querF = $con->query(
        "SELECT 
            F.*, 
            U.Nombres, 
            U.Apellidos, 
            CONCAT(
                DATE_FORMAT(F.FinAlistamiento, '%Y-%m-%d'), 
                ' ', 
                TIME_FORMAT(F.FinAlistamiento, '%h:%i %p')
            ) AS fecha_y_hora
        FROM 
            Facturas AS F, Usuarios AS U 
        WHERE 
            F.idAlistador = U.idUsuarios 
            AND (F.facEstado = 3 OR F.facEstado = 4)
        ;
    ");

?>
<!doctype html>
<html lang="es" data-bs-theme="auto">
    <head>
        <?php
            include('partes/head.php')
        ?>
        <link rel="stylesheet" href="css_individuales/alistamiento.css">
        <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
    </head>
    <body>
        <div class="layout has-sidebar fixed-sidebar fixed-header">
            <?php
                $activado = "Verificacion";
                include('partes/sidebar.php')
            ?>  
            <div id="overlay" class="overlay"></div>
            <div class="layout">
                <main class="content">
                    <div id = "contenidoMovil" style="display: none;">
                        <br>
                        <a href = "dashboard.php" ><button class="btn btn-primary primeButton" type="button">Volver</button></a>
                        <br>
                        <br>
                        <table>
                            <thead>
                                <tr>
                                    <th>#Factura</th>
                                    <th>Fecha y Hora Venta</th>
                                    <th>Nombre Cliente</th>
                                    <th>Razón Social</th>
                                    <th>Ciudad</th>
                                    <th>Vendedor</th>
                                    <th>Alistador</th>
                                    <th>Fecha y Hora Alistado</th>
                                    <th>Procesar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    while ($verificacion = $querF->fetch_assoc()) {
                                    $filaClase = $verificacion['Forzado'] == '1' ? 'fila-amarilla' : ''; // Determinar la clase de la fila
                                ?>
                                    <tr class="<?php echo $filaClase; ?>">
                                        <td><?php echo $verificacion['PrfId'] . " " . $verificacion['VtaNum'] ?></td>
                                        <td><?php echo $verificacion['vtafec'] . " " . $verificacion['vtahor'] ?></td>
                                        <td><?php echo utf8_encode($verificacion['TerNom']) ?></td>
                                        <td><?php echo utf8_encode($verificacion['TerRaz']) ?></td>
                                        <td><?php echo utf8_encode($verificacion['CiuNom']) ?></td>
                                        <td><?php echo utf8_encode($verificacion['VenNom']) ?></td>
                                        <td><?php echo utf8_encode($verificacion['Nombres']) . " " . utf8_encode($verificacion['Apellidos']) ?></td>
                                        <td><?php echo $verificacion['fecha_y_hora'] ?></td>
                                        <td>
                                            <?php 
                                                echo "<a href='verificacion.php?id=" . $verificacion['vtaid'] . "' class='btn btn-primary'>Procesar</a>";
                                            ?>
                                        </td>
                                    </tr>
                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>     
                    </div>
                    <div class="table" id = "contenidoEscritorio" style="display: none;">
                        <br>
                        <br>
                        <table id="tablaVerificacion">
                        <thead>
                                <tr>
                                    <th>#Factura</th>
                                    <th>Fecha y Hora Venta</th>
                                    <th>Nombre Cliente</th>
                                    <th>Razón Social</th>
                                    <th>Ciudad</th>
                                    <th>Vendedor</th>
                                    <th>Alistador</th>
                                    <th>Fecha y Hora Alistadoo</th>
                                    <th>Procesar</th>
                                </tr>
                            </thead>
                        </table>     
                    </div>  
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

                var miTabla = $('#tablaVerificacion').DataTable({
                    destroy: true,
                    responsive: true,
                    processing: true,
                    pageLength: 10,
                    ajax: {
                        url: 'core/tabla_index_verificacion.php',
                        type: 'GET',
                    },
                    language: {
                        lengthMenu: '',
                        search: 'Buscar',
                        zeroRecords: 'Ningún Resultado',
                        emptyTable: "Ningún dato disponible en esta tabla",
                        info: 'De _START_ A _END_ De Un Total De _TOTAL_',
                        infoEmpty: 'Ningún Resultado',
                        infoFiltered: '(Filtrando _MAX_ En Total)',
                        loadingRecords: 'Cargando',
                        paginate: {
                            first: 'Primero',
                            last: 'Último',
                            next: 'Siguiente',
                            previous: 'Anterior'
                        },
                    },
                    columns: [
                        {data: 'id', name:'id', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'fecha', name:'fecha', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'nombre', name:'nombre', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'razon', name:'razon', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'ciudad', name:'ciudad', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'vendedor', name:'vendedor', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'alistador', name:'alistador', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'horaAlistado', name:'horaAlistado', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'accion', name:'accion', orderable: true, searchable: true, className: 'dt-body-center'}
                    ],

                });

            });
        </script> 
    </body>
</html>