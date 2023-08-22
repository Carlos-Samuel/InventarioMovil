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
                        <br>
                    </div>
                    <div class="table">
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
                        {data: 'accion', observacion:'accion', orderable: true, searchable: true, className: 'dt-body-center'}

                    ],
                });


                $('.btnMostrar').on('click', function() {
                    var id = $(this).closest('tr').find('td[data-id]').data('id');
                    console.log('Valor de data-id:', id);
                    window.location.href = 'detalle_usuario.php';
                });

            });
        </script> 
    </body>
</html>