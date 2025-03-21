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

            #tablaDocumento {
                width: 100% !important;
                border-collapse: collapse !important;
                margin: 0 !important;
            }

            #tablaDocumento th, #tablaDocumento td {
                border: 1px solid #ccc !important;
                padding: 8px !important;
                text-align: center !important;
                font-size: 12px !important;
            }

            .column {
                float: left;
                width: 50%;
            }

            /* Clear floats after the columns */
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
                $activado = "CForzado";
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
                    <button class='btn btn-danger' id = "borrarFechas">Limpiar fechas</button>
                    <br>
                    <table id="tablaDocumento">
                        <thead>
                            <tr>
                                <th>Prefijo - #Factura</th>
                                <th>Fecha alistado</th>
                                <th>Persona que autorizó</th>
                                <th>Alistador</th>
                                <th>Observacion</th>
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
        <script>

            var botonB = document.getElementById('borrarFechas');

            const fechaInicioInput = document.getElementById('fechaInicio');
            const fechaFinInput = document.getElementById('fechaFin');

            
            fechaInicioInput.addEventListener('change', function() {
                tablaEstadisticas.ajax.reload();
            });

            fechaFinInput.addEventListener('change', function() {
                tablaEstadisticas.ajax.reload();
            });

            botonB.addEventListener('click', function() {
                fechaInicioInput.value = null;
                fechaFinInput.value = null;
                tablaEstadisticas.ajax.reload();
            });


            //$(document).ready( function () {

                var controlador = 1;
                
                var numeroActual = 0;

                var tablaEstadisticas = $('#tablaDocumento').DataTable({
                    destroy: true,
                    responsive: true,
                    processing: true,
                    pageLength: 10,
                    ajax: {
                        url: 'core/tabla_cierre_forzado_informe.php',
                        type: 'GET',
                        data: function(data) {
                            data.inicial = $('#fechaInicio').val(),
                            data.final = $('#fechaFin').val()
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
                        {data: 'id', name:'id', orderable: true, searchable: true},
                        {data: 'fecha_alistado', name:'fecha_alistado', orderable: true, searchable: true},
                        {data: 'forzador', name:'forzador', orderable: true, searchable: true},
                        {data: 'alistador', name:'alistador', orderable: true, searchable: true},
                        {data: 'ObservacionesFor', name:'ObservacionesFor', orderable: true, searchable: true}

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