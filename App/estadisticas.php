<?php
    session_start(); 	
    date_default_timezone_set('America/Bogota');

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
        <link rel="stylesheet" href="css_individuales/alistamiento.css">
        <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
        <style>

        </style>
    </head>
    <body>
        <div class="layout has-sidebar fixed-sidebar fixed-header">
            <?php
                $activado = "Estadisticas";
                include('partes/sidebar.php');
            ?>  
            <div id="overlay" class="overlay"></div>
            <div class="layout">
                <main class="content">
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <div class="table" id = "contenidoEscritorio">
                        <table id="tablaEstadisticasCarga">
                            <thead>
                                <tr>
                                    <th>Prefijo - #Factura</th>
                                    <th>Nombre - Razón social</th>
                                    <th>Hora factura</th>
                                    <th>Vendedor</th>
                                    <th>Alistador - Hora FECHA alistamiento</th>
                                    <th>DURACIÓN ALISTAMIENTO</th>
                                    <th>Verificador - Hora FECHA verificacion</th>
                                    <th>DURACIÓN VERIFICACION</th>
                                    <th>Entregado - Hora FECHA entrega</th>
                                    <th>DURACIÓN ENTREGA</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <br>
                </main>
            </div>
        </div>
        <!-- Incluye la biblioteca jQuery -->
        <script src="js/jquery-3.6.0.min.js"></script>
        <!-- Incluye la biblioteca DataTables -->
        <script type="text/javascript" charset="utf8" src="js/jquery.dataTables.js"></script>
        <script>

            var botonA = document.getElementById('botonA');
            var botonV = document.getElementById('botonV');
            var botonE = document.getElementById('botonE');

            botonA.addEventListener('click', function() {
                cargarTabla(1);
            });

            botonV.addEventListener('click', function() {
                cargarTabla(2);
            });
        
            botonE.addEventListener('click', function() {
                cargarTabla(3);
            });

            function cargarTabla(numero){

                numeroActual = numero;

                console.log(numeroActual);

                tablaEstadisticas.ajax.reload();

            }

            //$(document).ready( function () {

                var controlador = 1;
                
                var numeroActual = 0;

                var tablaEstadisticas = $('#tablaEstadisticasCarga').DataTable({
                    destroy: true,
                    responsive: true,
                    processing: true,
                    pageLength: 10,
                    ajax: {
                        url: 'core/tabla_estadisticas_carga.php',
                        type: 'GET',
                        data: function(data) {
                            data.numero = numeroActual;
                            // data.numero = 1;
                        }
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
                        {data: 'hora', name:'hora', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'cliente', name:'cliente', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'razon', name:'razon', orderable: true, searchable: true, className: 'dt-body-center'}
                    ],

                });

            //});

        </script>
        <!-- Incluye la biblioteca jQuery -->
        <script src="js/jquery-3.6.0.min.js"></script>
        <?php
            include('partes/foot.php')
        ?>  
    </body>
</html>