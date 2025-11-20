<?php
    session_start(); 	

    if (!isset($_SESSION["cedula"]) || !isset($_SESSION["nombres"])) {
        header("Location: index.php");
        exit();
    }

    $permiso1 = "Admin";
    $permiso2 = "Alistamiento";

    if (!(strpos($_SESSION['permisos'], $permiso1) || strpos($_SESSION['permisos'], $permiso2))) {
        header("Location: dashboard.php");
        exit();
    }

    require_once 'controladores/Connection.php';

    $limite = 0;
    if (isset($_GET['limit']) && is_numeric($_GET['limit'])) {
        $limite = intval($_GET['limit']);
    }

    $con = Connection::getInstance()->getConnection();

    $consulta = "SELECT * FROM Facturas WHERE (facEstado = 1 OR facEstado = 2) AND Forzado = 0 ";

    $consultaCount = "SELECT COUNT(*) AS total FROM Facturas WHERE (facEstado = 1 OR facEstado = 2) AND Forzado = 0 ";

    $busqueda = '';

    if (isset($_GET['busqueda']) && is_numeric($_GET['busqueda'])) {
        $busqueda = $_GET['busqueda'];
        
        $consulta .= "AND VtaNum LIKE '%" . $busqueda ."%'";
        $consultaCount .= "AND VtaNum LIKE '%" . $busqueda ."%'";
    }


    $consulta .= "ORDER BY vtafec ASC, vtahor ASC LIMIT " . (($limite) * 5) .", 5;";

    $querF = $con->query($consulta);

    $resultado = $con->query($consultaCount);

    $totalRegistros = 0;

    if ($fila = $resultado->fetch_assoc()) {
        $totalRegistros = $fila['total'];
    }


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
                $activado = "Alistamiento";
                include('partes/sidebar.php')
            ?>  
            <div id="overlay" class="overlay"></div>
            <div class="layout">
                <main class="content">
                    <div id = "contenidoMovil" style="display: none;">
                        <br>
                        <a href="dashboard.php"><button class="btn btn-primary primeButton" type="button">Volver</button></a>
                        <br>

                        <?php if ($limite != 0): ?>
                            <a href="lista_alistamiento.php?busqueda=<?php echo $busqueda; ?>&limit=<?php echo $limite - 1; ?>">
                                <button class="btn btn-warning primeButton" type="button">Página anterior</button>
                            </a>
                        <?php endif; ?>

                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                        <?php if ((1+$limite)*5 < $totalRegistros) : ?>
                            <a href="lista_alistamiento.php?busqueda=<?php echo $busqueda; ?>&limit=<?php echo $limite + 1; ?>">
                                <button class="btn btn-success primeButton" type="button">Página siguiente</button>
                            </a>
                        <?php endif; ?>

                        <br><br>

                        <!-- Formulario de búsqueda por número de página -->
                        <form action="lista_alistamiento.php" method="get" style="display: inline-block;">
                            <input type="number" name="busqueda" id="busqueda" class="form-control d-inline" style="width: 170px;" min="0" value = <?php echo $busqueda; ?>>
                            <input type="hidden" name="limit" id="limit" value = <?php echo $limite; ?>>
                            <button type="submit" class="btn btn-info primeButton">Buscar</button>
                        </form>
                        <br>
                        <table>
                            <thead>
                                <tr>
                                    <th>#Factura</th>
                                    <th>Fecha</th>
                                    <th>Nombre Cliente</th>
                                    <!-- <th>Razón Social</th> -->
                                    <th>Ciudad</th>
                                    <th>Vendedor</th>
                                    <th>Hora Doc</th>
                                    <th>Observacion</th>
                                    <th>Procesar</th>
                                    <th>No Procesar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    while ($alistamiento = $querF->fetch_assoc()) {
                                    $filaClase = $alistamiento['facEstado'] == '2' ? 'fila-verde' : ''; // Determinar la clase de la fila
                                ?>
                                    <tr class="<?php echo $filaClase; ?>">
                                        <td><?php echo $alistamiento['PrfCod'] . " " . $alistamiento['VtaNum'] ?></td>
                                        <td><?php echo $alistamiento['vtafec'] ?></td>
                                        <td><?php echo utf8_encode($alistamiento['TerNom']) ?></td>
                                        <!-- <td><?php echo utf8_encode($alistamiento['TerRaz']) ?></td> -->
                                        <td><?php echo utf8_encode($alistamiento['CiuNom']) ?></td>
                                        <td><?php echo utf8_encode($alistamiento['VenNom']) ?></td>
                                        <td><?php echo $alistamiento['vtahor'] ?></td>
                                        <td><?php echo $alistamiento['facObservaciones'] ?></td>
                                        <td>
                                            <?php 
                                                echo "<a href='alistamiento.php?id=" . $alistamiento['vtaid'] . "' class='btn btn-primary'>Procesar</a>";
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                echo "<a href='noPRocesar.php?id=" . $alistamiento['vtaid'] . "' class='btn btn-danger'>X</a>";
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
                        <table id="tablaAlistamiento">
                            <thead>
                                <tr>
                                    <th>#Factura</th>
                                    <th>Fecha</th>
                                    <th>Nombre Cliente</th>
                                    <!-- <th>Razón Social</th> -->
                                    <th>Ciudad</th>
                                    <th>Vendedor</th>
                                    <th>Hora Doc</th>
                                    <th>Observacion</th>
                                    <th>Procesar</th>
                                    <th>No Procesar</th>
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

                var miTabla = $('#tablaAlistamiento').DataTable({
                    destroy: true,
                    responsive: true,
                    processing: true,
                    pageLength: 10,
                    ordering: false,
                    ajax: {
                        url: 'core/tabla_index_alistamiento.php',
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
                        // {data: 'razon', name:'razon', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'ciudad', name:'ciudad', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'vendedor', name:'vendedor', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'hora', name:'hora', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'observacion', name:'observacion', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'accion', name:'accion', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'segundaAccion', name:'segundaAccion', orderable: true, searchable: true, className: 'dt-body-center'}
                        
                    ],

                });


                $('.btnMostrar').on('click', function() {
                    var id = $(this).closest('tr').find('td[data-id]').data('id');
                    console.log('Valor de data-id:', id);
                    window.location.href = 'detalle_usuario.php';
                });

                // $('#recargarBtn').click(function() {
                //     miTabla.ajax.reload();
                // });

            });
        </script> 
    </body>
</html>