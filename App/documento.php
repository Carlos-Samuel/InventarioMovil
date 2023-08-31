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
        </style>
    </head>
    <body>
        <div class="layout has-sidebar fixed-sidebar fixed-header">
            <?php
                $activado = "Documentos";
                include('partes/sidebar.php');
            ?>  
            <div id="overlay" class="overlay"></div>
            <div class="layout">
                <main class="content">
                    <h5>Fecha inicio</h5>
                    <input type="date" id="fechaInicio">
                    <br>
                    <h5>Fecha fin</h5>
                    <input type="date" id="fechaFin">
                    <button class='btn btn-danger' id = "borrarFechas">Borrar</button>
                    <br>
                    <table id="tablaDocumento">
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
                        url: 'core/tabla_documentos.php',
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
                        {data: 'nombre', name:'nombre', orderable: true, searchable: true},
                        {data: 'fecha', name:'fecha', orderable: true, searchable: true},
                        {data: 'vendedor', name:'vendedor', orderable: true, searchable: true},
                        {data: 'alistador', name:'alistador', orderable: true, searchable: true},
                        {data: 'horaAlistado', name:'vendedor', orderable: true, searchable: true},
                        {data: 'verificador', name:'alistador', orderable: true, searchable: true},
                        {data: 'horaVerificado', name:'horaAlistado', orderable: true, searchable: true},
                        {data: 'entregador', name:'verificador', orderable: true, searchable: true},
                        {data: 'horaEntrega', name:'horaVerificado', orderable: true, searchable: true},
                        {data: 'estado', name:'estado', orderable: true, searchable: true}

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