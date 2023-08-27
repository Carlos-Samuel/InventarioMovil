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
        </style>
    </head>
    <body>
        <div class="layout has-sidebar fixed-sidebar fixed-header">
            <?php
                $activado = "Usuario";
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


                    <table id="tablaUsuarios">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>Cedula</th>
                                <th>Email</th>
                                <th>Editar</th>
                                <th>Contraseña</th>
                            </tr>
                        </thead>
                    </table>
                    <br>
                    <a href = "crear_usuario.php" ><button class="btn btn-primary primeButton" type="button">Crear usuario</button></a>
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

                var miTabla = $('#tablaUsuarios').DataTable({
                    destroy: true,
                    responsive: true,
                    processing: true,
                    pageLength: 10,
                    ajax: {
                        url: 'core/tabla_index_usuarios.php',
                        type: 'GET',
                        // data: function(data) {
                        //     data.empresa = empresa;
                        // }
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
                        {data: 'apellidos', name:'apellidos', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'cedula', name:'cedula', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'email', name:'email', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'editar', name:'editar', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'password', name:'password', orderable: true, searchable: true, className: 'dt-body-center'}

                    ],
                });

                $('.btnMostrar').on('click', function() {
                    var id = $(this).closest('tr').find('td[data-id]').data('id');
                    console.log('Valor de data-id:', id);
                    window.location.href = 'detalle_usuario.php';
                });

                $('#recargar').on('click', function() {
                    miTabla.ajax.reload(null, false); // Recarga la tabla sin perder la paginación
                });
            });
        </script>
    </body>
</html>