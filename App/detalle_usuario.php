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

    require_once 'controladores/Connection.php';

    if(isset($_GET['id'])) {
        try{
            $id_recibido = urldecode($_GET['id']);

            $con = Connection::getInstance()->getConnection();
            $quer = $con->query("select * from Usuarios where idUsuarios = " . $id_recibido . ";");
    
            if ($quer->num_rows > 0) {
                // Existen resultados, obtén la fila
                $row = $quer->fetch_assoc();
        
                // Accede a los valores de la fila
                $idUsuarios = $row['idUsuarios'];
                $Cedula = $row['Cedula'];
                $Nombres = $row['Nombres'];
                $Apellidos = $row['Apellidos'];
                $Correo = $row['Correo'];
    
            } else {

            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "No se recibió ningún valor.";
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
            /* Estilos para el botón */
            .btnActivar {
                background-color: green;
                color: white;
                border: none;
                border-radius: 10px;
                padding: 5px 10px;
                cursor: pointer;
            }

            .btnDesactivar {
                background-color: red;
                color: white;
                border: none;
                border-radius: 10px;
                padding: 5px 10px;
                cursor: pointer;
            }

            #tablaPermisos {
                width: 100%; /* Ancho de la tabla al 100% */
                border-collapse: collapse; /* Colapso de bordes para un diseño más limpio */
                margin: 20px 0; /* Margen superior e inferior */
                font-family: Arial, sans-serif;
            }

            #tablaPermisos th,
            #tablaPermisos td {
                padding: 10px; /* Espaciado interior de celdas */
                border: 1px solid #ccc; /* Borde para celdas */
            }

            #tablaPermisos th {
                background-color: #f2f2f2; /* Color de fondo para encabezados */
                text-align: left; /* Alineación de texto en encabezados */
            }

            /* Estilo para la columna específica */
            #tablaPermisos td:nth-child(4) {
                width: 50px; /* Ancho de la columna específica */
                text-align: center; /* Alineación de texto al centro */
            }
        </style>

    </head>
    <body>
        <div class="layout has-sidebar fixed-sidebar fixed-header">
            <?php
                $activado = "Usuario";
                include('partes/sidebar.php')
            ?>  
            <div id="overlay" class="overlay"></div>
            <div class="layout">
                <main class="content">
                    <br>
                    <h2><strong>FORMULARIO DE USUARIOS</strong></h2>
                    <br>
                    <form id="formularioUsuario" action="controladores/updateUsuario.php" method="post">
                        <input type ="hidden" id = "idUsuario" name = "idUsuario" value = <?php echo $idUsuarios ?> >
                        <div style="display: flex; justify-content: space-between;">
                            <div style="width: 45%;">
                                <label for="cedula"><strong>Cedula</strong></label>
                                <input type="number" id="cedula" name="cedula" value = <?php echo $Cedula ?> required>
                            </div>
                            <div style="width: 45%;">
                                <label for="nombres"><strong>Nombres</strong></label>
                                <input type="text" id="nombres" name="nombres" value = <?php echo $Nombres ?> required>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                            <div style="width: 45%;">
                                <label for="apellidos"><strong>Apellidos</strong></label>
                                <input type="text" id="apellidos" name="apellidos"  value = <?php echo $Apellidos ?> required>
                            </div>
                            <div style="width: 45%;">
                                <label for="correo"><strong>Correo</strong></label>
                                <input type="email" id="correo" name="correo" value = <?php echo $Correo ?> required>
                            </div>
                        </div>
                    
                        <br>
                        <h3><strong>PERMISOS DEL USUARIO</strong></h3>
                        <div class="table">
                            <table id = "tablaPermisos">
                                <thead>
                                    <tr>
                                        <th>#Id</th>
                                        <th>Permiso</th>
                                        <th>Descripcion</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                            </table>   
                            <br>
                            <a><button class="btn btn-primary primeButton" type="submit">Guardar</button></a>
                            <br>  
                        </div>  
                    </form>
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
<!--         
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->

        <script>
            $('.btnCambiar').on('click', function() {
                console.log("bandera");
                if ($(this).hasClass('btn-danger')) {
                    var id = $(this).closest('tr').find('td[data-id]').data('id');
                    console.log('Botón Danger - Valor de data-id:', id);
                    // Aquí podrías realizar la lógica de desactivación
                } else if ($(this).hasClass('btn-success')) {
                    var id = $(this).closest('tr').find('td[data-id]').data('id');
                    console.log('Botón Success - Valor de data-id:', id);
                    // Aquí podrías realizar la lógica de activación
                }
            });
            
            $(document).ready( function () {

                var idUsuario = $('#idUsuario').val();

                var tablaPermisos = $('#tablaPermisos').DataTable({
                    destroy: true,
                    responsive: true,
                    processing: true,
                    pageLength: 10,
                    ajax: {
                        url: 'core/tabla_permisos_usuarios.php',
                        type: 'GET',
                        data: function(data) {
                            data.idUsuario = idUsuario;
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
                        {data: 'nombres', name:'nombres', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'descripcion', name:'descripcion', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'estado', name:'estado', orderable: true, searchable: true}
                    ],
                });
                
                $(document).on('click', '.btnCambiar', function() {

                    var action = $(this).attr('data-action');
                    var id = $(this).attr('data-id');
      
                    if (action === 'Desactivar') {

                        var dataToSend = {
                            idUsuario: idUsuario,
                            idPermiso: id
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
                        fetch('controladores/desactivarPermiso.php', requestOptions)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Error en la respuesta de la red');
                                }
                                return response.json();
                            })
                            .then(data => {
                                console.log('Respuesta:', data);
                                tablaPermisos.ajax.reload();
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                tablaPermisos.ajax.reload();
                            });

                    } else if (action === 'Activar') {

                        var dataToSend = {
                            idUsuario: idUsuario,
                            idPermiso: id
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
                        fetch('controladores/activarPermiso.php', requestOptions)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Error en la respuesta de la red');
                                }
                                return response.json();
                            })
                            .then(data => {
                                console.log('Respuesta:', data);
                                tablaPermisos.ajax.reload();
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                tablaPermisos.ajax.reload();
                            });
                    }
                });

            } );
        </script>
    </body>
</html>