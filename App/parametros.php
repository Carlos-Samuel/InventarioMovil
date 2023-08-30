<?php
	session_start(); 	

	if (!isset($_SESSION["cedula"]) || !isset($_SESSION["nombres"])) {
		header("Location: index.php");
		exit();
	}

    $permiso = "Admin";

    if (!strpos($_SESSION['permisos'], $permiso)) {
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
        <style>
            .mensaje {
                position: relative;
                background-color: #e0e0e0;
                padding: 10px;
                border: 1px solid #888;
                text-align: center;
            }

            .cerrar {
                position: absolute;
                top: 5px;
                right: 5px;
                cursor: pointer;
            }

            #botonEmbalaje {
                width: 400px; 
                height: 50px; 
                font-size: 24px;
                margin-top: 10px;
            }

        </style>
    </head>
    <body>
        <div class="layout has-sidebar fixed-sidebar fixed-header">
            <?php
                $activado = "Parametros";
                include('partes/sidebar.php');
            ?>  

            <div id="overlay" class="overlay"></div>
            <div class="layout">
                <main class="content">
                <?php 
                    if(isset($_GET['mensaje'])) {
                        $mensaje = $_GET['mensaje'];
                        include('partes/mensaje.php');
                    }
                ?>
                    <h1>Embalajes</h1>
                    <table id="tablaParametros">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Descripcion</th>
                                <th>Eliminar</th>
                            </tr>
                        </thead>
                    </table>
                    <br>
                    <div style="display: flex; justify-content: space-between;">
                            <div style="width: 45%;">
                                <label for="Embalaje"><strong>Embalaje</strong></label>
                                <input type="text" id="embalaje" name="embalaje">
                            </div>
                            <div style="width: 45%;">
                                <br>
                                <button id = "botonEmbalaje" class="btn btn-primary primeButton" type="button">Crear Embalaje</button>
                            </div>
                        </div>
                    <br>
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

                var tablaParametros = $('#tablaParametros').DataTable({
                    destroy: true,
                    responsive: true,
                    processing: true,
                    pageLength: 10,
                    ajax: {
                        url: 'core/tabla_index_parametros.php',
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
                        {data: 'descripcion', name:'descripcion', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'borrar', name:'borrar', orderable: true, searchable: true, className: 'dt-body-center'}
                    ],
                });

                $('#botonEmbalaje').on('click', function() {

                    var dataToSend = {
                        embalaje: $('#embalaje').val(),
                    };

                    // Configuración de la solicitud
                    var requestOptions = {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(dataToSend)
                    };

                    // Realizar la solicitud utilizando fetch
                    fetch('controladores/crearEmbalaje.php', requestOptions)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Error en la respuesta de la red');
                            }
                            return response.json();
                        })
                        .then(data => {
                            tablaParametros.ajax.reload();
                            $('#embalaje').val('');
                        })
                        .catch(error => {
                            alert('Erroal insertar');
                            tablaParametros.ajax.reload();
                            $('#embalaje').val('');
                        });
                    
                });

                $(document).on('click', '.btnEliminar', function() {
                    var id = $(this).attr('data-id');

                    var dataToSend = {
                        idEmbalaje: id,
                    };

                    // Configuración de la solicitud
                    var requestOptions = {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(dataToSend)
                    };

                    // Realizar la solicitud utilizando fetch
                    fetch('controladores/borrarEmbalaje.php', requestOptions)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Error en la respuesta de la red');
                            }
                            return response.json();
                        })
                        .then(data => {
                            tablaParametros.ajax.reload();
                            $('#embalaje').val('');
                        })
                        .catch(error => {
                            alert('Erro al insertar');
                            tablaParametros.ajax.reload();
                            $('#embalaje').val('');
                        });
                    
                });
            });
        </script>
    </body>
</html>