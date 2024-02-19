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

    $consulta = "SELECT * FROM Facturas WHERE facEstado = 1 OR facEstado = 2 ORDER BY vtafec ASC, vtahor ASC";

    $consulta .= " LIMIT " . (($limite) * 5) .", 5;";

    $querF = $con->query($consulta);

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
                        <a href = "dashboard.php" ><button class="btn btn-primary primeButton" type="button">Volver</button></a>
                        <br>
                        <?php
                            if ($limite!=0){
                        ?>
                        <a href = "lista_alistamiento.php?limit=<?php echo $limite-1; ?> " ><button class="btn btn-warning primeButton" type="button">Pagina anterior</button></a>
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        <?php
                            }
                        ?>
                        <a href = "lista_alistamiento.php?limit=<?php echo $limite+1; ?>" ><button class="btn btn-success primeButton" type="button">Pagina siguiente</button></a>
                        <br>
                        <table>
                            <thead>
                                <tr>
                                    <th>#Factura</th>
                                    <th>Fecha</th>
                                    <th>Nombre Cliente</th>
                                    <th>Razón Social</th>
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
                                        <td><?php echo utf8_encode($alistamiento['TerRaz']) ?></td>
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
                                    <th>Razón Social</th>
                                    <th>Ciudad</th>
                                    <th>Vendedor</th>
                                    <th>Hora Doc</th>
                                    <th>Observacion</th>
                                    <th>Procesar</th>
                                    <th>No Procesar</th>
                                </tr>
                            </thead>
                        </table>    
                        <!-- <button id="recargarBtn">Recargar DataTable</button> -->
 
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
                        {data: 'razon', name:'razon', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'ciudad', name:'ciudad', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'vendedor', name:'vendedor', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'hora', name:'hora', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'observacion', observacion:'password', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'accion', observacion:'accion', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'segundaAccion', observacion:'segundaAccion', orderable: true, searchable: true, className: 'dt-body-center'}
                        
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