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
                width: 33%;
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
                $activado = "Documentos";
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
                        <div class="column">
                            <h5>Prefijo</h5>
                            <input type="text" id="prefijoFiltro">
                        </div>
                    </div>
                    <br>
                    <button class='btn btn-warning' id = "borrarFechas">Limpiar filtros</button>
                    <br>
                    <button class='btn btn-danger' id = "borrarAlistamientos">Eliminar registros para Alistamiento sin empezar</button>
                    <br>
                    <button class='btn btn-success' id = "generarInforme">Generar Informe</button>
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

            var tablaEstadisticas;

            var botonB = document.getElementById('borrarFechas');
            var botonA = document.getElementById('borrarAlistamientos');

            const fechaInicioInput = document.getElementById('fechaInicio');
            const fechaFinInput = document.getElementById('fechaFin');
            const prefijoFiltroInput = document.getElementById('prefijoFiltro');
            
            /*
            fechaInicioInput.addEventListener('change', function() {
                tablaEstadisticas.ajax.reload();
            });

            fechaFinInput.addEventListener('change', function() {
                tablaEstadisticas.ajax.reload();
            });
            */
            botonB.addEventListener('click', function() {
                fechaInicioInput.value = null;
                fechaFinInput.value = null;
                prefijoFiltroInput.value = null;
                tablaEstadisticas.clear().draw();
            });

            botonA.addEventListener('click', function() {
                
                Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Una vez eliminados los registros no se pueden recuperar!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, estoy seguro',
                cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {

                        let fecIni = $('#fechaInicio').val();
                        let fecFin = $('#fechaFin').val();
                        let prefijoBorrar = $('#prefijoFiltro').val();

                        if (!fecIni || !fecFin) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Por favor, ingresa ambas fechas antes de continuar.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            var dataToSend = {
                                fecIni: fecIni,
                                fecFin: fecFin,
                                prefijoBorrar : prefijoBorrar
                            };

                            var requestOptions = {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify(dataToSend)
                            };

                            fetch('controladores/borrarAlistamientos.php', requestOptions)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Error en la respuesta de la red');
                                }
                                return response.json();
                            })
                            .then(data => {
                                tablaEstadisticas.ajax.reload();
                            })
                            .catch(error => {
                                alert('Error:', error);
                            });

                        }

                    }
                });

            });



            //$(document).ready( function () {

                var controlador = 1;
                
                var numeroActual = 0;

                var iniciadorDT = 0;


                

                $('#generarInforme').on('click', function() {
                    if (iniciadorDT == 0){
                        iniciadorDT = 1;
                        tablaEstadisticas = $('#tablaDocumento').DataTable({
                            destroy: true,
                            responsive: true,
                            processing: true,
                            pageLength: 10,
                            ajax: {
                                url: 'core/tabla_documentos.php',
                                type: 'GET',
                                data: function(data) {
                                    data.inicial = $('#fechaInicio').val(),
                                    data.final = $('#fechaFin').val(),
                                    data.prefijoFiltrar = $('#prefijoFiltro').val()
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
                                {data: 'horaAlistado', name:'horaAlistado', orderable: true, searchable: true},
                                {data: 'verificador', name:'verificador', orderable: true, searchable: true},
                                {data: 'horaVerificado', name:'horaVerificado', orderable: true, searchable: true},
                                {data: 'entregador', name:'entregador', orderable: true, searchable: true},
                                {data: 'horaEntrega', name:'horaEntrega', orderable: true, searchable: true},
                                {data: 'estado', name:'estado', orderable: true, searchable: true}
                            ]
                        });
                    }else{
                        tablaEstadisticas.ajax.reload();
                    }
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