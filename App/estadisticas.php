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
        <!-- <link rel="stylesheet" href="css_individuales/alistamiento.css"> -->
        <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
        <style>
            /* Estilos para la tabla */
            .table {
                max-width: 800px !important;
                margin: 0 auto !important;
            }

            #tablaAlistado, #tablaVerificado, #tablaEntrega {
                width: 100% !important;
                border-collapse: collapse !important;
                margin: 0 !important;
            }

            #tablaAlistado th, #tablaAlistado td, #tablaVerificado th, #tablaVerificado td, #tablaEntrega th, #tablaEntrega td {
                border: 1px solid #ccc !important;
                padding: 8px !important;
                text-align: center !important;
                font-size: 12px !important;
            }

            .column {
                float: left;
                width: 50%;
            }

            .row:after {
                content: "";
                display: table;
                clear: both;
            }
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
                    <div class="row">
                        <div class="column">
                            <h5>Fecha inicio</h5>
                            <input type="date" id="fechaInicio">
                        </div>
                        <div class="column">
                            <h5>Fecha fin</h5>
                            <input type="date" id="fechaFin">
                        </div>
                    </div>
                    <br>
                    <button class='btn btn-warning' id = "borrarFechas">Limpiar filtros</button>
                    <br>
                    <button class='btn btn-success' id = "generarInforme">Generar Informe</button>
                    <br>
                    <table id="tablaAlistado">
                        <thead>
                            <tr>
                                <th>Alistador</th>
                                <th>Rango de fecha</th>
                                <th># de facturas alistadas</th>
                                <th># items</th>
                                <th># de productos alistados</th>
                                <th>Tiempo total</th>
                                <th>Tiempo promedio cada item</th>
                            </tr>
                        </thead>
                    </table>
                    <br>
                    <table id="tablaVerificado">
                        <thead>
                            <tr>
                                <th>Verificador</th>
                                <th>Rango de fecha</th>
                                <th># de facturas verificadas</th>
                                <th># items</th>
                                <th># de productos verificados</th>
                                <th>T. total</th>
                                <th>T. promedio cada item</th>
                                <th>T. total entre fin a. a inicio v.</th>
                                <th>T. promedio entre fin a. a inicio v.</th>
                            </tr>
                        </thead>
                    </table>
                    <br>
                    <table id="tablaEntrega">
                        <thead>
                            <tr>
                                <th>Entregador</th>
                                <th>Rango de fecha</th>
                                <th># de facturas entregadas</th>
                                <th># items</th>
                                <th># de productos entregados</th>
                                <th>T. total</th>
                                <th>T. promedio cada item</th>
                                <th>T. total entre fin a. a inicio v.</th>
                                <th>T. promedio entre fin a. a inicio v.</th>
                            </tr>
                        </thead>
                    </table>
                    <br>
                </main>
            </div>
        </div>
        <!-- Incluye la biblioteca jQuery -->
        <script src="js/jquery-3.6.0.min.js"></script>
        <!-- Incluye la biblioteca DataTables -->
        <script type="text/javascript" charset="utf8" src="js/jquery.dataTables.js"></script>

        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

        <script>

            var tablaAlistado;
            var tablaVerificado;
            var tablaEntrega;

            var botonB = document.getElementById('borrarFechas');

            const fechaInicioInput = document.getElementById('fechaInicio');
            const fechaFinInput = document.getElementById('fechaFin');
            
            botonB.addEventListener('click', function() {
                fechaInicioInput.value = null;
                fechaFinInput.value = null;
                tablaEstadisticas.clear().draw();
            });

            var iniciador = 0;

            $('#generarInforme').on('click', function() {
                if (iniciador == 0){
                    iniciador = 1;

                    tablaAlistado = $('#tablaAlistado').DataTable({
                        destroy: true,
                        responsive: true,
                        processing: true,
                        pageLength: 10,
                        ajax: {
                            url: 'core/tabla_alistado.php',
                            type: 'GET',
                            data: function(data) {
                                data.inicial = $('#fechaInicio').val(),
                                data.final = $('#fechaFin').val()
                            }
                        },
                        dom: 'Bfrtip',
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print'
                        ],
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
                            {data: 'alistador', name:'alistador', orderable: true, searchable: true},
                            {data: 'rango', name:'rango', orderable: true, searchable: true},
                            {data: 'facturasAlistadas', name:'facturasAlistadas', orderable: true, searchable: true},
                            {data: 'items', name:'vendedor', orderable: true, searchable: true},
                            {data: 'productosAlistados', name:'productosAlistados', orderable: true, searchable: true},
                            {data: 'tiempoTotal', name:'tiempoTotal', orderable: true, searchable: true},
                            {data: 'tiempoPromedio', name:'tiempoPromedio', orderable: true, searchable: true}
                        ]
                    });

                    tablaVerificado = $('#tablaVerificado').DataTable({
                        destroy: true,
                        responsive: true,
                        processing: true,
                        pageLength: 10,
                        ajax: {
                            url: 'core/tabla_verificado.php',
                            type: 'GET',
                            data: function(data) {
                                data.inicial = $('#fechaInicio').val(),
                                data.final = $('#fechaFin').val()
                            }
                        },
                        dom: 'Bfrtip',
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print'
                        ],
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
                            {data: 'verificador', name:'verificador', orderable: true, searchable: true},
                            {data: 'rango', name:'rango', orderable: true, searchable: true},
                            {data: 'facturasVerificadas', name:'facturasVerificadas', orderable: true, searchable: true},
                            {data: 'items', name:'items', orderable: true, searchable: true},
                            {data: 'productosVerificados', name:'productosVerificados', orderable: true, searchable: true},
                            {data: 'tiempoTotal', name:'tiempoTotal', orderable: true, searchable: true},
                            {data: 'tiempoPromedio', name:'tiempoPromedio', orderable: true, searchable: true},
                            {data: 'tiempoTotalQuieto', name:'tiempoTotalQuieto', orderable: true, searchable: true},
                            {data: 'tiempoPromedioQuieto', name:'tiempoPromedioQuieto', orderable: true, searchable: true}
                        ]
                    });

                    tablaEntrega = $('#tablaEntrega').DataTable({
                        destroy: true,
                        responsive: true,
                        processing: true,
                        pageLength: 10,
                        ajax: {
                            url: 'core/tabla_entregado.php',
                            type: 'GET',
                            data: function(data) {
                                data.inicial = $('#fechaInicio').val(),
                                data.final = $('#fechaFin').val()
                            }
                        },
                        dom: 'Bfrtip',
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print'
                        ],
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
                            {data: 'entregador', name:'entregador', orderable: true, searchable: true},
                            {data: 'rango', name:'rango', orderable: true, searchable: true},
                            {data: 'facturasEntregadas', name:'facturasEntregadas', orderable: true, searchable: true},
                            {data: 'items', name:'items', orderable: true, searchable: true},
                            {data: 'productosEntregados', name:'productosEntregados', orderable: true, searchable: true},
                            {data: 'tiempoTotal', name:'tiempoTotal', orderable: true, searchable: true},
                            {data: 'tiempoPromedio', name:'tiempoPromedio', orderable: true, searchable: true},
                            {data: 'tiempoTotalQuieto', name:'tiempoTotalQuieto', orderable: true, searchable: true},
                            {data: 'tiempoPromedioQuieto', name:'tiempoPromedioQuieto', orderable: true, searchable: true}
                        ]
                    });


                }else{
                    tablaAlistado.ajax.reload();
                    tablaVerificado.ajax.reload();
                    tablaEntrega.ajax.reload();
                }
            });

        </script>
        <!-- Incluye la biblioteca jQuery -->
        <script src="js/jquery-3.6.0.min.js"></script>
        <?php
            include('partes/foot.php')
        ?>  
    </body>
</html>